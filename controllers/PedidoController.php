<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/ItemPedido.php';
// Inquilino e Usuario podem ser necessários para selects em formulários
require_once __DIR__ . '/../models/Inquilino.php';
require_once __DIR__ . '/../models/Usuario.php'; // Supondo que exista um Model Usuario para buscar funcionários

// Proteção: Admin, Funcionário, Vendedor podem gerenciar pedidos
// (Vendedor pode criar pedido para seu inquilino, Funcionário para qualquer um)
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
if (!is_logged_in() || !in_array(get_user_access_level(), ['admin', 'funcionario', 'vendedor'])) {
    $_SESSION['error_message'] = "Acesso negado.";
    redirect(APP_URL . '/login.php');
}

$action = $_REQUEST['action'] ?? null;
$pedidoModel = new Pedido();
$itemPedidoModel = new ItemPedido();

$logged_in_user_id = get_logged_in_user_id();

try {
    switch ($action) {
        case 'create_pedido': // Cria um novo pedido (cabeçalho)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['inquilino_id'])) {
                    $_SESSION['error_message'] = "Inquilino é obrigatório para criar um pedido.";
                    redirect(APP_URL . '/admin/pedidos_listar.php'); // Ou para uma página de "novo pedido"
                    exit;
                }
                $dados_pedido = [
                    'inquilino_id' => $_POST['inquilino_id'],
                    'funcionario_id' => $logged_in_user_id, // Funcionário que está criando
                    'observacoes' => trim($_POST['observacoes'] ?? ''),
                    'forma_pagamento_prevista' => $_POST['forma_pagamento_prevista'] ?? null
                ];
                $pedido_id = $pedidoModel->create($dados_pedido);
                if ($pedido_id) {
                    $_SESSION['success_message'] = "Novo pedido (ID: {$pedido_id}) criado. Adicione itens agora.";
                    redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id); // Redireciona para a página de gerenciamento do pedido
                } else {
                    $_SESSION['error_message'] = "Erro ao criar o novo pedido.";
                    redirect(APP_URL . '/admin/pedidos_listar.php'); // Ou página anterior
                }
            } else {
                 // Se for GET, redireciona para a página de criação de pedido (que pode ser a listagem com um botão "Novo")
                redirect(APP_URL . '/admin/pedido_gerenciar.php'); // Abre a página de gerenciamento/criação
            }
            break;

        case 'add_item':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pedido_id = $_POST['pedido_id'] ?? null;
                $pedido = $pedidoModel->getByIdWithDetails($pedido_id);
                if (!$pedido || $pedido['status'] !== 'aberto') {
                    $_SESSION['error_message'] = "Não é possível adicionar itens a este pedido (ID: {$pedido_id}). Pode estar fechado ou não existir.";
                    redirect(APP_URL . ($pedido_id ? '/admin/pedido_gerenciar.php?id=' . $pedido_id : '/admin/pedidos_listar.php'));
                    exit;
                }

                if (empty($_POST['nome_item']) || !isset($_POST['quantidade']) || !isset($_POST['preco_unitario'])) {
                    $_SESSION['error_message'] = "Nome do item, quantidade e preço unitário são obrigatórios.";
                    redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id);
                    exit;
                }
                $quantidade = filter_var($_POST['quantidade'], FILTER_VALIDATE_INT);
                $preco_unitario = filter_var($_POST['preco_unitario'], FILTER_VALIDATE_FLOAT);

                if ($quantidade === false || $quantidade <= 0 || $preco_unitario === false || $preco_unitario < 0) {
                     $_SESSION['error_message'] = "Quantidade e preço devem ser números válidos e positivos.";
                     redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id);
                     exit;
                }

                $dados_item = [
                    'pedido_id' => $pedido_id,
                    'nome_item' => trim($_POST['nome_item']),
                    'quantidade' => $quantidade,
                    'preco_unitario' => $preco_unitario,
                    'produto_id' => $_POST['produto_id'] ?? null // Se houver seleção de produto
                ];

                if ($itemPedidoModel->create($dados_item)) {
                    $pedidoModel->recalcularTotal($pedido_id); // Recalcular total do pedido
                    $_SESSION['success_message'] = "Item adicionado ao pedido!";
                } else {
                    $_SESSION['error_message'] = "Erro ao adicionar item ao pedido.";
                }
                redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id);
            }
            break;

        case 'delete_item':
            // Geralmente por POST ou GET com confirmação
            $item_id = $_REQUEST['item_id'] ?? null;
            $pedido_id_redirect = $_REQUEST['pedido_id'] ?? null; // Para redirecionar de volta

            if (!$item_id || !$pedido_id_redirect) {
                $_SESSION['error_message'] = "Informações insuficientes para remover o item.";
                redirect(APP_URL . ($pedido_id_redirect ? '/admin/pedido_gerenciar.php?id=' . $pedido_id_redirect : '/admin/pedidos_listar.php'));
                exit;
            }

            $item = $itemPedidoModel->getById($item_id);
            if(!$item || $item['pedido_id'] != $pedido_id_redirect) {
                 $_SESSION['error_message'] = "Item não encontrado ou não pertence a este pedido.";
                 redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id_redirect);
                 exit;
            }

            $pedido = $pedidoModel->getByIdWithDetails($pedido_id_redirect);
             if (!$pedido || $pedido['status'] !== 'aberto') {
                $_SESSION['error_message'] = "Não é possível remover itens deste pedido pois ele não está aberto.";
                redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id_redirect);
                exit;
            }

            if ($itemPedidoModel->delete($item_id)) {
                $pedidoModel->recalcularTotal($pedido_id_redirect);
                $_SESSION['success_message'] = "Item removido do pedido.";
            } else {
                $_SESSION['error_message'] = "Erro ao remover item do pedido.";
            }
            redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id_redirect);
            break;

        case 'fechar_pedido':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pedido_id = $_POST['pedido_id'] ?? null;
                 $forma_pagamento_final = $_POST['forma_pagamento_final'] ?? null;

                if (!$pedido_id) {
                    $_SESSION['error_message'] = "ID do pedido não fornecido para fechar.";
                    redirect(APP_URL . '/admin/pedidos_listar.php');
                    exit;
                }
                $resultado_fechamento = $pedidoModel->fecharPedido($pedido_id, $forma_pagamento_final);
                if ($resultado_fechamento && $resultado_fechamento['success']) {
                    $_SESSION['success_message'] = $resultado_fechamento['message'];
                    // Se um pagamento foi gerado, pode redirecionar para detalhes do pagamento ou do pedido
                    redirect(APP_URL . '/admin/pedidos_listar.php'); // Ou para pedido_gerenciar.php?id=...
                } else {
                    $_SESSION['error_message'] = $_SESSION['error_message'] ?? ($resultado_fechamento['message'] ?? "Erro ao fechar o pedido.");
                     redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id);
                }
            } else {
                 $_SESSION['error_message'] = "Ação inválida para fechar pedido.";
                 redirect(APP_URL . '/admin/pedidos_listar.php');
            }
            break;

        case 'update_pedido_status': // Ex: admin cancela um pedido, ou marca como pago após pagamento externo
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && get_user_access_level() === 'admin') { // Apenas admin pode mudar status arbitrariamente
                $pedido_id = $_POST['pedido_id_status_update'] ?? null;
                $novo_status = $_POST['novo_status_pedido'] ?? null;

                if (!$pedido_id || !$novo_status) {
                    $_SESSION['error_message'] = "ID do pedido e novo status são obrigatórios.";
                } else {
                    if ($pedidoModel->updateStatus($pedido_id, $novo_status)) {
                        $_SESSION['success_message'] = "Status do pedido ID {$pedido_id} atualizado para {$novo_status}.";
                        // Se o status do pedido foi para 'pago', e existe um pagamento pendente, marcar o pagamento como pago.
                        if ($novo_status === 'pago') {
                            $pagamentos_do_pedido = $pagamentoModel->getByOrigem('pedido', $pedido_id);
                            foreach ($pagamentos_do_pedido as $pag) {
                                if ($pag['status'] === 'pendente') {
                                    $pagamentoModel->updatePaymentDetails($pag['id'], 'pago', $_POST['forma_pagamento_confirmada'] ?? null, date('Y-m-d H:i:s'), null, "Pagamento confirmado ao mudar status do pedido para PAGO.");
                                }
                            }
                        }
                    } else {
                        $_SESSION['error_message'] = "Erro ao atualizar status do pedido.";
                    }
                }
            } else {
                 $_SESSION['error_message'] = "Ação inválida ou não permitida.";
            }
             // Redireciona para a página de gerenciamento do pedido se o ID estiver disponível, caso contrário para a listagem
            $redirect_url = $pedido_id ? APP_URL . '/admin/pedido_gerenciar.php?id=' . $pedido_id : APP_URL . '/admin/pedidos_listar.php';
            redirect($redirect_url);
            break;


        default:
            // Se a ação for GET e tiver um ID, provavelmente é para gerenciar/visualizar
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                 redirect(APP_URL . '/admin/pedido_gerenciar.php?id=' . $_GET['id']);
            } else {
                redirect(APP_URL . '/admin/pedidos_listar.php');
            }
            break;
    }
} catch (Exception $e) {
    add_log('erro', 'PedidoController_Exception', $e->getMessage(), $logged_in_user_id);
    $_SESSION['error_message'] = "Ocorreu um erro inesperado no sistema de pedidos: " . $e->getMessage();
    redirect(APP_URL . '/admin/pedidos_listar.php');
}
?>
