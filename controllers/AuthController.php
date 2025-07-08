<?php
// Este arquivo lida com a lógica de autenticação (login, logout, etc.)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';

// Garante que a sessão seja iniciada (já está no config.php, mas é bom garantir)
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}

$action = $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        handle_login();
    } elseif ($action === 'register') {
        // handle_registration(); // Implementar se houver página de registro
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'logout') {
        handle_logout();
    }
}

function handle_login() {
    if (empty($_POST['email']) || empty($_POST['senha'])) {
        $_SESSION['login_error'] = "Email e senha são obrigatórios.";
        add_log('aviso', 'login_tentativa_falha', 'Campos vazios', null);
        redirect(APP_URL . '/login.php');
        return;
    }

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    try {
        $db = DB::getInstance();
        $pdo = $db->getConnection();

        $sql = "SELECT id, nome, email, senha_hash, nivel_acesso FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && verify_password($senha, $user['senha_hash'])) {
            // Login bem-sucedido
            session_regenerate_id(true); // Regenera o ID da sessão para segurança

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_level'] = $user['nivel_acesso'];

            // Definir para onde redirecionar com base no nível (opcional)
            $redirect_url = APP_URL . '/admin/dashboard.php'; // Padrão
            // Exemplo:
            // if ($user['nivel_acesso'] === 'vendedor') {
            //     $_SESSION['dashboard_url'] = '/vendedor/dashboard.php';
            //     $redirect_url = APP_URL . $_SESSION['dashboard_url'];
            // } elseif ($user['nivel_acesso'] === 'admin') {
            //      $_SESSION['dashboard_url'] = '/admin/dashboard.php';
            //      $redirect_url = APP_URL . $_SESSION['dashboard_url'];
            // } ...

            add_log('info', 'login_sucesso', 'Usuário ' . $user['email'] . ' logado com sucesso.', $user['id']);
            redirect($redirect_url);
        } else {
            // Credenciais inválidas
            $_SESSION['login_error'] = "Email ou senha inválidos.";
            add_log('aviso', 'login_tentativa_falha', 'Credenciais inválidas para o email: ' . escape_html($email), null);
            redirect(APP_URL . '/login.php');
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Erro no sistema. Tente novamente mais tarde.";
        // Logar o erro real para o administrador
        add_log('erro', 'login_excecao_db', 'PDOException: ' . $e->getMessage() . ' para o email: ' . escape_html($email), null);
        error_log("Erro de PDO no login: " . $e->getMessage());
        redirect(APP_URL . '/login.php');
    } catch (Exception $e) {
        $_SESSION['login_error'] = "Ocorreu um erro inesperado.";
        add_log('erro', 'login_excecao_geral', 'Exception: ' . $e->getMessage() . ' para o email: ' . escape_html($email), null);
        error_log("Erro geral no login: " . $e->getMessage());
        redirect(APP_URL . '/login.php');
    }
}

function handle_logout() {
    if (is_logged_in()) {
        $userId = get_logged_in_user_id();
        $userEmail = $_SESSION['user_email'] ?? 'N/A';
        add_log('info', 'logout', 'Usuário ' . $userEmail . ' deslogado.', $userId);
    }

    // Limpa todas as variáveis de sessão
    $_SESSION = array();

    // Destrói o cookie da sessão se existir
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalmente, destrói a sessão.
    session_destroy();

    redirect(APP_URL . '/login.php');
}

// Função para registrar um novo usuário (exemplo básico)
// Você precisaria de uma página register.php e um formulário para isso.
/*
function handle_registration() {
    // Validação de dados (nome, email, senha, confirmação de senha, nível - se aplicável)
    // ...

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nivel_acesso = 'inquilino'; // Ou o nível padrão para auto-registro

    // Verificar se o email já existe
    // ...

    $hashed_password = hash_password($senha);
    $user_id = generate_uuid();

    try {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $sql = "INSERT INTO usuarios (id, nome, email, senha_hash, nivel_acesso) VALUES (:id, :nome, :email, :senha_hash, :nivel_acesso)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $user_id,
            ':nome' => $nome,
            ':email' => $email,
            ':senha_hash' => $hashed_password,
            ':nivel_acesso' => $nivel_acesso
        ]);

        $_SESSION['success_message'] = "Usuário registrado com sucesso! Faça login.";
        add_log('info', 'registro_usuario', 'Novo usuário registrado: ' . $email, $user_id);
        redirect(APP_URL . '/login.php?registered=true');

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro ao registrar. Tente novamente.";
        add_log('erro', 'registro_usuario_falha_db', 'PDOException: ' . $e->getMessage() . ' para o email: ' . $email, null);
        redirect(APP_URL . '/register.php'); // Ou para a página de registro com erro
    }
}
*/

// Se nenhuma ação válida for fornecida ou acesso direto ao script
// http_response_code(400); // Bad Request
// echo "Ação inválida.";
// redirect(APP_URL . '/login.php'); // Redireciona para login como padrão
?>
