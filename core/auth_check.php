<?php
// Este script deve ser incluído no topo de qualquer página que requer autenticação.
// Ele utiliza a função protect_page() de core/functions.php

// Garante que as funções e configurações principais sejam carregadas.
// O config.php já é chamado em functions.php, que é chamado em header.php,
// mas para scripts que podem ser chamados independentemente (como processamento de formulários),
// é bom garantir que tudo necessário esteja carregado.
if (!defined('DB_HOST')) { // Verifica se config.php já foi carregado
    require_once __DIR__ . '/../config/config.php';
}
if (!function_exists('is_logged_in')) { // Verifica se functions.php já foi carregado
    require_once __DIR__ . '/functions.php';
}


/**
 * Verifica se o usuário está logado e, opcionalmente, se tem o nível de acesso necessário.
 * Esta é uma reiteração da função protect_page para ser usada diretamente se necessário,
 * mas o ideal é chamar protect_page() que já faz o redirecionamento.
 *
 * @param array|string|null $required_levels Nível(s) de acesso requerido(s).
 *                                       Pode ser uma string para um único nível ou um array para múltiplos níveis.
 *                                       Se null, apenas verifica se está logado.
 * @return bool True se o usuário atende aos requisitos, false caso contrário.
 */
function check_authentication($required_levels = null) {
    if (!is_logged_in()) {
        if (session_status() == PHP_SESSION_NONE) { // Garante que a sessão está ativa para definir a mensagem
            start_secure_session();
        }
        $_SESSION['error_message'] = "Você precisa estar logado para acessar esta área.";
        return false;
    }

    if ($required_levels !== null) {
        $user_level = get_user_access_level();
        if (is_array($required_levels)) {
            if (!in_array($user_level, $required_levels)) {
                if (session_status() == PHP_SESSION_NONE) {
                    start_secure_session();
                }
                $_SESSION['error_message'] = "Você não tem permissão para acessar esta página.";
                return false;
            }
        } else {
            if ($user_level !== $required_levels) {
                if (session_status() == PHP_SESSION_NONE) {
                    start_secure_session();
                }
                $_SESSION['error_message'] = "Você não tem permissão para acessar esta página.";
                return false;
            }
        }
    }
    return true;
}

// Exemplo de uso (geralmente protect_page() é mais direto):
// include __DIR__ . '/core/auth_check.php';
//
// // Para uma página que requer apenas login:
// if (!check_authentication()) {
//     redirect(APP_URL . '/login.php');
// }
//
// // Para uma página que requer nível 'admin':
// if (!check_authentication('admin')) {
//     // Se a função check_authentication já define a mensagem de erro e retorna false,
//     // o redirect pode ser feito aqui ou dentro da função protect_page.
//     // A função protect_page já faz o redirect.
//     redirect(APP_URL . '/admin/dashboard.php'); // Ou alguma página de acesso negado
// }
//
// // Para uma página que requer nível 'admin' OU 'vendedor':
// if (!check_authentication(['admin', 'vendedor'])) {
//    redirect(APP_URL . '/admin/dashboard.php');
// }

// A principal forma de usar isso será chamando protect_page($nivel_requerido) no topo dos scripts.
// Ex: require_once __DIR__ . '/../core/auth_check.php'; protect_page('admin');
// Ou, melhor ainda, o protect_page já está em functions.php, que é carregado pelo header.
// Então, nas páginas, apenas:
// require_once __DIR__ . '/../views/partials/header.php'; // header já carrega functions.php
// protect_page('admin'); // Chama a função diretamente

?>
