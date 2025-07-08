<?php
require_once __DIR__ . '/../config/config.php';

/**
 * Gera um UUID v4.
 *
 * @return string O UUID gerado.
 */
function generate_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

/**
 * Redireciona para uma URL.
 *
 * @param string $url A URL para redirecionar.
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Verifica se o usuário está logado.
 *
 * @return bool True se logado, false caso contrário.
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Retorna o ID do usuário logado.
 *
 * @return string|null ID do usuário ou null se não logado.
 */
function get_logged_in_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Retorna o nível de acesso do usuário logado.
 *
 * @return string|null Nível de acesso ou null se não logado.
 */
function get_user_access_level() {
    return $_SESSION['user_level'] ?? null;
}

/**
 * Protege uma página, exigindo login e, opcionalmente, um nível de acesso específico.
 *
 * @param array|string|null $required_level Nível(s) de acesso requerido(s).
 *                                       Pode ser uma string para um único nível ou um array para múltiplos níveis.
 *                                       Se null, apenas verifica se está logado.
 */
function protect_page($required_level = null) {
    if (!is_logged_in()) {
        $_SESSION['error_message'] = "Você precisa estar logado para acessar esta página.";
        redirect(APP_URL . '/login.php');
    }

    if ($required_level !== null) {
        $user_level = get_user_access_level();
        if (is_array($required_level)) {
            if (!in_array($user_level, $required_level)) {
                $_SESSION['error_message'] = "Você não tem permissão para acessar esta página.";
                redirect(APP_URL . (isset($_SESSION['dashboard_url']) ? $_SESSION['dashboard_url'] : '/admin/dashboard.php')); // Redireciona para um dashboard padrão ou específico
            }
        } else {
            if ($user_level !== $required_level) {
                $_SESSION['error_message'] = "Você não tem permissão para acessar esta página.";
                redirect(APP_URL . (isset($_SESSION['dashboard_url']) ? $_SESSION['dashboard_url'] : '/admin/dashboard.php'));
            }
        }
    }
}

/**
 * Escapa output HTML para prevenir XSS.
 *
 * @param string $string A string para escapar.
 * @return string A string escapada.
 */
function escape_html($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Função para registrar logs.
 *
 * @param string $level Nível do log (info, erro, aviso, debug).
 * @param string $action Ação ou módulo relacionado ao log.
 * @param string $details Detalhes do log.
 * @param string|null $userId ID do usuário que realizou a ação (opcional).
 */
function add_log($level, $action, $details, $userId = null) {
    require_once __DIR__ . '/db.php'; // Garante que a classe DB esteja disponível
    try {
        $db = DB::getInstance();
        $pdo = $db->getConnection();

        $sql = "INSERT INTO logs (id, usuario_id, nivel, acao, detalhes, ip_origem) VALUES (:id, :usuario_id, :nivel, :acao, :detalhes, :ip_origem)";
        $stmt = $pdo->prepare($sql);

        $ip_origem = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
        $log_id = generate_uuid();
        $current_user_id = $userId ?? get_logged_in_user_id();

        $stmt->bindParam(':id', $log_id);
        $stmt->bindParam(':usuario_id', $current_user_id); // Pode ser null
        $stmt->bindParam(':nivel', $level);
        $stmt->bindParam(':acao', $action);
        $stmt->bindParam(':detalhes', $details);
        $stmt->bindParam(':ip_origem', $ip_origem);

        $stmt->execute();
    } catch (PDOException $e) {
        // Em um cenário real, tratar esse erro de forma mais robusta
        // (e.g., logar em arquivo se o BD de logs falhar)
        error_log("Falha ao registrar log no banco de dados: " . $e->getMessage());
    } catch (Exception $e) {
        error_log("Erro geral ao registrar log: " . $e->getMessage());
    }
}

/**
 * Cria um hash de senha.
 *
 * @param string $password A senha para hashear.
 * @return string O hash da senha.
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verifica uma senha contra um hash.
 *
 * @param string $password A senha para verificar.
 * @param string $hash O hash para comparar.
 * @return bool True se a senha corresponde ao hash, false caso contrário.
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

?>
