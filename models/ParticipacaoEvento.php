<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';

class ParticipacaoEvento {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Registra a participação (check-in) de um inquilino em um evento.
     *
     * @param array $dados Dados da participação: evento_id, inquilino_id,
     *                     tipo_participacao (opcional), comprovante_checkin (opcional), observacoes (opcional).
     * @return string|false O UUID da participação criada ou false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        try {
            // Verificar se já existe uma participação para este inquilino neste evento
            $sql_check = "SELECT id FROM participacoes_evento WHERE evento_id = :evento_id AND inquilino_id = :inquilino_id";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->bindParam(':evento_id', $dados['evento_id']);
            $stmt_check->bindParam(':inquilino_id', $dados['inquilino_id']);
            $stmt_check->execute();
            if ($stmt_check->fetch()) {
                $_SESSION['error_message'] = "Este inquilino já possui um registro de participação neste evento.";
                add_log('aviso', 'participacao_evento_duplicada', "Tentativa de registrar participação duplicada para Inquilino ID {$dados['inquilino_id']} no Evento ID {$dados['evento_id']}.", get_logged_in_user_id());
                return false;
            }

            $sql = "INSERT INTO participacoes_evento (id, evento_id, inquilino_id, data_checkin_evento, tipo_participacao, comprovante_checkin, observacoes)
                    VALUES (:id, :evento_id, :inquilino_id, CURRENT_TIMESTAMP, :tipo_participacao, :comprovante_checkin, :observacoes)";
            $stmt = $this->pdo->prepare($sql);

            $tipo_participacao = $dados['tipo_participacao'] ?? null;
            $comprovante_checkin = $dados['comprovante_checkin'] ?? null;
            $observacoes = $dados['observacoes'] ?? null;

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':evento_id', $dados['evento_id']);
            $stmt->bindParam(':inquilino_id', $dados['inquilino_id']);
            $stmt->bindParam(':tipo_participacao', $tipo_participacao);
            $stmt->bindParam(':comprovante_checkin', $comprovante_checkin);
            $stmt->bindParam(':observacoes', $observacoes);

            if ($stmt->execute()) {
                add_log('info', 'participacao_evento_criada', "Participação (Check-in) ID {$uuid} criada para Inquilino ID {$dados['inquilino_id']} no Evento ID {$dados['evento_id']}.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'participacao_evento_criar_falha_db', "PDOException ao criar participação em evento: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar participação em evento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca uma participação pelo ID.
     * @param string $id UUID da participação.
     * @return array|false
     */
    public function getById($id) {
        try {
            $sql = "SELECT pe.*, e.nome as nome_evento, i.nome as nome_inquilino, i.email as email_inquilino
                    FROM participacoes_evento pe
                    JOIN eventos e ON pe.evento_id = e.id
                    JOIN inquilinos i ON pe.inquilino_id = i.id
                    WHERE pe.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'participacao_evento_buscar_id_falha_db', "PDOException ao buscar ParticipacaoEvento ID {$id}: " . $e->getMessage());
            error_log("Erro ao buscar ParticipacaoEvento por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todas as participações de um evento específico.
     * @param string $evento_id UUID do evento.
     * @return array
     */
    public function getByEventoIdWithDetails($evento_id) {
        try {
            $sql = "SELECT
                        pe.id, pe.data_checkin_evento, pe.tipo_participacao, pe.comprovante_checkin, pe.observacoes,
                        i.nome as nome_inquilino, i.email as email_inquilino, i.documento as documento_inquilino,
                        e.nome as nome_evento
                    FROM participacoes_evento pe
                    JOIN inquilinos i ON pe.inquilino_id = i.id
                    JOIN eventos e ON pe.evento_id = e.id
                    WHERE pe.evento_id = :evento_id
                    ORDER BY i.nome ASC, pe.data_checkin_evento ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':evento_id', $evento_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'participacao_evento_listar_por_evento_falha_db', "PDOException ao listar participações do Evento ID {$evento_id}: " . $e->getMessage());
            error_log("Erro ao listar participações por evento: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lista todas as participações (geral, com filtros).
     * @param array $filtros (ex: evento_id, inquilino_id)
     * @return array
     */
    public function getAllWithDetails($filtros = []) {
        try {
            $sql = "SELECT
                        pe.id, pe.data_checkin_evento, pe.tipo_participacao, pe.comprovante_checkin,
                        i.nome as nome_inquilino, i.email as email_inquilino,
                        e.nome as nome_evento
                    FROM participacoes_evento pe
                    JOIN inquilinos i ON pe.inquilino_id = i.id
                    JOIN eventos e ON pe.evento_id = e.id";

            $where_clauses = [];
            $params = [];

            if (!empty($filtros['evento_id'])) {
                $where_clauses[] = "pe.evento_id = :evento_id";
                $params[':evento_id'] = $filtros['evento_id'];
            }
            if (!empty($filtros['inquilino_id'])) {
                $where_clauses[] = "pe.inquilino_id = :inquilino_id";
                $params[':inquilino_id'] = $filtros['inquilino_id'];
            }
            // Adicionar mais filtros se necessário

            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(" AND ", $where_clauses);
            }
            $sql .= " ORDER BY pe.data_checkin_evento DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'participacao_evento_listar_geral_falha_db', "PDOException ao listar participações: " . $e->getMessage());
            error_log("Erro ao listar participações: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Deleta um registro de participação.
     * @param string $id UUID da participação.
     * @return bool
     */
    public function delete($id) {
        try {
            $participacao = $this->getById($id); // Para log
            $nomeInquilino = $participacao ? $participacao['nome_inquilino'] : 'N/A';
            $nomeEvento = $participacao ? $participacao['nome_evento'] : 'N/A';

            $sql = "DELETE FROM participacoes_evento WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();

            if ($success && $stmt->rowCount() > 0) {
                add_log('info', 'participacao_evento_deletada', "Participação de '{$nomeInquilino}' no evento '{$nomeEvento}' (ID: {$id}) deletada.", get_logged_in_user_id());
                return true;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'participacao_evento_deletar_falha_db', "PDOException ao deletar ParticipacaoEvento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao deletar ParticipacaoEvento: " . $e->getMessage());
            return false;
        }
    }

    // Métodos para buscar eventos e inquilinos para formulários
    public function getAllEventosAtivosSimple() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome FROM eventos WHERE data_fim >= CURDATE() ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    public function getAllInquilinosSimple() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome, email, documento FROM inquilinos ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

}
?>
