<?php
// Configurações do Banco de Dados
define('DB_HOST', 'localhost'); // Ou o host do seu BD
define('DB_NAME', 'eventos_hospedagem_db'); // Nome do seu banco de dados
define('DB_USER', 'root'); // Seu usuário do BD
define('DB_PASS', ''); // Sua senha do BD
define('DB_CHARSET', 'utf8mb4');

// Configurações da Aplicação
define('APP_URL', 'http://localhost/seu_projeto'); // URL base da sua aplicação
define('SITE_NAME', 'Gestão de Eventos');

// Configurações de Sessão
define('SESSION_NAME', 'eventos_session');

// Outras configurações
error_reporting(E_ALL);
ini_set('display_errors', 1); // Mudar para 0 em produção

// Fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Função para iniciar a sessão de forma segura
function start_secure_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_name(SESSION_NAME . '_Sec');
        session_set_cookie_params([
            'lifetime' => 0, // Cookie expira ao fechar o navegador
            'path' => '/',
            'domain' => '', // Preencher se necessário para subdomínios
            'secure' => isset($_SERVER['HTTPS']), // True se HTTPS
            'httponly' => true, // Previne acesso via JavaScript
            'samesite' => 'Lax' // Ajuda a prevenir CSRF
        ]);
        session_start();
    }
}

// Iniciar sessão para todas as páginas que incluem este config
start_secure_session();

?>
