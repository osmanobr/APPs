<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php'; // Para generate_uuid e add_log

class Apartamento {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Cria um novo apartamento.
     *
     * @param array $dados Dados do apartamento.
     * @return string|false O UUID do apartamento criado em caso de sucesso, false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO apartamentos (id, hotel_id, numero_piso, numero_apartamento, tipo_acomodacao, valor_diaria, vendedor_id, responsavel_id)
                    VALUES (:id, :hotel_id, :numero_piso, :numero_apartamento, :tipo_acomodacao, :valor_diaria, :vendedor_id, :responsavel_id)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':hotel_id', $dados['hotel_id']);
            $stmt->bindParam(':numero_piso', $dados['numero_piso']);
            $stmt->bindParam(':numero_apartamento', $dados['numero_apartamento']);
            $stmt->bindParam(':tipo_acomodacao', $dados['tipo_acomodacao']);
            $stmt->bindParam(':valor_diaria', $dados['valor_diaria']);
            $stmt->bindParam(':vendedor_id', $dados['vendedor_id']); // Pode ser NULL
            $stmt->bindParam(':responsavel_id', $dados['responsavel_id']); // Pode ser NULL

            if ($stmt->execute()) {
                add_log('info', 'apartamento_criado', "Apartamento '{$dados['numero_apartamento']}' no hotel ID '{$dados['hotel_id']}' (ID: {$uuid}) criado.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'apartamento_criar_falha_db', "PDOException ao criar apartamento: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar apartamento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os apartamentos com informações adicionais (nome do hotel, vendedor, responsável).
     *
     * @return array Lista de apartamentos.
     */
    public function getAllWithDetails() {
        try {
            $sql = "SELECT
                        a.*,
                        h.nome as nome_hotel,
                        u_vend.nome as nome_vendedor,
                        u_resp.nome as nome_responsavel
                    FROM apartamentos a
                    JOIN hoteis h ON a.hotel_id = h.id
                    LEFT JOIN usuarios u_vend ON a.vendedor_id = u_vend.id
                    LEFT JOIN usuarios u_resp ON a.responsavel_id = u_resp.id
                    ORDER BY h.nome ASC, a.numero_apartamento ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'apartamento_listar_falha_db', "PDOException ao listar apartamentos: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar todos os apartamentos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um apartamento pelo ID.
     *
     * @param string $id UUID do apartamento.
     * @return array|false Dados do apartamento ou false se não encontrado.
     */
    public function getById($id) {
        try {
            $sql = "SELECT a.*, h.nome as nome_hotel
                    FROM apartamentos a
                    JOIN hoteis h ON a.hotel_id = h.id
                    WHERE a.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'apartamento_buscar_id_falha_db', "PDOException ao buscar apartamento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao buscar apartamento por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um apartamento.
     *
     * @param string $id UUID do apartamento a ser atualizado.
     * @param array $dados Novos dados do apartamento.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function update($id, $dados) {
        try {
            $sql = "UPDATE apartamentos SET
                    hotel_id = :hotel_id,
                    numero_piso = :numero_piso,
                    numero_apartamento = :numero_apartamento,
                    tipo_acomodacao = :tipo_acomodacao,
                    valor_diaria = :valor_diaria,
                    vendedor_id = :vendedor_id,
                    responsavel_id = :responsavel_id,
                    data_modificacao = CURRENT_TIMESTAMP
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':hotel_id', $dados['hotel_id']);
            $stmt->bindParam(':numero_piso', $dados['numero_piso']);
            $stmt->bindParam(':numero_apartamento', $dados['numero_apartamento']);
            $stmt->bindParam(':tipo_acomodacao', $dados['tipo_acomodacao']);
            $stmt->bindParam(':valor_diaria', $dados['valor_diaria']);
            $stmt->bindParam(':vendedor_id', $dados['vendedor_id']);
            $stmt->bindParam(':responsavel_id', $dados['responsavel_id']);

            $success = $stmt->execute();
            if ($success) {
                add_log('info', 'apartamento_atualizado', "Apartamento ID {$id} atualizado.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'apartamento_atualizar_falha_db', "PDOException ao atualizar apartamento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao atualizar apartamento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta um apartamento.
     *
     * @param string $id UUID do apartamento a ser deletado.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function delete($id) {
        try {
            $apt = $this->getById($id);
            $aptIdentifier = $apt ? "Apt {$apt['numero_apartamento']} (Hotel: {$apt['nome_hotel']})" : "ID ".$id;

            $sql = "DELETE FROM apartamentos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();

            if ($success && $stmt->rowCount() > 0) {
                add_log('info', 'apartamento_deletado', "Apartamento '{$aptIdentifier}' (ID: {$id}) deletado.", get_logged_in_user_id());
                return true;
            } elseif ($success && $stmt->rowCount() == 0) {
                 add_log('aviso', 'apartamento_deletar_nao_encontrado', "Tentativa de deletar apartamento ID {$id}, mas não foi encontrado.", get_logged_in_user_id());
                return false;
            }
            return false;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Integrity constraint violation
                 add_log('erro', 'apartamento_deletar_falha_fk', "PDOException: Tentativa de deletar apartamento '{$aptIdentifier}' (ID: {$id}) que possui dependências (ex: reservas). " . $e->getMessage(), get_logged_in_user_id());
                 error_log("Erro ao deletar apartamento (FK constraint): " . $e->getMessage());
                 $_SESSION['error_message'] = "Não é possível excluir este apartamento pois ele possui reservas ou outras dependências associadas.";
            } else {
                add_log('erro', 'apartamento_deletar_falha_db', "PDOException ao deletar apartamento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
                error_log("Erro ao deletar apartamento: " . $e->getMessage());
                $_SESSION['error_message'] = "Erro ao excluir o apartamento.";
            }
            return false;
        }
    }

    /**
     * Busca todos os hotéis para preencher selects.
     * @return array
     */
    public function getAllHoteisSimple() {
        try {
            $stmt = $this->pdo->query("SELECT id, nome FROM hoteis ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar hotéis para select: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca todos os usuários que podem ser vendedores ou responsáveis.
     * (Ex: admin, vendedor. Pode ser ajustado para incluir 'inquilino' como responsável se necessário)
     * @return array
     */
    public function getPotenciaisUsuarios() {
         try {
            // Ajustar os níveis conforme a regra de negócio para quem pode ser vendedor/responsável
            $stmt = $this->pdo->query("SELECT id, nome, nivel_acesso FROM usuarios WHERE nivel_acesso IN ('admin', 'vendedor', 'funcionario') ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuários para select: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Conta o total de apartamentos cadastrados.
     * @return int
     */
    public function countTotalApartamentos() {
        try {
            $sql = "SELECT COUNT(id) FROM apartamentos";
            $stmt = $this->pdo->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'apartamento_count_total_falha_db', "PDOException ao contar apartamentos: " . $e->getMessage());
            error_log("Erro ao contar total de apartamentos: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Retorna a contagem de apartamentos agrupados por hotel.
     * @return array Ex: [['nome_hotel' => 'Hotel A', 'total_apartamentos' => 10], ...]
     */
    public function getApartamentosCountByHotel() {
        try {
            $sql = "SELECT h.nome as nome_hotel, COUNT(a.id) as total_apartamentos
                    FROM apartamentos a
                    JOIN hoteis h ON a.hotel_id = h.id
                    GROUP BY h.id, h.nome
                    ORDER BY h.nome ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'apartamento_count_por_hotel_falha_db', "PDOException: " . $e->getMessage());
            error_log("Erro ao contar apartamentos por hotel: " . $e->getMessage());
            return [];
        }
    }
}
?>
