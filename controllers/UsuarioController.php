<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Usuario.php';

// Apenas Admin pode gerenciar usuários
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
if (!is_logged_in() || get_user_access_level() !== 'admin') {
    $_SESSION['error_message'] = "Acesso negado. Apenas administradores podem gerenciar usuários.";
    redirect(APP_URL . '/admin/dashboard.php'); // Redireciona para o dashboard se não for admin
}

$action = $_REQUEST['action'] ?? null;
$usuarioModel = new Usuario();
$logged_in_user_id = get_logged_in_user_id();

try {
    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['nivel_acesso'])) {
                    $_SESSION['error_message'] = "Nome, email, senha e nível de acesso são obrigatórios.";
                    // $_SESSION['form_data_usuario'] = $_POST; // Para repopular
                    redirect(APP_URL . '/admin/usuario_criar.php');
                    exit;
                }
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error_message'] = "Formato de email inválido.";
                    // $_SESSION['form_data_usuario'] = $_POST;
                    redirect(APP_URL . '/admin/usuario_criar.php');
                    exit;
                }
                if (strlen($_POST['senha']) < 6) { // Exemplo de validação de senha
                    $_SESSION['error_message'] = "A senha deve ter pelo menos 6 caracteres.";
                     // $_SESSION['form_data_usuario'] = $_POST;
                    redirect(APP_URL . '/admin/usuario_criar.php');
                    exit;
                }
                if (!in_array($_POST['nivel_acesso'], Usuario::getNiveisAcessoPermitidos())) {
                    $_SESSION['error_message'] = "Nível de acesso inválido.";
                    // $_SESSION['form_data_usuario'] = $_POST;
                    redirect(APP_URL . '/admin/usuario_criar.php');
                    exit;
                }

                $dados_usuario = [
                    'nome' => trim($_POST['nome']),
                    'email' => trim($_POST['email']),
                    'senha' => $_POST['senha'], // Senha raw, o model fará o hash
                    'nivel_acesso' => $_POST['nivel_acesso']
                ];

                $result_id = $usuarioModel->create($dados_usuario);
                if ($result_id) {
                    $_SESSION['success_message'] = "Usuário '" . escape_html($dados_usuario['nome']) . "' criado com sucesso!";
                    redirect(APP_URL . '/admin/usuarios_listar.php');
                } else {
                    // Mensagem de erro (ex: email duplicado) já deve estar na sessão pelo Model
                    $_SESSION['error_message'] = $_SESSION['error_message'] ?? "Erro ao criar o usuário.";
                    // $_SESSION['form_data_usuario'] = $_POST;
                    redirect(APP_URL . '/admin/usuario_criar.php');
                }
            } else {
                redirect(APP_URL . '/admin/usuario_criar.php');
            }
            break;

        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                if (empty($id) || empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['nivel_acesso'])) {
                    $_SESSION['error_message'] = "ID, Nome, email e nível de acesso são obrigatórios para edição.";
                    redirect(APP_URL . ( $id ? '/admin/usuario_editar.php?id=' . $id : '/admin/usuarios_listar.php'));
                    exit;
                }
                 if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error_message'] = "Formato de email inválido.";
                    redirect(APP_URL . '/admin/usuario_editar.php?id=' . $id);
                    exit;
                }
                 if (!empty($_POST['senha']) && strlen($_POST['senha']) < 6) {
                    $_SESSION['error_message'] = "A nova senha deve ter pelo menos 6 caracteres.";
                    redirect(APP_URL . '/admin/usuario_editar.php?id=' . $id);
                    exit;
                }
                if (!in_array($_POST['nivel_acesso'], Usuario::getNiveisAcessoPermitidos())) {
                    $_SESSION['error_message'] = "Nível de acesso inválido.";
                    redirect(APP_URL . '/admin/usuario_editar.php?id=' . $id);
                    exit;
                }

                $dados_update = [
                    'nome' => trim($_POST['nome']),
                    'email' => trim($_POST['email']),
                    'nivel_acesso' => $_POST['nivel_acesso']
                ];
                if (!empty($_POST['senha'])) { // Só atualiza senha se fornecida
                    $dados_update['senha'] = $_POST['senha'];
                }

                if ($usuarioModel->update($id, $dados_update)) {
                    $_SESSION['success_message'] = "Usuário '" . escape_html($dados_update['nome']) . "' atualizado com sucesso!";
                    redirect(APP_URL . '/admin/usuarios_listar.php');
                } else {
                     $_SESSION['error_message'] = $_SESSION['error_message'] ?? "Erro ao atualizar o usuário.";
                    redirect(APP_URL . '/admin/usuario_editar.php?id=' . $id);
                }
            } else {
                $id = $_GET['id'] ?? null;
                if ($id) {
                    redirect(APP_URL . '/admin/usuario_editar.php?id=' . $id);
                } else {
                    $_SESSION['error_message'] = "ID do usuário não especificado para edição.";
                    redirect(APP_URL . '/admin/usuarios_listar.php');
                }
            }
            break;

        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm_delete'])) {
                $id = $_POST['id'] ?? $_GET['id'] ?? null;
                if (!$id) {
                    $_SESSION['error_message'] = "ID do usuário não fornecido para exclusão.";
                } else {
                    $usuario_para_deletar = $usuarioModel->getById($id);
                    if (!$usuario_para_deletar) {
                        $_SESSION['error_message'] = "Usuário não encontrado para exclusão.";
                    } else {
                        if ($usuarioModel->delete($id)) {
                            $_SESSION['success_message'] = "Usuário '" . escape_html($usuario_para_deletar['nome']) . "' excluído com sucesso!";
                        } else {
                            // Mensagem de erro já deve estar na sessão (ex: não pode excluir a si mesmo, ou erro de DB)
                             $_SESSION['error_message'] = $_SESSION['error_message'] ?? "Erro ao excluir o usuário.";
                        }
                    }
                }
            } else {
                 $_SESSION['error_message'] = "Ação de exclusão inválida.";
            }
            redirect(APP_URL . '/admin/usuarios_listar.php');
            break;

        default:
            redirect(APP_URL . '/admin/usuarios_listar.php');
            break;
    }
} catch (Exception $e) {
    add_log('erro', 'UsuarioController_Exception', $e->getMessage(), $logged_in_user_id);
    $_SESSION['error_message'] = "Ocorreu um erro inesperado no gerenciamento de usuários: " . $e->getMessage();
    redirect(APP_URL . '/admin/usuarios_listar.php');
}
?>
