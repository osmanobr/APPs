<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/Pagamento.php'; // Para criar pagamento associado ao fechar pedido

class Pedido {
    private $pdo;
    private $pagamentoModel;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
        $this->pagamentoModel = new Pagamento();
    }

    /**
     * Cria um novo pedido.
     *
     * @param array $dados Dados do pedido: inquilino_id, funcionario_id (opcional), observacoes (opcional), forma_pagamento_prevista (opcional)
     * @return string|false O UUID do pedido criado ou false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO pedidos (id, inquilino_id, funcionario_id, status, forma_pagamento_prevista, valor_total_calculado, observacoes, criado_em, atualizado_em)
                    VALUES (:id, :inquilino_id, :funcionario_id, :status, :forma_pagamento_prevista, 0.00, :observacoes, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $stmt = $this->pdo->prepare($sql);

            $status_inicial = 'aberto';
            $funcionario_id = $dados['funcionario_id'] ?? null;
            $observacoes = $dados['observacoes'] ?? null;
            $forma_pagamento_prevista = $dados['forma_pagamento_prevista'] ?? null;

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':inquilino_id', $dados['inquilino_id']);
            $stmt->bindParam(':funcionario_id', $funcionario_id);
            $stmt->bindParam(':status', $status_inicial);
            $stmt->bindParam(':forma_pagamento_prevista', $forma_pagamento_prevista);
            $stmt->bindParam(':observacoes', $observacoes);

            if ($stmt->execute()) {
                add_log('info', 'pedido_criado', "Pedido ID {$uuid} criado para Inquilino ID {$dados['inquilino_id']}.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'pedido_criar_falha_db', "PDOException ao criar pedido: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar pedido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um pedido pelo ID com detalhes (inquilino, funcionário).
     * @param string $id UUID do pedido.
     * @return array|false
     */
    public function getByIdWithDetails($id) {
        try {
            $sql = "SELECT
                        p.*,
                        i.nome as nome_inquilino,
                        i.email as email_inquilino,
                        u.nome as nome_funcionario,
                        (SELECT GROUP_CONCAT(CONCAT(ip.nome_item, ' (Qtd: ', ip.quantidade, ', Subtotal: ', ip.subtotal, ')') SEPARATOR '; ')
                         FROM itens_pedido ip WHERE ip.pedido_id = p.id) as itens_resumo,
                        (SELECT pg.status FROM pagamentos pg WHERE pg.origem_id = p.id AND pg.origem_tipo = 'pedido' ORDER BY pg.criado_em DESC LIMIT 1) as status_pagamento_atual,
                        (SELECT pg.id FROM pagamentos pg WHERE pg.origem_id = p.id AND pg.origem_tipo = 'pedido' ORDER BY pg.criado_em DESC LIMIT 1) as pagamento_principal_id
                    FROM pedidos p
                    JOIN inquilinos i ON p.inquilino_id = i.id
                    LEFT JOIN usuarios u ON p.funcionario_id = u.id
                    WHERE p.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'pedido_buscar_id_detalhes_falha_db', "PDOException ao buscar pedido ID {$id} com detalhes: " . $e->getMessage());
            error_log("Erro ao buscar pedido por ID com detalhes: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os pedidos com detalhes.
     * @param array $filtros Filtros (ex: inquilino_id, status)
     * @return array
     */
    public function getAllWithDetails($filtros = []) {
        try {
            $sql = "SELECT
                        p.id, p.status, p.forma_pagamento_prevista, p.valor_total_calculado, p.criado_em,
                        i.nome as nome_inquilino,
                        u.nome as nome_funcionario,
                        (SELECT pg.status FROM pagamentos pg WHERE pg.origem_id = p.id AND pg.origem_tipo = 'pedido' ORDER BY pg.criado_em DESC LIMIT 1) as status_pagamento_atual
                    FROM pedidos p
                    JOIN inquilinos i ON p.inquilino_id = i.id
                    LEFT JOIN usuarios u ON p.funcionario_id = u.id";

            $where_clauses = [];
            $params = [];

            if (!empty($filtros['inquilino_id'])) {
                $where_clauses[] = "p.inquilino_id = :inquilino_id";
                $params[':inquilino_id'] = $filtros['inquilino_id'];
            }
            if (!empty($filtros['status'])) {
                $where_clauses[] = "p.status = :status";
                $params[':status'] = $filtros['status'];
            }
            // Adicionar mais filtros se necessário (data, etc.)

            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(" AND ", $where_clauses);
            }
            $sql .= " ORDER BY p.criado_em DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'pedido_listar_detalhes_falha_db', "PDOException ao listar pedidos: " . $e->getMessage());
            error_log("Erro ao listar pedidos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Recalcula o valor_total_calculado de um pedido com base nos seus itens.
     * @param string $pedido_id UUID do pedido.
     * @return bool True se sucesso, false se falha.
     */
    public function recalcularTotal($pedido_id) {
        try {
            $sql_sum = "SELECT SUM(subtotal) as total_itens FROM itens_pedido WHERE pedido_id = :pedido_id";
            $stmt_sum = $this->pdo->prepare($sql_sum);
            $stmt_sum->bindParam(':pedido_id', $pedido_id);
            $stmt_sum->execute();
            $result = $stmt_sum->fetch(PDO::FETCH_ASSOC);
            $novo_total = $result['total_itens'] ?? 0.00;

            $sql_update = "UPDATE pedidos SET valor_total_calculado = :novo_total, atualizado_em = CURRENT_TIMESTAMP WHERE id = :pedido_id";
            $stmt_update = $this->pdo->prepare($sql_update);
            $stmt_update->bindParam(':novo_total', $novo_total);
            $stmt_update->bindParam(':pedido_id', $pedido_id);

            return $stmt_update->execute();

        } catch (PDOException $e) {
            add_log('erro', 'pedido_recalcular_total_falha_db', "PDOException ao recalcular total do Pedido ID {$pedido_id}: " . $e->getMessage());
            error_log("Erro ao recalcular total do pedido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fecha um pedido, atualiza seu status e gera um pagamento pendente.
     * @param string $pedido_id UUID do pedido.
     * @param string|null $forma_pagamento_final Forma de pagamento escolhida ao fechar (opcional).
     * @return array ['success' => bool, 'message' => string, 'pagamento_id' => string|null]
     */
    public function fecharPedido($pedido_id, $forma_pagamento_final = null) {
        $this->pdo->beginTransaction();
        try {
            $pedido = $this->getByIdWithDetails($pedido_id);
            if (!$pedido || $pedido['status'] !== 'aberto') {
                $this->pdo->rollBack();
                $msg = $pedido ? "Pedido não está com status 'aberto'." : "Pedido não encontrado.";
                $_SESSION['error_message'] = $msg;
                return ['success' => false, 'message' => $msg];
            }

            // Recalcular total uma última vez para garantir
            if (!$this->recalcularTotal($pedido_id)) {
                 $this->pdo->rollBack();
                 $_SESSION['error_message'] = "Erro ao recalcular o total do pedido antes de fechar.";
                return ['success' => false, 'message' => "Erro ao recalcular o total do pedido."];
            }
            // Pegar o pedido atualizado com o total correto
            $pedido_atualizado = $this->getByIdWithDetails($pedido_id);
            $valor_a_cobrar = $pedido_atualizado['valor_total_calculado'];

            $pagamento_id_gerado = null;
            if ($valor_a_cobrar > 0) {
                $dados_pagamento = [
                    'origem_tipo' => 'pedido',
                    'origem_id' => $pedido_id,
                    'inquilino_id' => $pedido_atualizado['inquilino_id'],
                    'valor' => $valor_a_cobrar,
                    'status' => 'pendente',
                    'forma_pagamento' => $forma_pagamento_final ?? $pedido_atualizado['forma_pagamento_prevista'],
                    'descricao' => "Pagamento referente ao Pedido ID: {$pedido_id}"
                ];
                $pagamento_id_gerado = $this->pagamentoModel->create($dados_pagamento);
                if (!$pagamento_id_gerado) {
                    $this->pdo->rollBack();
                    $_SESSION['error_message'] = $_SESSION['error_message'] ?? "Erro ao criar registro de pagamento para o pedido.";
                    return ['success' => false, 'message' => $_SESSION['error_message']];
                }
            }

            // Atualizar status do pedido para 'fechado' (ou 'pago' se valor for 0 e não precisar de pagamento)
            $novo_status_pedido = ($valor_a_cobrar > 0) ? 'fechado' : 'pago'; // Se valor 0, já considera pago.

            $sql_update = "UPDATE pedidos SET status = :status, forma_pagamento_prevista = COALESCE(:forma_pagamento_final, forma_pagamento_prevista), atualizado_em = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt_update = $this->pdo->prepare($sql_update);
            $stmt_update->bindParam(':status', $novo_status_pedido);
            $stmt_update->bindParam(':forma_pagamento_final', $forma_pagamento_final);
            $stmt_update->bindParam(':id', $pedido_id);

            if (!$stmt_update->execute()) {
                $this->pdo->rollBack();
                add_log('erro', 'pedido_fechar_falha_db_update', "PDOException ao fechar Pedido ID {$pedido_id} (update).", get_logged_in_user_id());
                $_SESSION['error_message'] = "Erro ao atualizar o status do pedido para fechado.";
                return ['success' => false, 'message' => "Erro ao atualizar o status do pedido."];
            }

            $this->pdo->commit();
            add_log('info', 'pedido_fechado', "Pedido ID {$pedido_id} fechado. Valor: {$valor_a_cobrar}. Pagamento ID: {$pagamento_id_gerado}", get_logged_in_user_id());
            $message = "Pedido fechado com sucesso.";
            if($pagamento_id_gerado) $message .= " Pagamento pendente gerado (ID: {$pagamento_id_gerado}).";
            else if ($valor_a_cobrar == 0) $message .= " Nenhum valor a cobrar.";

            return [
                'success' => true,
                'message' => $message,
                'pagamento_id' => $pagamento_id_gerado,
                'valor_cobrado' => $valor_a_cobrar
            ];

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            add_log('erro', 'pedido_fechar_falha_db_transacao', "PDOException na transação de fechar Pedido ID {$pedido_id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro (transação) ao fechar pedido: " . $e->getMessage());
            $_SESSION['error_message'] = "Erro crítico ao processar o fechamento do pedido.";
            return ['success' => false, 'message' => "Erro crítico."];
        }
    }

    /**
     * Atualiza o status de um pedido, por exemplo, para 'pago' ou 'cancelado'.
     * Geralmente chamado após um pagamento ser confirmado ou se o pedido for cancelado.
     * @param string $pedido_id
     * @param string $novo_status ('pago', 'cancelado', 'aberto')
     * @return bool
     */
    public function updateStatus($pedido_id, $novo_status) {
        try {
            $sql = "UPDATE pedidos SET status = :status, atualizado_em = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':status', $novo_status);
            $stmt->bindParam(':id', $pedido_id);
            $success = $stmt->execute();
            if ($success) {
                add_log('info', 'pedido_status_atualizado', "Status do Pedido ID {$pedido_id} atualizado para '{$novo_status}'.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'pedido_update_status_falha_db', "PDOException ao atualizar status do Pedido ID {$pedido_id}: " . $e->getMessage());
            error_log("Erro ao atualizar status do pedido: " . $e->getMessage());
            return false;
        }
    }


    // Métodos para buscar inquilinos e funcionários para formulários
    public function getAllInquilinosSimple() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome, email FROM inquilinos ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    public function getAllFuncionariosSimple() { // Funcionários e Admins podem registrar pedidos
        try {
            $stmt = $this->pdo->query("SELECT id, nome FROM usuarios WHERE nivel_acesso IN ('admin', 'funcionario', 'vendedor') ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    /**
     * Conta o número de pedidos com status 'aberto'.
     * @return int
     */
    public function countPedidosAbertos() {
        try {
            $sql = "SELECT COUNT(id) FROM pedidos WHERE status = 'aberto'";
            $stmt = $this->pdo->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'pedido_count_abertos_falha_db', "PDOException ao contar pedidos abertos: " . $e->getMessage());
            error_log("Erro ao contar pedidos abertos: " . $e->getMessage());
            return 0;
        }
    }
}
?>
