<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Inquilino.php';

// Proteção: Administradores e Vendedores podem gerenciar inquilinos
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
if (!is_logged_in() || !in_array(get_user_access_level(), ['admin', 'vendedor'])) {
    $_SESSION['error_message'] = "Acesso negado. Você não tem permissão para gerenciar inquilinos.";
    // Redirecionar para o dashboard do usuário ou login se não tiver nível adequado
    $dashboard_url = APP_URL . (get_user_access_level() === 'admin' ? '/admin/dashboard.php' : '/login.php');
    redirect($dashboard_url);
}

$action = $_REQUEST['action'] ?? null;
$inquilinoModel = new Inquilino();

try {
    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['nome'])) {
                    $_SESSION['error_message'] = "O nome do inquilino é obrigatório.";
                    // $_SESSION['form_data_inquilino'] = $_POST;
                    redirect(APP_URL . '/admin/inquilino_criar.php');
                    exit;
                }
                // Validação de e-mail (opcional, mas bom ter)
                if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error_message'] = "Formato de e-mail inválido.";
                    // $_SESSION['form_data_inquilino'] = $_POST;
                    redirect(APP_URL . '/admin/inquilino_criar.php');
                    exit;
                }

                $dados = [
                    'nome' => trim($_POST['nome']),
                    'email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
                    'telefone' => trim($_POST['telefone'] ?? ''),
                    'documento' => trim($_POST['documento'] ?? '')
                ];

                if ($inquilinoModel->create($dados)) {
                    $_SESSION['success_message'] = "Inquilino '" . escape_html($dados['nome']) . "' criado com sucesso!";
                    redirect(APP_URL . '/admin/inquilinos_listar.php');
                } else {
                    // A mensagem de erro específica (ex: email duplicado) é definida no Model
                    if (!isset($_SESSION['error_message'])) {
                         $_SESSION['error_message'] = "Erro ao criar o inquilino.";
                    }
                    // $_SESSION['form_data_inquilino'] = $_POST;
                    redirect(APP_URL . '/admin/inquilino_criar.php');
                }
            } else {
                redirect(APP_URL . '/admin/inquilino_criar.php');
            }
            break;

        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                if (empty($id) || empty($_POST['nome'])) {
                    $_SESSION['error_message'] = "ID e nome do inquilino são obrigatórios para edição.";
                    redirect(APP_URL . ( $id ? '/admin/inquilino_editar.php?id=' . $id : '/admin/inquilinos_listar.php'));
                    exit;
                }
                if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error_message'] = "Formato de e-mail inválido.";
                    redirect(APP_URL . '/admin/inquilino_editar.php?id=' . $id);
                    exit;
                }

                $dados = [
                    'nome' => trim($_POST['nome']),
                    'email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
                    'telefone' => trim($_POST['telefone'] ?? ''),
                    'documento' => trim($_POST['documento'] ?? '')
                ];

                if ($inquilinoModel->update($id, $dados)) {
                    $_SESSION['success_message'] = "Inquilino '" . escape_html($dados['nome']) . "' atualizado com sucesso!";
                    redirect(APP_URL . '/admin/inquilinos_listar.php');
                } else {
                     if (!isset($_SESSION['error_message'])) {
                        $_SESSION['error_message'] = "Erro ao atualizar o inquilino.";
                     }
                    redirect(APP_URL . '/admin/inquilino_editar.php?id=' . $id);
                }
            } else {
                $id = $_GET['id'] ?? null;
                if ($id) {
                    redirect(APP_URL . '/admin/inquilino_editar.php?id=' . $id);
                } else {
                    $_SESSION['error_message'] = "ID do inquilino não especificado para edição.";
                    redirect(APP_URL . '/admin/inquilinos_listar.php');
                }
            }
            break;

        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm_delete'])) {
                $id = $_POST['id'] ?? $_GET['id'] ?? null;
                if (!$id) {
                    $_SESSION['error_message'] = "ID do inquilino não fornecido para exclusão.";
                    redirect(APP_URL . '/admin/inquilinos_listar.php');
                    exit;
                }
                $inquilino = $inquilinoModel->getById($id);
                if (!$inquilino) {
                    $_SESSION['error_message'] = "Inquilino não encontrado para exclusão.";
                    redirect(APP_URL . '/admin/inquilinos_listar.php');
                    exit;
                }

                if ($inquilinoModel->delete($id)) {
                    if (!isset($_SESSION['error_message'])) {
                        $_SESSION['success_message'] = "Inquilino '" . escape_html($inquilino['nome']) . "' excluído com sucesso!";
                    }
                } else {
                    if (!isset($_SESSION['error_message'])) {
                        $_SESSION['error_message'] = "Erro ao excluir o inquilino '" . escape_html($inquilino['nome']) . "'.";
                    }
                }
                redirect(APP_URL . '/admin/inquilinos_listar.php');
            } else {
                $_SESSION['error_message'] = "Ação de exclusão inválida.";
                redirect(APP_URL . '/admin/inquilinos_listar.php');
            }
            break;

        default:
            redirect(APP_URL . '/admin/inquilinos_listar.php');
            break;
    }
} catch (Exception $e) {
    add_log('erro', 'InquilinoController_Exception', $e->getMessage(), get_logged_in_user_id());
    $_SESSION['error_message'] = "Ocorreu um erro inesperado no sistema de inquilinos.";
    redirect(APP_URL . '/admin/inquilinos_listar.php');
}
?>
