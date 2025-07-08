<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';

class HistoricoResponsavelReserva {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Adiciona uma nova entrada ao histórico de responsáveis pela reserva.
     *
     * @param array $dados Dados da entrada:
     *                     reserva_id (CHAR(36) - FK para reservas),
     *                     responsavel_id (CHAR(36) - FK para usuarios ou inquilinos, dependendo da regra),
     *                     tipo_responsavel (ENUM 'inquilino', 'usuario_sistema'),
     *                     alterado_por_id (CHAR(36) - FK para usuarios, quem fez a alteração),
     *                     motivo_alteracao (VARCHAR - opcional)
     * @return string|false O UUID da entrada de histórico criada ou false em caso de falha.
     */
    public function addEntry($dados) {
        $uuid = generate_uuid();
        try {
            // Antes de adicionar uma nova entrada, podemos "encerrar" a anterior para esta reserva, se houver.
            // No schema atual, não temos data_fim no histórico, então cada entrada é um ponto no tempo.
            // Se a regra fosse ter apenas um responsável ativo por vez, precisaríamos de data_fim ou um status 'ativo'.
            // Por ora, apenas adicionamos uma nova entrada para cada mudança.

            $sql = "INSERT INTO historico_responsaveis_reserva (id, reserva_id, responsavel_id, tipo_responsavel, data_alteracao, alterado_por_id, motivo_alteracao)
                    VALUES (:id, :reserva_id, :responsavel_id, :tipo_responsavel, CURRENT_TIMESTAMP, :alterado_por_id, :motivo_alteracao)";
            $stmt = $this->pdo->prepare($sql);

            $motivo = $dados['motivo_alteracao'] ?? null;
            // Determinar a FK correta para responsavel_id baseado em tipo_responsavel
            // O schema atual tem `responsavel_id` referenciando `usuarios(id)`.
            // Se `tipo_responsavel` for 'inquilino', o `responsavel_id` deve ser o ID de um inquilino.
            // Isso implica que a FK em `historico_responsaveis_reserva.responsavel_id` deveria ser mais flexível
            // ou ter duas colunas (responsavel_usuario_id, responsavel_inquilino_id).
            // Por simplicidade e consistência com o schema atual (que tem FK para usuarios),
            // vamos assumir que se o tipo_responsavel é 'inquilino', o ID do inquilino é passado,
            // mas a FK no banco ainda é para usuarios. Isso é uma limitação do schema atual se quisermos integridade referencial estrita para ambos.
            // Uma solução seria o responsavel_id na tabela de histórico ser NULLABLE e ter duas colunas separadas:
            // responsavel_usuario_id e responsavel_inquilino_id.
            // Ou, o `responsavel_id` na tabela `apartamentos` e `reservas` também seria o ID de um `inquilino` se essa for a regra.
            // Mantendo a estrutura atual da FK para usuarios:
            // Se o responsável for um inquilino, o $dados['responsavel_id'] é o ID do inquilino, mas a FK não vai validar contra a tabela inquilinos.
            // Isso precisa ser tratado na lógica da aplicação ou o schema ajustado.
            // Para este exemplo, vou assumir que o ID fornecido é o correto para o tipo especificado,
            // e a FK no banco é para `usuarios` (o que significa que se tipo_responsavel='inquilino',
            // o `responsavel_id` pode não ter uma contraparte direta na tabela `usuarios` a menos que haja uma regra de negócio).
            // Vamos assumir que o ID do inquilino é passado para responsavel_id, e a FK no banco é para usuarios.
            // No schema atualizado, `historico_responsaveis_reserva.responsavel_id` -> `usuarios(id)`.
            // Se um inquilino assume, o `responsavel_id` DEVE ser o ID de um usuário que REPRESENTA esse inquilino,
            // ou o inquilino também deve ser um usuário.
            // Se `inquilino_id` da reserva for usado como `responsavel_id` e `tipo_responsavel` for 'inquilino',
            // a FK para `usuarios` não se aplicaria diretamente a menos que o inquilino também seja um usuário.
            // Vou manter a FK para usuarios e assumir que, se o tipo é 'inquilino', o ID é de um usuário que representa o inquilino,
            // ou o `responsavel_id` na tabela `reservas` e `apartamentos` aponta para `inquilinos.id` (o que não é o caso no schema atual).

            // Simplificação para o momento: Assumir que responsavel_id é sempre um ID de usuário válido,
            // e 'tipo_responsavel' é apenas informativo sobre o papel daquele usuário.
            // Se o responsável for um inquilino que NÃO é um usuário, o schema atual precisa de ajuste.
            // Vamos seguir o schema onde responsavel_id é FK de usuarios.

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':reserva_id', $dados['reserva_id']);
            $stmt->bindParam(':responsavel_id', $dados['responsavel_id']); // Este é o ID do USUÁRIO responsável
            $stmt->bindParam(':tipo_responsavel', $dados['tipo_responsavel']); // Informativo
            $stmt->bindParam(':alterado_por_id', $dados['alterado_por_id']);
            $stmt->bindParam(':motivo_alteracao', $motivo);

            if ($stmt->execute()) {
                // Log já é feito pelo controller que chama este método
                // add_log('info', 'hist_resp_reserva_add', "Histórico de responsável adicionado para Reserva ID {$dados['reserva_id']}.", $dados['alterado_por_id']);
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'hist_resp_reserva_add_falha_db', "PDOException ao adicionar histórico de responsável para Reserva ID {$dados['reserva_id']}: " . $e->getMessage(), $dados['alterado_por_id'] ?? null);
            error_log("Erro ao adicionar histórico de responsável de reserva: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca o histórico de responsáveis para uma reserva específica.
     * @param string $reserva_id
     * @return array
     */
    public function getByReservaId($reserva_id) {
        try {
            $sql = "SELECT
                        hr.*,
                        u_resp.nome as nome_responsavel,
                        u_resp.email as email_responsavel,
                        u_alter.nome as nome_alterado_por
                    FROM historico_responsaveis_reserva hr
                    JOIN usuarios u_resp ON hr.responsavel_id = u_resp.id -- Assumindo que responsavel_id é sempre um usuário
                    LEFT JOIN usuarios u_alter ON hr.alterado_por_id = u_alter.id
                    WHERE hr.reserva_id = :reserva_id
                    ORDER BY hr.data_alteracao DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':reserva_id', $reserva_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'hist_resp_reserva_get_falha_db', "PDOException ao buscar histórico de resp. para Reserva ID {$reserva_id}: " . $e->getMessage());
            error_log("Erro ao buscar histórico de resp. de reserva: " . $e->getMessage());
            return [];
        }
    }
}
?>
