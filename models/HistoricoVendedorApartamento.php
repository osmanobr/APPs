<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';

class HistoricoVendedorApartamento {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Adiciona uma nova entrada ao histórico de vendedores do apartamento.
     *
     * @param array $dados Dados da entrada:
     *                     apartamento_id (CHAR(36) - FK para apartamentos),
     *                     vendedor_id (CHAR(36) - FK para usuarios),
     *                     alterado_por_id (CHAR(36) - FK para usuarios, quem fez a alteração),
     *                     motivo_alteracao (VARCHAR - opcional)
     * @return string|false O UUID da entrada de histórico criada ou false em caso de falha.
     */
    public function addEntry($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO historico_vendedores_apartamento (id, apartamento_id, vendedor_id, data_alteracao, alterado_por_id, motivo_alteracao)
                    VALUES (:id, :apartamento_id, :vendedor_id, CURRENT_TIMESTAMP, :alterado_por_id, :motivo_alteracao)";
            $stmt = $this->pdo->prepare($sql);

            $motivo = $dados['motivo_alteracao'] ?? 'Alteração de vendedor';
            $vendedor_id = $dados['vendedor_id'] ?? null; // Permitir NULL se o vendedor for removido

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':apartamento_id', $dados['apartamento_id']);
            $stmt->bindParam(':vendedor_id', $vendedor_id);
            $stmt->bindParam(':alterado_por_id', $dados['alterado_por_id']);
            $stmt->bindParam(':motivo_alteracao', $motivo);

            if ($stmt->execute()) {
                // Log pode ser feito pelo controller que chama este método
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'hist_vend_apto_add_falha_db', "PDOException ao adicionar histórico de vendedor para Apto ID {$dados['apartamento_id']}: " . $e->getMessage(), $dados['alterado_por_id'] ?? null);
            error_log("Erro ao adicionar histórico de vendedor de apartamento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca o histórico de vendedores para um apartamento específico.
     * @param string $apartamento_id
     * @return array
     */
    public function getByApartamentoId($apartamento_id) {
        try {
            $sql = "SELECT
                        hv.*,
                        u_vend.nome as nome_vendedor,
                        u_vend.email as email_vendedor,
                        u_alter.nome as nome_alterado_por
                    FROM historico_vendedores_apartamento hv
                    LEFT JOIN usuarios u_vend ON hv.vendedor_id = u_vend.id -- Vendedor pode ser NULL se removido
                    LEFT JOIN usuarios u_alter ON hv.alterado_por_id = u_alter.id
                    WHERE hv.apartamento_id = :apartamento_id
                    ORDER BY hv.data_alteracao DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':apartamento_id', $apartamento_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'hist_vend_apto_get_falha_db', "PDOException ao buscar histórico de vendedor para Apto ID {$apartamento_id}: " . $e->getMessage());
            error_log("Erro ao buscar histórico de vendedor de apto: " . $e->getMessage());
            return [];
        }
    }
}
?>
