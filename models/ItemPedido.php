<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';
// require_once __DIR__ . '/Pedido.php'; // Para chamar recalcularTotal do PedidoModel

class ItemPedido {
    private $pdo;
    // private $pedidoModel; // Descomentar se for chamar PedidoModel->recalcularTotal daqui

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
        // $this->pedidoModel = new Pedido(); // Descomentar se for chamar PedidoModel->recalcularTotal daqui
    }

    /**
     * Adiciona um item a um pedido.
     * Após adicionar, o total do pedido principal deve ser recalculado.
     *
     * @param array $dados Dados do item: pedido_id, nome_item, quantidade, preco_unitario, produto_id (opcional)
     * @return string|false O UUID do item de pedido criado ou false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO itens_pedido (id, pedido_id, produto_id, nome_item, quantidade, preco_unitario, criado_em)
                    VALUES (:id, :pedido_id, :produto_id, :nome_item, :quantidade, :preco_unitario, CURRENT_TIMESTAMP)";
            $stmt = $this->pdo->prepare($sql);

            $produto_id = $dados['produto_id'] ?? null;

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':pedido_id', $dados['pedido_id']);
            $stmt->bindParam(':produto_id', $produto_id);
            $stmt->bindParam(':nome_item', $dados['nome_item']);
            $stmt->bindParam(':quantidade', $dados['quantidade'], PDO::PARAM_INT);
            $stmt->bindParam(':preco_unitario', $dados['preco_unitario']);

            if ($stmt->execute()) {
                // Após criar o item, recalcular o total do pedido pai.
                // Essa responsabilidade pode ser do controller ou do PedidoModel.
                // Se for aqui: $this->pedidoModel->recalcularTotal($dados['pedido_id']);
                // Por enquanto, vamos deixar o controller chamar o PedidoModel->recalcularTotal.
                add_log('info', 'item_pedido_criado', "Item '{$dados['nome_item']}' adicionado ao Pedido ID {$dados['pedido_id']}.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'item_pedido_criar_falha_db', "PDOException ao criar item de pedido: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar item de pedido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os itens de um pedido específico.
     * @param string $pedido_id UUID do pedido.
     * @return array Lista de itens do pedido.
     */
    public function getByPedidoId($pedido_id) {
        try {
            $sql = "SELECT * FROM itens_pedido WHERE pedido_id = :pedido_id ORDER BY criado_em ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':pedido_id', $pedido_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'item_pedido_listar_falha_db', "PDOException ao listar itens do Pedido ID {$pedido_id}: " . $e->getMessage());
            error_log("Erro ao buscar itens por pedido ID: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um item de pedido específico pelo seu ID.
     * @param string $item_pedido_id UUID do item do pedido.
     * @return array|false
     */
    public function getById($item_pedido_id) {
        try {
            $sql = "SELECT * FROM itens_pedido WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $item_pedido_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'item_pedido_buscar_id_falha_db', "PDOException ao buscar ItemPedido ID {$item_pedido_id}: " . $e->getMessage());
            error_log("Erro ao buscar ItemPedido por ID: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Atualiza um item de pedido (ex: quantidade).
     * Após atualizar, o total do pedido principal deve ser recalculado.
     *
     * @param string $item_id UUID do item a ser atualizado.
     * @param array $dados Novos dados do item (quantidade, preco_unitario, nome_item).
     * @return bool True em sucesso, false em falha.
     */
    public function update($item_id, $dados) {
        try {
            // Primeiro, pegar o pedido_id para recalcular o total depois, se necessário
            $item_original = $this->getById($item_id);
            if (!$item_original) return false;
            $pedido_id = $item_original['pedido_id'];

            $sql = "UPDATE itens_pedido SET
                        nome_item = :nome_item,
                        quantidade = :quantidade,
                        preco_unitario = :preco_unitario,
                        produto_id = :produto_id
                        -- criado_em não é atualizado, subtotal é gerado
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $produto_id = $dados['produto_id'] ?? $item_original['produto_id']; // Mantém se não fornecido

            $stmt->bindParam(':id', $item_id);
            $stmt->bindParam(':nome_item', $dados['nome_item']);
            $stmt->bindParam(':quantidade', $dados['quantidade'], PDO::PARAM_INT);
            $stmt->bindParam(':preco_unitario', $dados['preco_unitario']);
            $stmt->bindParam(':produto_id', $produto_id);

            $success = $stmt->execute();
            if ($success) {
                // Lógica para recalcular total do pedido pai será feita no controller ou PedidoModel
                add_log('info', 'item_pedido_atualizado', "Item ID {$item_id} do Pedido ID {$pedido_id} atualizado.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'item_pedido_atualizar_falha_db', "PDOException ao atualizar ItemPedido ID {$item_id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao atualizar item de pedido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove um item de um pedido.
     * Após remover, o total do pedido principal deve ser recalculado.
     *
     * @param string $item_id UUID do item a ser removido.
     * @return bool True em sucesso, false em falha.
     */
    public function delete($item_id) {
        try {
            // Pegar pedido_id para recalcular total depois
            $item_original = $this->getById($item_id);
            if (!$item_original) return false; // Item não existe
            // $pedido_id = $item_original['pedido_id']; // O controller vai precisar disso

            $sql = "DELETE FROM itens_pedido WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $item_id);
            $success = $stmt->execute();

            if ($success && $stmt->rowCount() > 0) {
                // Lógica para recalcular total do pedido pai será feita no controller ou PedidoModel
                 add_log('info', 'item_pedido_deletado', "Item ID {$item_id} (Nome: {$item_original['nome_item']}) do Pedido ID {$item_original['pedido_id']} deletado.", get_logged_in_user_id());
                return true;
            }
            return false; // Não deletou (ex: ID não encontrado) ou falhou
        } catch (PDOException $e) {
            add_log('erro', 'item_pedido_deletar_falha_db', "PDOException ao deletar ItemPedido ID {$item_id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao deletar item de pedido: " . $e->getMessage());
            return false;
        }
    }

    // Se houver uma tabela de produtos/serviços cadastrados:
    /*
    public function getProdutosServicosDisponiveis() {
        try {
            $sql = "SELECT id, nome, preco_padrao FROM produtos_servicos WHERE ativo = 1 ORDER BY nome ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar produtos/serviços: " . $e->getMessage());
            return [];
        }
    }
    */

}
?>
