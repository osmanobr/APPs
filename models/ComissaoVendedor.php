<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';

class ComissaoVendedor {
    private $pdo;
    // Definir percentual de comissão padrão aqui ou buscar de configurações/tabela de vendedores
    const PERCENTUAL_COMISSAO_PADRAO = 0.05; // Exemplo: 5%

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Cria um novo registro de comissão.
     * Geralmente chamado quando o pagamento de uma reserva associada a um vendedor é confirmado.
     *
     * @param array $dados Dados da comissão: vendedor_id, reserva_id, pagamento_id (que quitou a reserva),
     *                     valor_base_comissao (valor da reserva sobre o qual a comissão incide),
     *                     percentual_comissao (opcional, usa padrão se não fornecido),
     *                     valor_comissao (calculado ou fornecido), observacoes (opcional)
     * @return string|false O UUID da comissão criada ou false em caso de falha.
     */
    public function create($dados) {
        // Verificar se já existe comissão para esta reserva/pagamento para evitar duplicidade
        if (isset($dados['reserva_id']) && isset($dados['pagamento_id'])) {
            $sql_check = "SELECT id FROM comissoes_vendedores WHERE reserva_id = :reserva_id AND pagamento_id = :pagamento_id";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->bindParam(':reserva_id', $dados['reserva_id']);
            $stmt_check->bindParam(':pagamento_id', $dados['pagamento_id']);
            $stmt_check->execute();
            if ($stmt_check->fetch()) {
                add_log('aviso', 'comissao_duplicada_tentativa', "Tentativa de criar comissão duplicada para Reserva ID {$dados['reserva_id']} e Pagamento ID {$dados['pagamento_id']}.", get_logged_in_user_id());
                // Não necessariamente um erro fatal, mas não cria nova. Poderia retornar o ID existente.
                return false;
            }
        }


        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO comissoes_vendedores (id, vendedor_id, reserva_id, pagamento_id, percentual_comissao, valor_base_comissao, valor_comissao, status, observacoes, criado_em)
                    VALUES (:id, :vendedor_id, :reserva_id, :pagamento_id, :percentual_comissao, :valor_base_comissao, :valor_comissao, :status, :observacoes, CURRENT_TIMESTAMP)";
            $stmt = $this->pdo->prepare($sql);

            $percentual = $dados['percentual_comissao'] ?? self::PERCENTUAL_COMISSAO_PADRAO;
            $valor_comissao_calculado = $dados['valor_comissao'] ?? ($dados['valor_base_comissao'] * $percentual);
            $status_inicial = 'pendente';
            $observacoes = $dados['observacoes'] ?? null;
            $pagamento_id = $dados['pagamento_id'] ?? null; // Pagamento que originou a comissão

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':vendedor_id', $dados['vendedor_id']);
            $stmt->bindParam(':reserva_id', $dados['reserva_id']);
            $stmt->bindParam(':pagamento_id', $pagamento_id);
            $stmt->bindParam(':percentual_comissao', $percentual);
            $stmt->bindParam(':valor_base_comissao', $dados['valor_base_comissao']);
            $stmt->bindParam(':valor_comissao', $valor_comissao_calculado);
            $stmt->bindParam(':status', $status_inicial);
            $stmt->bindParam(':observacoes', $observacoes);

            if ($stmt->execute()) {
                add_log('info', 'comissao_criada', "Comissão ID {$uuid} (Valor: {$valor_comissao_calculado}) criada para Vendedor ID {$dados['vendedor_id']} referente à Reserva ID {$dados['reserva_id']}.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'comissao_criar_falha_db', "PDOException ao criar comissão: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar comissão: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca uma comissão pelo ID.
     * @param string $id UUID da comissão.
     * @return array|false
     */
    public function getById($id) {
        try {
            $sql = "SELECT cv.*, u.nome as nome_vendedor, r.id as id_reserva_ref, apt.numero_apartamento, h.nome as nome_hotel_reserva
                    FROM comissoes_vendedores cv
                    JOIN usuarios u ON cv.vendedor_id = u.id
                    JOIN reservas r ON cv.reserva_id = r.id
                    JOIN apartamentos apt ON r.apartamento_id = apt.id
                    JOIN hoteis h ON apt.hotel_id = h.id
                    WHERE cv.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'comissao_buscar_id_falha_db', "PDOException ao buscar Comissão ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todas as comissões com detalhes.
     * @param array $filtros (ex: vendedor_id, status)
     * @return array
     */
    public function getAllWithDetails($filtros = []) {
        try {
            $sql = "SELECT
                        cv.*,
                        u.nome as nome_vendedor,
                        r.id as id_reserva_ref, -- Para linkar para a reserva
                        (SELECT CONCAT(apt.numero_apartamento, ' (', h.nome, ')') FROM apartamentos apt JOIN hoteis h ON apt.hotel_id = h.id WHERE apt.id = r.apartamento_id) as desc_apartamento_reserva,
                        i.nome as nome_inquilino_reserva,
                        p.data_pagamento as data_pagamento_origem
                    FROM comissoes_vendedores cv
                    JOIN usuarios u ON cv.vendedor_id = u.id
                    JOIN reservas r ON cv.reserva_id = r.id
                    JOIN inquilinos i ON r.inquilino_id = i.id
                    LEFT JOIN pagamentos p ON cv.pagamento_id = p.id";

            $where_clauses = [];
            $params = [];

            if (!empty($filtros['vendedor_id'])) {
                $where_clauses[] = "cv.vendedor_id = :vendedor_id";
                $params[':vendedor_id'] = $filtros['vendedor_id'];
            }
            if (!empty($filtros['status'])) {
                $where_clauses[] = "cv.status = :status";
                $params[':status'] = $filtros['status'];
            }
            // Adicionar mais filtros se necessário (data, etc.)

            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(" AND ", $where_clauses);
            }
            $sql .= " ORDER BY cv.criado_em DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'comissao_listar_falha_db', "PDOException ao listar comissões: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza o status de uma comissão (ex: para 'paga').
     * @param string $id UUID da comissão.
     * @param string $novo_status ('paga', 'cancelada').
     * @param string|null $data_pagamento_comissao Data do pagamento da comissão.
     * @return bool
     */
    public function updateStatus($id, $novo_status, $data_pagamento_comissao = null) {
        try {
            $sql = "UPDATE comissoes_vendedores SET status = :status, data_pagamento_comissao = :data_pagamento_comissao WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            if ($novo_status === 'paga' && $data_pagamento_comissao === null) {
                $data_pagamento_comissao = date('Y-m-d H:i:s');
            } elseif ($novo_status !== 'paga') {
                $data_pagamento_comissao = null; // Limpar data se não for 'paga'
            }

            $stmt->bindParam(':status', $novo_status);
            $stmt->bindParam(':data_pagamento_comissao', $data_pagamento_comissao);
            $stmt->bindParam(':id', $id);

            $success = $stmt->execute();
            if ($success) {
                add_log('info', 'comissao_status_atualizado', "Status da Comissão ID {$id} atualizado para '{$novo_status}'.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'comissao_update_status_falha_db', "PDOException ao atualizar status da Comissão ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os vendedores (usuários com nível 'vendedor' ou 'admin')
     * @return array
     */
    public function getAllVendedores() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome FROM usuarios WHERE nivel_acesso IN ('vendedor', 'admin') ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    /**
     * Calcula o valor total de comissões com status 'pendente'.
     * @param string|null $vendedor_id Opcional para filtrar por vendedor.
     * @return float
     */
    public function getTotalComissoesPendentes($vendedor_id = null) {
        try {
            $sql = "SELECT SUM(valor_comissao) FROM comissoes_vendedores WHERE status = 'pendente'";
            $params = [];
            if ($vendedor_id) {
                $sql .= " AND vendedor_id = :vendedor_id";
                $params[':vendedor_id'] = $vendedor_id;
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return (float) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'comissao_get_total_pendentes_falha_db', "PDOException: " . $e->getMessage());
            error_log("Erro ao calcular total de comissões pendentes: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Calcula o total de comissões pagas em um período.
     * @param string $data_inicio Formato YYYY-MM-DD HH:MM:SS
     * @param string $data_fim Formato YYYY-MM-DD HH:MM:SS
     * @param string|null $vendedor_id Opcional para filtrar por vendedor.
     * @return float
     */
    public function getTotalComissoesPagasNoPeriodo($data_inicio, $data_fim, $vendedor_id = null) {
        try {
            $sql = "SELECT SUM(valor_comissao) FROM comissoes_vendedores
                    WHERE status = 'paga' AND data_pagamento_comissao BETWEEN :data_inicio AND :data_fim";
            $params = [':data_inicio' => $data_inicio, ':data_fim' => $data_fim];
            if ($vendedor_id) {
                $sql .= " AND vendedor_id = :vendedor_id";
                $params[':vendedor_id'] = $vendedor_id;
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return (float) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'comissao_get_total_pagas_periodo_falha_db', "PDOException: " . $e->getMessage());
            error_log("Erro ao calcular total de comissões pagas no período: " . $e->getMessage());
            return 0.0;
        }
    }
}
?>
