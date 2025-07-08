<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php'; // Para generate_uuid

class Evento {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Cria um novo evento.
     *
     * @param array $dados Dados do evento (nome, data_inicio, data_fim, organizador_id, descricao).
     * @return string|false O UUID do evento criado em caso de sucesso, false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO eventos (id, nome, data_inicio, data_fim, organizador_id, descricao)
                    VALUES (:id, :nome, :data_inicio, :data_fim, :organizador_id, :descricao)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':data_inicio', $dados['data_inicio']);
            $stmt->bindParam(':data_fim', $dados['data_fim']);
            $stmt->bindParam(':organizador_id', $dados['organizador_id']); // Pode ser NULL
            $stmt->bindParam(':descricao', $dados['descricao']);

            if ($stmt->execute()) {
                add_log('info', 'evento_criado', "Evento '{$dados['nome']}' (ID: {$uuid}) criado.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'evento_criar_falha_db', "PDOException ao criar evento '{$dados['nome']}': " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar evento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os eventos.
     *
     * @return array Lista de eventos.
     */
    public function getAll() {
        try {
            // Vamos buscar também o nome do organizador se houver
            $sql = "SELECT e.*, u.nome as nome_organizador
                    FROM eventos e
                    LEFT JOIN usuarios u ON e.organizador_id = u.id
                    ORDER BY e.data_inicio DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'evento_listar_falha_db', "PDOException ao listar eventos: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar todos os eventos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um evento pelo ID.
     *
     * @param string $id UUID do evento.
     * @return array|false Dados do evento ou false se não encontrado.
     */
    public function getById($id) {
        try {
            $sql = "SELECT e.*, u.nome as nome_organizador
                    FROM eventos e
                    LEFT JOIN usuarios u ON e.organizador_id = u.id
                    WHERE e.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'evento_buscar_id_falha_db', "PDOException ao buscar evento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar evento por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um evento.
     *
     * @param string $id UUID do evento a ser atualizado.
     * @param array $dados Novos dados do evento.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function update($id, $dados) {
        try {
            $sql = "UPDATE eventos SET
                    nome = :nome,
                    data_inicio = :data_inicio,
                    data_fim = :data_fim,
                    organizador_id = :organizador_id,
                    descricao = :descricao,
                    data_modificacao = CURRENT_TIMESTAMP
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':data_inicio', $dados['data_inicio']);
            $stmt->bindParam(':data_fim', $dados['data_fim']);
            $stmt->bindParam(':organizador_id', $dados['organizador_id']);
            $stmt->bindParam(':descricao', $dados['descricao']);

            $success = $stmt->execute();
            if ($success) {
                add_log('info', 'evento_atualizado', "Evento '{$dados['nome']}' (ID: {$id}) atualizado.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'evento_atualizar_falha_db', "PDOException ao atualizar evento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao atualizar evento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta um evento.
     *
     * @param string $id UUID do evento a ser deletado.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function delete($id) {
        try {
            // Antes de deletar, podemos pegar o nome para o log
            $evento = $this->getById($id);
            $nomeEvento = $evento ? $evento['nome'] : "ID ".$id;

            $sql = "DELETE FROM eventos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();

            if ($success && $stmt->rowCount() > 0) {
                 add_log('info', 'evento_deletado', "Evento '{$nomeEvento}' (ID: {$id}) deletado.", get_logged_in_user_id());
                return true;
            } elseif ($success && $stmt->rowCount() == 0) {
                // Nenhum registro foi deletado, talvez o ID não exista mais
                add_log('aviso', 'evento_deletar_nao_encontrado', "Tentativa de deletar evento ID {$id}, mas não foi encontrado.", get_logged_in_user_id());
                return false;
            }
            return false;
        } catch (PDOException $e) {
            // Verificar se o erro é de restrição de chave estrangeira (ex: evento com reservas)
            if ($e->getCode() == '23000') { // Código SQLSTATE para integrity constraint violation
                 add_log('erro', 'evento_deletar_falha_fk', "PDOException: Tentativa de deletar evento '{$nomeEvento}' (ID: {$id}) que possui dependências (ex: reservas). " . $e->getMessage(), get_logged_in_user_id());
                 error_log("Erro ao deletar evento (FK constraint): " . $e->getMessage());
                 $_SESSION['error_message'] = "Não é possível excluir este evento pois ele possui reservas ou outras dependências associadas.";
            } else {
                add_log('erro', 'evento_deletar_falha_db', "PDOException ao deletar evento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
                error_log("Erro ao deletar evento: " . $e->getMessage());
                $_SESSION['error_message'] = "Erro ao excluir o evento.";
            }
            return false;
        }
    }

    /**
     * Busca todos os usuários que podem ser organizadores (ex: admins, vendedores).
     * Ajuste os níveis conforme necessário.
     *
     * @return array Lista de usuários.
     */
    public function getPotenciaisOrganizadores() {
        try {
            $sql = "SELECT id, nome FROM usuarios WHERE nivel_acesso IN ('admin', 'vendedor') ORDER BY nome ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar potenciais organizadores: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Conta o número de eventos futuros (data de início maior ou igual a hoje).
     * @return int
     */
    public function countEventosFuturos() {
        try {
            $sql = "SELECT COUNT(id) FROM eventos WHERE data_inicio >= CURDATE()";
            $stmt = $this->pdo->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'evento_count_futuros_falha_db', "PDOException ao contar eventos futuros: " . $e->getMessage());
            error_log("Erro ao contar eventos futuros: " . $e->getMessage());
            return 0;
        }
    }
}
?>
