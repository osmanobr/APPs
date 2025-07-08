<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php'; // Para generate_uuid e add_log

class Hotel {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Cria um novo hotel.
     *
     * @param array $dados Dados do hotel (nome, endereco).
     * @return string|false O UUID do hotel criado em caso de sucesso, false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO hoteis (id, nome, endereco) VALUES (:id, :nome, :endereco)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':endereco', $dados['endereco']);

            if ($stmt->execute()) {
                add_log('info', 'hotel_criado', "Hotel '{$dados['nome']}' (ID: {$uuid}) criado.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'hotel_criar_falha_db', "PDOException ao criar hotel '{$dados['nome']}': " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar hotel: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os hotéis.
     *
     * @return array Lista de hotéis.
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM hoteis ORDER BY nome ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'hotel_listar_falha_db', "PDOException ao listar hoteis: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar todos os hotéis: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um hotel pelo ID.
     *
     * @param string $id UUID do hotel.
     * @return array|false Dados do hotel ou false se não encontrado.
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM hoteis WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'hotel_buscar_id_falha_db', "PDOException ao buscar hotel ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar hotel por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um hotel.
     *
     * @param string $id UUID do hotel a ser atualizado.
     * @param array $dados Novos dados do hotel.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function update($id, $dados) {
        try {
            $sql = "UPDATE hoteis SET
                    nome = :nome,
                    endereco = :endereco,
                    data_modificacao = CURRENT_TIMESTAMP
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':endereco', $dados['endereco']);

            $success = $stmt->execute();
            if ($success) {
                add_log('info', 'hotel_atualizado', "Hotel '{$dados['nome']}' (ID: {$id}) atualizado.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'hotel_atualizar_falha_db', "PDOException ao atualizar hotel ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao atualizar hotel: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta um hotel.
     *
     * @param string $id UUID do hotel a ser deletado.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function delete($id) {
        try {
            $hotel = $this->getById($id);
            $nomeHotel = $hotel ? $hotel['nome'] : "ID ".$id;

            $sql = "DELETE FROM hoteis WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();

            if ($success && $stmt->rowCount() > 0) {
                add_log('info', 'hotel_deletado', "Hotel '{$nomeHotel}' (ID: {$id}) deletado.", get_logged_in_user_id());
                return true;
            } elseif ($success && $stmt->rowCount() == 0) {
                add_log('aviso', 'hotel_deletar_nao_encontrado', "Tentativa de deletar hotel ID {$id}, mas não foi encontrado.", get_logged_in_user_id());
                return false;
            }
            return false;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Integrity constraint violation
                 add_log('erro', 'hotel_deletar_falha_fk', "PDOException: Tentativa de deletar hotel '{$nomeHotel}' (ID: {$id}) que possui dependências (ex: apartamentos). " . $e->getMessage(), get_logged_in_user_id());
                 error_log("Erro ao deletar hotel (FK constraint): " . $e->getMessage());
                 $_SESSION['error_message'] = "Não é possível excluir este hotel pois ele possui apartamentos ou outras dependências associadas.";
            } else {
                add_log('erro', 'hotel_deletar_falha_db', "PDOException ao deletar hotel ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
                error_log("Erro ao deletar hotel: " . $e->getMessage());
                $_SESSION['error_message'] = "Erro ao excluir o hotel.";
            }
            return false;
        }
    }
}
?>
