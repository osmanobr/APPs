<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/Pagamento.php'; // Para criar o pagamento associado

class Reserva {
    private $pdo;
    private $pagamentoModel;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
        $this->pagamentoModel = new Pagamento();
    }

    /**
     * Cria uma nova reserva e um pagamento pendente associado.
     *
     * @param array $dados Dados da reserva: evento_id, apartamento_id, inquilino_id,
     *                     data_checkin, data_checkout, valor_total.
     *                     Opcional: status_pagamento (default 'pendente').
     * @return string|false O UUID da reserva criada ou false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        $this->pdo->beginTransaction(); // Iniciar transação

        try {
            // 1. Criar a reserva
            $sql_reserva = "INSERT INTO reservas (id, evento_id, apartamento_id, inquilino_id, data_checkin, data_checkout, valor_total, status_pagamento)
                            VALUES (:id, :evento_id, :apartamento_id, :inquilino_id, :data_checkin, :data_checkout, :valor_total, :status_pagamento)";
            $stmt_reserva = $this->pdo->prepare($sql_reserva);

            $status_pagamento_reserva = $dados['status_pagamento'] ?? 'pendente';

            $stmt_reserva->bindParam(':id', $uuid);
            $stmt_reserva->bindParam(':evento_id', $dados['evento_id']);
            $stmt_reserva->bindParam(':apartamento_id', $dados['apartamento_id']);
            $stmt_reserva->bindParam(':inquilino_id', $dados['inquilino_id']);
            $stmt_reserva->bindParam(':data_checkin', $dados['data_checkin']);
            $stmt_reserva->bindParam(':data_checkout', $dados['data_checkout']);
            $stmt_reserva->bindParam(':valor_total', $dados['valor_total']);
            $stmt_reserva->bindParam(':status_pagamento', $status_pagamento_reserva);

            if (!$stmt_reserva->execute()) {
                $this->pdo->rollBack();
                add_log('erro', 'reserva_criar_falha_db', "PDOException ao criar reserva (etapa 1).", get_logged_in_user_id());
                error_log("Erro ao criar reserva (etapa 1): " . implode(", ", $stmt_reserva->errorInfo()));
                return false;
            }

            // 2. Criar o pagamento associado
            $dados_pagamento = [
                'origem_tipo' => 'reserva',
                'origem_id' => $uuid,
                'inquilino_id' => $dados['inquilino_id'],
                'valor' => $dados['valor_total'],
                'status' => 'pendente', // Pagamento sempre começa como pendente
                'data_vencimento' => $dados['data_checkin'], // Vencimento pode ser a data do check-in
                'descricao' => "Pagamento referente à reserva ID: {$uuid}"
            ];

            $pagamento_id = $this->pagamentoModel->create($dados_pagamento);
            if (!$pagamento_id) {
                $this->pdo->rollBack();
                // Log já é feito dentro do PagamentoModel->create() em caso de falha
                $_SESSION['error_message'] = $_SESSION['error_message'] ?? "Erro ao criar o registro de pagamento para a reserva.";
                return false;
            }

            $this->pdo->commit(); // Confirmar transação
            add_log('info', 'reserva_criada', "Reserva ID {$uuid} e Pagamento ID {$pagamento_id} criados.", get_logged_in_user_id());
            return $uuid;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            add_log('erro', 'reserva_criar_falha_db_transacao', "PDOException na transação ao criar reserva: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro (transação) ao criar reserva: " . $e->getMessage());
            $_SESSION['error_message'] = "Erro crítico ao processar a reserva e pagamento.";
            return false;
        }
    }

    /**
     * Busca uma reserva pelo ID com detalhes.
     * @param string $id UUID da reserva.
     * @return array|false
     */
    public function getByIdWithDetails($id) {
        try {
            $sql = "SELECT
                        r.*,
                        e.nome as nome_evento,
                        apt.numero_apartamento,
                        apt.tipo_acomodacao as tipo_acomodacao_apt,
                        h.nome as nome_hotel,
                        i.nome as nome_inquilino,
                        i.email as email_inquilino,
                        -- Subconsulta ou JOIN para pegar o status do pagamento mais recente da tabela pagamentos
                        (SELECT p.status FROM pagamentos p WHERE p.origem_id = r.id AND p.origem_tipo = 'reserva' ORDER BY p.criado_em DESC LIMIT 1) as status_pagamento_atual,
                        (SELECT p.id FROM pagamentos p WHERE p.origem_id = r.id AND p.origem_tipo = 'reserva' ORDER BY p.criado_em DESC LIMIT 1) as pagamento_principal_id
                    FROM reservas r
                    JOIN eventos e ON r.evento_id = e.id
                    JOIN apartamentos apt ON r.apartamento_id = apt.id
                    JOIN hoteis h ON apt.hotel_id = h.id
                    JOIN inquilinos i ON r.inquilino_id = i.id
                    WHERE r.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

            // Se o status_pagamento_atual não foi encontrado na subquery (nenhum pagamento associado), usa o da reserva
            if ($reserva && $reserva['status_pagamento_atual'] === null) {
                $reserva['status_pagamento_atual'] = $reserva['status_pagamento'];
            }
            return $reserva;

        } catch (PDOException $e) {
            add_log('erro', 'reserva_buscar_id_detalhes_falha_db', "PDOException ao buscar reserva ID {$id} com detalhes: " . $e->getMessage());
            error_log("Erro ao buscar reserva por ID com detalhes: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todas as reservas com detalhes.
     * @return array
     */
    public function getAllWithDetails() {
        try {
             $sql = "SELECT
                        r.id, r.data_checkin, r.data_checkout, r.valor_total, r.status_pagamento as status_pagamento_reserva_tabela,
                        e.nome as nome_evento,
                        apt.numero_apartamento,
                        h.nome as nome_hotel,
                        i.nome as nome_inquilino,
                        (SELECT p.status FROM pagamentos p WHERE p.origem_id = r.id AND p.origem_tipo = 'reserva' ORDER BY p.criado_em DESC LIMIT 1) as status_pagamento_atual
                    FROM reservas r
                    JOIN eventos e ON r.evento_id = e.id
                    JOIN apartamentos apt ON r.apartamento_id = apt.id
                    JOIN hoteis h ON apt.hotel_id = h.id
                    JOIN inquilinos i ON r.inquilino_id = i.id
                    ORDER BY r.data_criacao DESC";
            $stmt = $this->pdo->query($sql);
            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($reservas as $key => $reserva) {
                 if ($reserva['status_pagamento_atual'] === null) {
                    $reservas[$key]['status_pagamento_atual'] = $reserva['status_pagamento_reserva_tabela'];
                }
            }
            return $reservas;

        } catch (PDOException $e) {
            add_log('erro', 'reserva_listar_detalhes_falha_db', "PDOException ao listar reservas com detalhes: " . $e->getMessage());
            error_log("Erro ao listar reservas com detalhes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza o status de pagamento de uma reserva.
     * Chamado quando um pagamento associado é confirmado/cancelado.
     *
     * @param string $reserva_id UUID da reserva.
     * @param string $novo_status_pagamento Novo status ('pago', 'pendente', 'cancelado', 'parcial').
     * @return bool
     */
    public function updateStatusPagamento($reserva_id, $novo_status_pagamento) {
        try {
            $sql = "UPDATE reservas SET status_pagamento = :status_pagamento, data_modificacao = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':status_pagamento', $novo_status_pagamento);
            $stmt->bindParam(':id', $reserva_id);
            $success = $stmt->execute();
            if ($success) {
                 add_log('info', 'reserva_status_pag_atualizado', "Status de pagamento da Reserva ID {$reserva_id} atualizado para '{$novo_status_pagamento}'.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'reserva_update_status_pag_falha_db', "PDOException ao atualizar status de pagamento da Reserva ID {$reserva_id}: " . $e->getMessage());
            error_log("Erro ao atualizar status de pagamento da reserva: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza os dados de uma reserva.
     * ATENÇÃO: A lógica de atualizar o pagamento associado pode ser complexa.
     * Se o valor_total muda, o pagamento original pode precisar ser cancelado e um novo criado,
     * ou ajustado se o gateway permitir.
     * Inicialmente, focaremos em atualizar dados que não afetam diretamente o valor já registrado no pagamento.
     *
     * @param string $id UUID da reserva.
     * @param array $dados Novos dados.
     * @return bool
     */
    public function update($id, $dados) {
        // Implementação simplificada: atualiza campos da reserva.
        // Lógica mais complexa para recalcular valor e atualizar pagamento será adicionada se necessário.
        $this->pdo->beginTransaction();
        try {
            $reserva_original = $this->getByIdWithDetails($id);
            if (!$reserva_original) {
                $this->pdo->rollBack();
                return false;
            }

            $sql = "UPDATE reservas SET
                        evento_id = :evento_id,
                        apartamento_id = :apartamento_id,
                        inquilino_id = :inquilino_id,
                        data_checkin = :data_checkin,
                        data_checkout = :data_checkout,
                        valor_total = :valor_total,
                        -- status_pagamento = :status_pagamento, -- Status do pagamento é gerenciado separadamente
                        data_modificacao = CURRENT_TIMESTAMP
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':evento_id', $dados['evento_id']);
            $stmt->bindParam(':apartamento_id', $dados['apartamento_id']);
            $stmt->bindParam(':inquilino_id', $dados['inquilino_id']);
            $stmt->bindParam(':data_checkin', $dados['data_checkin']);
            $stmt->bindParam(':data_checkout', $dados['data_checkout']);
            $stmt->bindParam(':valor_total', $dados['valor_total']);
            // $stmt->bindParam(':status_pagamento', $dados['status_pagamento']);

            if (!$stmt->execute()) {
                $this->pdo->rollBack();
                error_log("Erro ao atualizar reserva (etapa 1): " . implode(", ", $stmt->errorInfo()));
                return false;
            }

            // Se o valor_total mudou, precisamos tratar o pagamento associado.
            // Estratégia: Se o pagamento principal estiver 'pendente', atualiza o valor.
            // Se estiver 'pago' ou 'parcial', a lógica é mais complexa (reembolso parcial, cobrança adicional, etc.)
            // Por simplicidade, se valor mudou e pagamento está pendente, atualizamos o pagamento.
            if (isset($dados['valor_total']) && $reserva_original['valor_total'] != $dados['valor_total'] && $reserva_original['pagamento_principal_id']) {
                $pagamento_principal = $this->pagamentoModel->getById($reserva_original['pagamento_principal_id']);
                if ($pagamento_principal && $pagamento_principal['status'] === 'pendente') {
                    // Atualizar o valor do pagamento pendente
                    $sql_update_pag = "UPDATE pagamentos SET valor = :valor WHERE id = :id";
                    $stmt_update_pag = $this->pdo->prepare($sql_update_pag);
                    $stmt_update_pag->bindParam(':valor', $dados['valor_total']);
                    $stmt_update_pag->bindParam(':id', $reserva_original['pagamento_principal_id']);
                    if (!$stmt_update_pag->execute()) {
                         $this->pdo->rollBack();
                         error_log("Erro ao atualizar valor do pagamento da reserva: " . implode(", ", $stmt_update_pag->errorInfo()));
                         $_SESSION['error_message'] = "Reserva atualizada, mas falha ao ajustar o valor do pagamento pendente associado.";
                         // Não retorna false aqui, pois a reserva foi atualizada, mas com aviso.
                    }
                } elseif ($pagamento_principal && $pagamento_principal['status'] !== 'pendente') {
                    // Pagamento já processado e valor da reserva mudou. Requer ação manual ou lógica mais complexa.
                    $_SESSION['warning_message'] = "Reserva atualizada, mas o valor total foi alterado e o pagamento associado não está mais pendente. Verifique manualmente.";
                    add_log('aviso', 'reserva_valor_alterado_pag_processado', "Valor da Reserva ID {$id} alterado, mas pagamento ID {$reserva_original['pagamento_principal_id']} ({$pagamento_principal['status']}) já processado. Requer atenção.", get_logged_in_user_id());
                }
            }

            $this->pdo->commit();
            add_log('info', 'reserva_atualizada', "Reserva ID {$id} atualizada.", get_logged_in_user_id());
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            add_log('erro', 'reserva_atualizar_falha_db', "PDOException ao atualizar reserva ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao atualizar reserva: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Cancela uma reserva e o pagamento pendente associado.
     * @param string $id UUID da reserva.
     * @return bool
     */
    public function cancel($id) {
        $this->pdo->beginTransaction();
        try {
            // 1. Marcar reserva como cancelada
            $sql_reserva = "UPDATE reservas SET status_pagamento = 'cancelado', data_modificacao = CURRENT_TIMESTAMP WHERE id = :id";
            // Poderíamos ter um campo status_reserva ENUM('confirmada', 'pendente_confirmacao', 'cancelada', 'concluida')
            // e status_pagamento ser apenas para o financeiro. Por ora, usamos status_pagamento.
            $stmt_reserva = $this->pdo->prepare($sql_reserva);
            $stmt_reserva->bindParam(':id', $id);
            if (!$stmt_reserva->execute()) {
                $this->pdo->rollBack();
                return false;
            }

            // 2. Marcar pagamentos pendentes associados como cancelados
            $pagamentos = $this->pagamentoModel->getByOrigem('reserva', $id);
            foreach ($pagamentos as $pag) {
                if ($pag['status'] === 'pendente' || $pag['status'] === 'parcial') { // Cancelar pendentes ou parciais
                    if (!$this->pagamentoModel->updatePaymentDetails($pag['id'], 'cancelado')) {
                        // Não necessariamente rollbacka tudo, mas loga o erro.
                        // A reserva principal foi cancelada.
                        add_log('erro', 'reserva_cancelar_falha_pagamento', "Reserva ID {$id} cancelada, mas falha ao cancelar Pagamento ID {$pag['id']}.", get_logged_in_user_id());
                    }
                }
                // Se um pagamento já estiver 'pago', o cancelamento da reserva pode implicar um reembolso (lógica futura).
            }

            $this->pdo->commit();
            add_log('info', 'reserva_cancelada', "Reserva ID {$id} cancelada.", get_logged_in_user_id());
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            add_log('erro', 'reserva_cancelar_falha_db', "PDOException ao cancelar reserva ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao cancelar reserva: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Busca apartamentos disponíveis para um período e tipo de acomodação.
     * @param string $data_inicio Data/hora de início.
     * @param string $data_fim Data/hora de fim.
     * @param string|null $tipo_acomodacao Tipo de acomodação (opcional).
     * @param string|null $hotel_id Hotel específico (opcional).
     * @param string|null $ignore_reserva_id ID de uma reserva a ser ignorada na verificação (útil ao editar uma reserva).
     * @return array Lista de apartamentos disponíveis.
     */
    public function getApartamentosDisponiveis($data_inicio, $data_fim, $tipo_acomodacao = null, $hotel_id = null, $ignore_reserva_id = null) {
        try {
            $sql = "SELECT a.*, h.nome as nome_hotel
                    FROM apartamentos a
                    JOIN hoteis h ON a.hotel_id = h.id
                    WHERE a.id NOT IN (
                        SELECT r.apartamento_id
                        FROM reservas r
                        WHERE r.status_pagamento != 'cancelado'
                          AND (
                                (r.data_checkin < :data_fim AND r.data_checkout > :data_inicio) -- Sobreposição
                          )
                          AND (:ignore_reserva_id IS NULL OR r.id != :ignore_reserva_id)
                    )";

            if ($tipo_acomodacao) {
                $sql .= " AND a.tipo_acomodacao = :tipo_acomodacao";
            }
            if ($hotel_id) {
                $sql .= " AND a.hotel_id = :hotel_id";
            }
            $sql .= " ORDER BY h.nome, a.numero_apartamento";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':data_inicio', $data_inicio);
            $stmt->bindParam(':data_fim', $data_fim);
            $stmt->bindParam(':ignore_reserva_id', $ignore_reserva_id);

            if ($tipo_acomodacao) {
                $stmt->bindParam(':tipo_acomodacao', $tipo_acomodacao);
            }
            if ($hotel_id) {
                $stmt->bindParam(':hotel_id', $hotel_id);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            add_log('erro', 'reserva_buscar_apt_disp_falha_db', "PDOException ao buscar apartamentos disponíveis: " . $e->getMessage());
            error_log("Erro ao buscar apartamentos disponíveis: " . $e->getMessage());
            return [];
        }
    }

    // Outros métodos (ex: para buscar eventos, inquilinos para selects nos formulários)
     public function getAllEventosAtivos() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome FROM eventos WHERE data_fim >= CURDATE() ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    public function getAllInquilinos() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome, email FROM inquilinos ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    //  public function getAllApartamentosSimple() { // Para selects, pode ser filtrado depois
    //     try {
    //         $stmt = $this->pdo->query("SELECT a.id, a.numero_apartamento, a.tipo_acomodacao, h.nome as nome_hotel FROM apartamentos a JOIN hoteis h ON a.hotel_id = h.id ORDER BY h.nome, a.numero_apartamento ASC");
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) { return []; }
    // }

    /**
     * Conta o número de reservas com pagamento pendente ou parcial.
     * Este método considera o status_pagamento da própria tabela reservas.
     * @return int
     */
    public function countPendentesDePagamento() {
        try {
            $sql = "SELECT COUNT(id)
                    FROM reservas
                    WHERE status_pagamento IN ('pendente', 'parcial')";

            $stmt = $this->pdo->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'reserva_count_pendentes_falha_db', "PDOException ao contar reservas pendentes: " . $e->getMessage());
            error_log("Erro ao contar reservas pendentes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Conta o número de reservas ativas para eventos futuros.
     * Uma reserva é considerada ativa se não estiver cancelada.
     * Um evento é futuro se sua data de fim for maior ou igual a hoje.
     * @return int
     */
    public function countReservasAtivasEventosFuturos() {
        try {
            $sql = "SELECT COUNT(r.id)
                    FROM reservas r
                    JOIN eventos e ON r.evento_id = e.id
                    WHERE r.status_pagamento != 'cancelado'
                      AND e.data_fim >= CURDATE()";
            $stmt = $this->pdo->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'reserva_count_ativas_futuras_falha_db', "PDOException: " . $e->getMessage());
            error_log("Erro ao contar reservas ativas para eventos futuros: " . $e->getMessage());
            return 0;
        }
    }
}
?>
