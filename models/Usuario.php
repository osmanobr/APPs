<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php'; // Para generate_uuid() e hash_password()

class Usuario {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Cria um novo usuário.
     *
     * @param array $dados Dados do usuário: nome, email, senha (raw), nivel_acesso.
     * @return string|false O UUID do usuário criado ou false em caso de falha.
     */
    public function create($dados) {
        // Verificar se o email já existe
        if ($this->getByEmail($dados['email'])) {
            $_SESSION['error_message'] = "O email '{$dados['email']}' já está cadastrado.";
            add_log('aviso', 'usuario_criar_falha_email_duplicado', "Tentativa de criar usuário com email duplicado: {$dados['email']}.", get_logged_in_user_id());
            return false;
        }

        $uuid = generate_uuid();
        $senha_hash = hash_password($dados['senha']); // Faz o hash da senha

        try {
            $sql = "INSERT INTO usuarios (id, nome, email, senha_hash, nivel_acesso)
                    VALUES (:id, :nome, :email, :senha_hash, :nivel_acesso)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':email', $dados['email']);
            $stmt->bindParam(':senha_hash', $senha_hash);
            $stmt->bindParam(':nivel_acesso', $dados['nivel_acesso']);

            if ($stmt->execute()) {
                add_log('info', 'usuario_criado', "Usuário '{$dados['nome']}' (Email: {$dados['email']}, Nível: {$dados['nivel_acesso']}) criado com ID: {$uuid}.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'usuario_criar_falha_db', "PDOException ao criar usuário '{$dados['nome']}': " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um usuário pelo email.
     * @param string $email
     * @return array|false
     */
    public function getByEmail($email) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Não logar erro aqui pois é usado para checagem
            error_log("Erro ao buscar usuário por email {$email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um usuário pelo ID.
     * @param string $id UUID do usuário.
     * @return array|false
     */
    public function getById($id) {
        try {
            $sql = "SELECT id, nome, email, nivel_acesso, data_criacao, data_modificacao FROM usuarios WHERE id = :id"; // Não retorna senha_hash
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'usuario_buscar_id_falha_db', "PDOException ao buscar usuário ID {$id}: " . $e->getMessage());
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os usuários com filtros.
     * @param array $filtros Ex: ['nivel_acesso' => 'vendedor']
     * @return array Lista de usuários.
     */
    public function getAll($filtros = []) {
        try {
            $sql = "SELECT id, nome, email, nivel_acesso, data_criacao FROM usuarios";
            $where_clauses = [];
            $params = [];

            if (!empty($filtros['nivel_acesso'])) {
                if (is_array($filtros['nivel_acesso'])) {
                    $placeholders = implode(',', array_fill(0, count($filtros['nivel_acesso']), '?'));
                    $where_clauses[] = "nivel_acesso IN ({$placeholders})";
                    $params = array_merge($params, $filtros['nivel_acesso']);
                } else {
                    $where_clauses[] = "nivel_acesso = ?";
                    $params[] = $filtros['nivel_acesso'];
                }
            }
            // Adicionar mais filtros se necessário

            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(" AND ", $where_clauses);
            }
            $sql .= " ORDER BY nome ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'usuario_listar_falha_db', "PDOException ao listar usuários: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar todos os usuários: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca usuários por nível de acesso.
     * @param array|string $niveis Nível ou array de níveis.
     * @return array
     */
    public function getByNivelAcesso($niveis) {
        if (!is_array($niveis)) {
            $niveis = [$niveis];
        }
        return $this->getAll(['nivel_acesso' => $niveis]);
    }


    /**
     * Atualiza os dados de um usuário.
     *
     * @param string $id UUID do usuário.
     * @param array $dados Dados a atualizar: nome, email, nivel_acesso.
     *                     Opcional: senha (raw, será hasheada se fornecida).
     * @return bool True em sucesso, false em falha.
     */
    public function update($id, $dados) {
        // Verificar se o novo email (se fornecido e diferente do atual) já existe para outro usuário
        if (isset($dados['email'])) {
            $usuario_atual = $this->getById($id);
            if ($usuario_atual && $usuario_atual['email'] !== $dados['email']) {
                if ($this->getByEmail($dados['email'])) {
                    $_SESSION['error_message'] = "O email '{$dados['email']}' já está cadastrado para outro usuário.";
                    add_log('aviso', 'usuario_update_falha_email_duplicado', "Tentativa de atualizar usuário ID {$id} com email duplicado: {$dados['email']}.", get_logged_in_user_id());
                    return false;
                }
            }
        }

        try {
            $campos_para_atualizar = [];
            if (isset($dados['nome'])) $campos_para_atualizar['nome'] = $dados['nome'];
            if (isset($dados['email'])) $campos_para_atualizar['email'] = $dados['email'];
            if (isset($dados['nivel_acesso'])) $campos_para_atualizar['nivel_acesso'] = $dados['nivel_acesso'];

            // Se uma nova senha for fornecida, faz o hash dela
            if (!empty($dados['senha'])) {
                $campos_para_atualizar['senha_hash'] = hash_password($dados['senha']);
            }

            if (empty($campos_para_atualizar)) {
                return true; // Nada a atualizar
            }

            $sql_parts = [];
            foreach (array_keys($campos_para_atualizar) as $campo) {
                $sql_parts[] = "{$campo} = :{$campo}";
            }
            $sql = "UPDATE usuarios SET " . implode(', ', $sql_parts) . ", data_modificacao = CURRENT_TIMESTAMP WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            foreach ($campos_para_atualizar as $campo => $valor) {
                $stmt->bindValue(":$campo", $valor);
            }

            $success = $stmt->execute();
            if ($success) {
                add_log('info', 'usuario_atualizado', "Usuário ID {$id} atualizado.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'usuario_atualizar_falha_db', "PDOException ao atualizar usuário ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta um usuário.
     * IMPORTANTE: Considerar o que acontece com entidades relacionadas a este usuário (ex: eventos organizados, apartamentos de vendedor).
     * O schema atual usa ON DELETE SET NULL para organizador_id em eventos e vendedor_id em apartamentos.
     *
     * @param string $id UUID do usuário.
     * @return bool True em sucesso, false em falha.
     */
    public function delete($id) {
        // Não permitir excluir o próprio usuário logado
        if ($id === get_logged_in_user_id()) {
            $_SESSION['error_message'] = "Você não pode excluir seu próprio usuário.";
            return false;
        }
        // Poderia adicionar uma verificação para não excluir o último admin, mas isso é mais complexo.

        try {
            $usuario = $this->getById($id); // Para log
            $nomeUsuario = $usuario ? $usuario['nome'] : "ID ".$id;

            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();

            if ($success && $stmt->rowCount() > 0) {
                add_log('info', 'usuario_deletado', "Usuário '{$nomeUsuario}' (ID: {$id}) deletado.", get_logged_in_user_id());
                return true;
            } elseif ($success && $stmt->rowCount() == 0) {
                 add_log('aviso', 'usuario_deletar_nao_encontrado', "Tentativa de deletar usuário ID {$id}, mas não foi encontrado.", get_logged_in_user_id());
                return false;
            }
            return false;
        } catch (PDOException $e) {
             // Se houver FKs com ON DELETE RESTRICT que não foram tratadas, pode dar erro aqui.
            add_log('erro', 'usuario_deletar_falha_db', "PDOException ao deletar usuário ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao deletar usuário: " . $e->getMessage());
            $_SESSION['error_message'] = "Erro ao excluir o usuário. Verifique se ele não está associado a outras partes do sistema.";
            return false;
        }
    }

    /**
     * Retorna os níveis de acesso permitidos.
     * @return array
     */
    public static function getNiveisAcessoPermitidos() {
        return ['admin', 'vendedor', 'funcionario', 'valet'];
    }

}
?>
