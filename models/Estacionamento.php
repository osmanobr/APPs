<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/Pagamento.php'; // Para criar pagamento associado

class Estacionamento {
    private $pdo;
    private $pagamentoModel;

    // Defina aqui as tarifas ou busque de uma tabela de configuração/hotel
    const TARIFA_CARRO_HORA = 10.00;
    const TARIFA_MOTO_HORA = 5.00;
    const TARIFA_ONIBUS_HORA = 20.00;
    const TARIFA_OUTRO_HORA = 10.00; // Tarifa padrão para 'outro'

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
        $this->pagamentoModel = new Pagamento();
    }

    /**
     * Registra o check-in de um veículo no estacionamento.
     *
     * @param array $dados Dados do check-in: hotel_id, inquilino_id, tipo_veiculo, placa, observacoes (opcional)
     * @return string|false O UUID do registro de estacionamento criado ou false em caso de falha.
     */
    public function checkIn($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO estacionamentos (id, hotel_id, inquilino_id, tipo_veiculo, placa, checkin, status_veiculo, observacoes, criado_em, atualizado_em)
                    VALUES (:id, :hotel_id, :inquilino_id, :tipo_veiculo, :placa, :checkin, :status_veiculo, :observacoes, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $stmt = $this->pdo->prepare($sql);

            $checkin_datetime = date('Y-m-d H:i:s'); // Check-in é agora
            $status_veiculo = 'estacionado';
            $observacoes = $dados['observacoes'] ?? null;

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':hotel_id', $dados['hotel_id']);
            $stmt->bindParam(':inquilino_id', $dados['inquilino_id']);
            $stmt->bindParam(':tipo_veiculo', $dados['tipo_veiculo']);
            $stmt->bindParam(':placa', $dados['placa']);
            $stmt->bindParam(':checkin', $checkin_datetime);
            $stmt->bindParam(':status_veiculo', $status_veiculo);
            $stmt->bindParam(':observacoes', $observacoes);

            if ($stmt->execute()) {
                add_log('info', 'estacionamento_checkin', "Check-in veículo placa {$dados['placa']} no Hotel ID {$dados['hotel_id']}. Estacionamento ID: {$uuid}.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'estacionamento_checkin_falha_db', "PDOException no check-in do veículo placa {$dados['placa']}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro no check-in de estacionamento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registra o check-out de um veículo e cria um pagamento pendente se houver valor.
     *
     * @param string $estacionamento_id UUID do registro de estacionamento.
     * @param string|null $observacoes_checkout Observações adicionais para o checkout.
     * @return array|false ['success' => bool, 'message' => string, 'pagamento_id' => string|null, 'valor_cobrado' => float|null] ou false em erro grave.
     */
    public function checkOut($estacionamento_id, $observacoes_checkout = null) {
        $this->pdo->beginTransaction();
        try {
            $estacionamento = $this->getById($estacionamento_id);
            if (!$estacionamento || $estacionamento['status_veiculo'] !== 'estacionado') {
                $this->pdo->rollBack();
                $msg = $estacionamento ? "Veículo não está com status 'estacionado'." : "Registro de estacionamento não encontrado.";
                $_SESSION['error_message'] = $msg;
                return ['success' => false, 'message' => $msg];
            }

            $checkout_datetime = date('Y-m-d H:i:s');
            $checkin_datetime = new DateTime($estacionamento['checkin']);
            $checkout_dt_obj = new DateTime($checkout_datetime);

            $intervalo = $checkin_datetime->diff($checkout_dt_obj);
            $horas_estacionado = $intervalo->h + ($intervalo->days * 24);
            if ($intervalo->i > 10) $horas_estacionado++; // Se passou de 10min, cobra hora cheia
            if ($horas_estacionado == 0) $horas_estacionado = 1; // Mínimo 1 hora

            $tarifa_hora = 0;
            switch ($estacionamento['tipo_veiculo']) {
                case 'carro': $tarifa_hora = self::TARIFA_CARRO_HORA; break;
                case 'moto': $tarifa_hora = self::TARIFA_MOTO_HORA; break;
                case 'onibus': $tarifa_hora = self::TARIFA_ONIBUS_HORA; break;
                case 'outro': default: $tarifa_hora = self::TARIFA_OUTRO_HORA; break;
            }
            $valor_cobrado = $horas_estacionado * $tarifa_hora;

            $pagamento_id_gerado = null;
            if ($valor_cobrado > 0) {
                $dados_pagamento = [
                    'origem_tipo' => 'estacionamento',
                    'origem_id' => $estacionamento_id,
                    'inquilino_id' => $estacionamento['inquilino_id'],
                    'valor' => $valor_cobrado,
                    'status' => 'pendente',
                    'descricao' => "Pagamento Estacionamento: Veículo {$estacionamento['placa']}, Período: {$horas_estacionado}h"
                ];
                $pagamento_id_gerado = $this->pagamentoModel->create($dados_pagamento);
                if (!$pagamento_id_gerado) {
                    $this->pdo->rollBack();
                    $_SESSION['error_message'] = $_SESSION['error_message'] ?? "Erro ao criar registro de pagamento para o estacionamento.";
                    return ['success' => false, 'message' => $_SESSION['error_message']];
                }
            }

            // Atualizar registro de estacionamento
            $sql_update = "UPDATE estacionamentos
                           SET checkout = :checkout,
                               status_veiculo = :status_veiculo,
                               pagamento_id = :pagamento_id,
                               observacoes = COALESCE(CONCAT(observacoes, CHAR(13), CHAR(10), :observacoes_checkout), :observacoes_checkout_single),
                               atualizado_em = CURRENT_TIMESTAMP
                           WHERE id = :id";
            $stmt_update = $this->pdo->prepare($sql_update);

            $novo_status_veiculo = 'saiu';
            $obs_checkout_single = $observacoes_checkout;

            $stmt_update->bindParam(':checkout', $checkout_datetime);
            $stmt_update->bindParam(':status_veiculo', $novo_status_veiculo);
            $stmt_update->bindParam(':pagamento_id', $pagamento_id_gerado);
            $stmt_update->bindParam(':observacoes_checkout', $observacoes_checkout);
            $stmt_update->bindParam(':observacoes_checkout_single', $obs_checkout_single);
            $stmt_update->bindParam(':id', $estacionamento_id);

            if (!$stmt_update->execute()) {
                $this->pdo->rollBack();
                add_log('erro', 'estacionamento_checkout_falha_db_update', "PDOException no checkout do Estacionamento ID {$estacionamento_id} (update).", get_logged_in_user_id());
                $_SESSION['error_message'] = "Erro ao atualizar o registro de estacionamento no checkout.";
                return ['success' => false, 'message' => "Erro ao atualizar o registro de estacionamento no checkout."];
            }

            $this->pdo->commit();
            add_log('info', 'estacionamento_checkout', "Check-out veículo placa {$estacionamento['placa']}. Estacionamento ID: {$estacionamento_id}. Valor: {$valor_cobrado}. Pagamento ID: {$pagamento_id_gerado}", get_logged_in_user_id());
            return [
                'success' => true,
                'message' => "Check-out realizado. Valor cobrado: R$ " . number_format($valor_cobrado, 2, ',', '.') . ".",
                'pagamento_id' => $pagamento_id_gerado,
                'valor_cobrado' => $valor_cobrado
            ];

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            add_log('erro', 'estacionamento_checkout_falha_db_transacao', "PDOException na transação de checkout do Estacionamento ID {$estacionamento_id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro (transação) no checkout de estacionamento: " . $e->getMessage());
            $_SESSION['error_message'] = "Erro crítico ao processar o check-out do estacionamento.";
            return false;
        }
    }

    public function getById($id) {
        try {
            $sql = "SELECT e.*, h.nome as nome_hotel, i.nome as nome_inquilino
                    FROM estacionamentos e
                    JOIN hoteis h ON e.hotel_id = h.id
                    JOIN inquilinos i ON e.inquilino_id = i.id
                    WHERE e.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'estacionamento_buscar_id_falha_db', "PDOException ao buscar estacionamento ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function getAllWithDetails($filtros = []) {
        try {
            $sql = "SELECT
                        est.*,
                        h.nome as nome_hotel,
                        i.nome as nome_inquilino,
                        p.status as status_pagamento,
                        p.valor as valor_pago,
                        p.forma_pagamento as forma_pgto_registrada
                    FROM estacionamentos est
                    JOIN hoteis h ON est.hotel_id = h.id
                    JOIN inquilinos i ON est.inquilino_id = i.id
                    LEFT JOIN pagamentos p ON est.pagamento_id = p.id";

            $where_clauses = [];
            $params = [];

            if (!empty($filtros['hotel_id'])) {
                $where_clauses[] = "est.hotel_id = :hotel_id";
                $params[':hotel_id'] = $filtros['hotel_id'];
            }
            if (!empty($filtros['status_veiculo'])) {
                $where_clauses[] = "est.status_veiculo = :status_veiculo";
                $params[':status_veiculo'] = $filtros['status_veiculo'];
            }
             if (!empty($filtros['placa'])) {
                $where_clauses[] = "est.placa LIKE :placa";
                $params[':placa'] = '%' . $filtros['placa'] . '%';
            }

            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(" AND ", $where_clauses);
            }
            $sql .= " ORDER BY est.checkin DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'estacionamento_listar_falha_db', "PDOException ao listar estacionamentos: " . $e->getMessage());
            return [];
        }
    }

    public function getVeiculosEstacionados($hotel_id = null) {
        $filtros = ['status_veiculo' => 'estacionado'];
        if ($hotel_id) {
            $filtros['hotel_id'] = $hotel_id;
        }
        return $this->getAllWithDetails($filtros);
    }

    public function getAllHoteisSimple() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome FROM hoteis ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    public function getAllInquilinosSimple() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome, documento FROM inquilinos ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    /**
     * Conta o número de veículos atualmente estacionados.
     * @param string|null $hotel_id Opcional para filtrar por hotel.
     * @return int
     */
    public function countVeiculosEstacionadosAtualmente($hotel_id = null) {
        try {
            $sql = "SELECT COUNT(id) FROM estacionamentos WHERE status_veiculo = 'estacionado'";
            $params = [];
            if ($hotel_id) {
                $sql .= " AND hotel_id = :hotel_id";
                $params[':hotel_id'] = $hotel_id;
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'estacionamento_count_estacionados_falha_db', "PDOException: " . $e->getMessage());
            error_log("Erro ao contar veículos estacionados: " . $e->getMessage());
            return 0;
        }
    }
}
?>
