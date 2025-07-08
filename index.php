<?php
// Raiz do projeto - redireciona para login ou dashboard

// Garante que as configurações e funções essenciais sejam carregadas.
// start_secure_session() já é chamado dentro de config.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/functions.php'; // Para is_logged_in() e redirect()

if (is_logged_in()) {
    // Usuário está logado.
    // Determinar para qual dashboard redirecionar com base no nível de acesso.
    $user_level = get_user_access_level();
    $dashboard_url = APP_URL . '/admin/dashboard.php'; // Padrão para admin e outros por enquanto

    // Você pode adicionar lógicas mais específicas aqui se tiver dashboards diferentes:
    // if ($user_level === 'vendedor') {
    //    $dashboard_url = APP_URL . '/vendedor/dashboard.php';
    // } elseif ($user_level === 'funcionario') {
    //    $dashboard_url = APP_URL . '/funcionario/dashboard.php';
    // } // etc.

    redirect($dashboard_url);
} else {
    // Usuário não está logado, redireciona para a página de login.
    redirect(APP_URL . '/login.php');
}
exit;
?>
