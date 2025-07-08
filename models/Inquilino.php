<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php'; // Para generate_uuid e add_log

class Inquilino {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Cria um novo inquilino.
     *
     * @param array $dados Dados do inquilino (nome, email, telefone, documento).
     * @return string|false O UUID do inquilino criado em caso de sucesso, false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO inquilinos (id, nome, email, telefone, documento)
                    VALUES (:id, :nome, :email, :telefone, :documento)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':email', $dados['email']); // Pode ser NULL se não obrigatório
            $stmt->bindParam(':telefone', $dados['telefone']); // Pode ser NULL
            $stmt->bindParam(':documento', $dados['documento']); // Pode ser NULL

            if ($stmt->execute()) {
                add_log('info', 'inquilino_criado', "Inquilino '{$dados['nome']}' (ID: {$uuid}) criado.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            // Verificar erro de email duplicado (se email for UNIQUE e não NULL)
            if ($e->getCode() == '23000' && strpos($e->getMessage(), 'email') !== false) {
                 add_log('erro', 'inquilino_criar_falha_email_duplicado', "PDOException: Email '{$dados['email']}' já existe. " . $e->getMessage(), get_logged_in_user_id());
                 $_SESSION['error_message'] = "O email '{$dados['email']}' já está cadastrado para outro inquilino.";
            } else {
                add_log('erro', 'inquilino_criar_falha_db', "PDOException ao criar inquilino '{$dados['nome']}': " . $e->getMessage(), get_logged_in_user_id());
                error_log("Erro ao criar inquilino: " . $e->getMessage());
                 if (!isset($_SESSION['error_message'])) $_SESSION['error_message'] = "Erro ao criar o inquilino.";
            }
            return false;
        }
    }

    /**
     * Busca todos os inquilinos.
     *
     * @return array Lista de inquilinos.
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM inquilinos ORDER BY nome ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'inquilino_listar_falha_db', "PDOException ao listar inquilinos: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar todos os inquilinos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um inquilino pelo ID.
     *
     * @param string $id UUID do inquilino.
     * @return array|false Dados do inquilino ou false se não encontrado.
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM inquilinos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'inquilino_buscar_id_falha_db', "PDOException ao buscar inquilino ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar inquilino por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um inquilino.
     *
     * @param string $id UUID do inquilino a ser atualizado.
     * @param array $dados Novos dados do inquilino.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function update($id, $dados) {
        try {
            $sql = "UPDATE inquilinos SET
                    nome = :nome,
                    email = :email,
                    telefone = :telefone,
                    documento = :documento,
                    data_modificacao = CURRENT_TIMESTAMP
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':email', $dados['email']);
            $stmt->bindParam(':telefone', $dados['telefone']);
            $stmt->bindParam(':documento', $dados['documento']);

            $success = $stmt->execute();
            if ($success) {
                add_log('info', 'inquilino_atualizado', "Inquilino '{$dados['nome']}' (ID: {$id}) atualizado.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
             if ($e->getCode() == '23000' && strpos($e->getMessage(), 'email') !== false) {
                 add_log('erro', 'inquilino_atualizar_falha_email_duplicado', "PDOException: Email '{$dados['email']}' já existe. " . $e->getMessage(), get_logged_in_user_id());
                 $_SESSION['error_message'] = "O email '{$dados['email']}' já está cadastrado para outro inquilino.";
            } else {
                add_log('erro', 'inquilino_atualizar_falha_db', "PDOException ao atualizar inquilino ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
                error_log("Erro ao atualizar inquilino: " . $e->getMessage());
                if (!isset($_SESSION['error_message'])) $_SESSION['error_message'] = "Erro ao atualizar o inquilino.";
            }
            return false;
        }
    }

    /**
     * Deleta um inquilino.
     *
     * @param string $id UUID do inquilino a ser deletado.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function delete($id) {
        try {
            $inquilino = $this->getById($id);
            $nomeInquilino = $inquilino ? $inquilino['nome'] : "ID ".$id;

            $sql = "DELETE FROM inquilinos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();

            if ($success && $stmt->rowCount() > 0) {
                add_log('info', 'inquilino_deletado', "Inquilino '{$nomeInquilino}' (ID: {$id}) deletado.", get_logged_in_user_id());
                return true;
            } elseif ($success && $stmt->rowCount() == 0) {
                 add_log('aviso', 'inquilino_deletar_nao_encontrado', "Tentativa de deletar inquilino ID {$id}, mas não foi encontrado.", get_logged_in_user_id());
                return false;
            }
            return false;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Integrity constraint violation
                 add_log('erro', 'inquilino_deletar_falha_fk', "PDOException: Tentativa de deletar inquilino '{$nomeInquilino}' (ID: {$id}) que possui dependências (ex: reservas). " . $e->getMessage(), get_logged_in_user_id());
                 error_log("Erro ao deletar inquilino (FK constraint): " . $e->getMessage());
                 $_SESSION['error_message'] = "Não é possível excluir este inquilino pois ele possui reservas ou outras dependências associadas.";
            } else {
                add_log('erro', 'inquilino_deletar_falha_db', "PDOException ao deletar inquilino ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
                error_log("Erro ao deletar inquilino: " . $e->getMessage());
                if (!isset($_SESSION['error_message'])) $_SESSION['error_message'] = "Erro ao excluir o inquilino.";
            }
            return false;
        }
    }
}
?>
