<?php
// Este script simplesmente chama o AuthController para lidar com o logout.
// Isso mantém a lógica centralizada no controller.

require_once __DIR__ . '/controllers/AuthController.php';

// A função handle_logout() em AuthController.php já faz o redirect.
// Se AuthController.php não for incluído diretamente (o que é o caso aqui, pois ele não produz output direto, apenas define funções e age em requests),
// precisamos de uma forma de invocar a ação.
// A maneira mais comum é redirecionar para o controller com um parâmetro de ação.

// No entanto, para simplificar o logout.php, podemos apenas chamar a função diretamente
// se o AuthController.php for estruturado para permitir isso (ou seja, não sair prematuramente).
// A estrutura atual do AuthController.php espera ser chamado via URL com ?action=logout.

// Vamos manter o logout.php simples e apenas redirecionar para o controller.
// Isso garante que toda a lógica de logout (limpeza de sessão, logs) está no AuthController.

require_once __DIR__ . '/config/config.php'; // Para APP_URL
require_once __DIR__ . '/core/functions.php'; // Para redirect()

// Não precisa iniciar sessão aqui, AuthController fará isso.
redirect(APP_URL . '/controllers/AuthController.php?action=logout');

// Alternativamente, se quiséssemos colocar a lógica de logout aqui:
/*
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    start_secure_session(); // Garante que a sessão está iniciada para ser destruída
}

if (is_logged_in()) {
    $userId = get_logged_in_user_id();
    $userEmail = $_SESSION['user_email'] ?? 'N/A'; // Assumindo que user_email está na sessão
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
*/
?>
