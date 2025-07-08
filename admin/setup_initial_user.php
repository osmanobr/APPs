<?php
// ATENÇÃO: Este script é para configuração inicial e deve ser removido ou protegido após o uso.
// Execute este script uma vez para criar o usuário administrador padrão.

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php'; // Para generate_uuid() e hash_password()

// Detalhes do usuário administrador padrão
$admin_email = 'admin@example.com';
$admin_password = 'password123'; // Mude isso para uma senha forte!
$admin_nome = 'Administrador';
$admin_nivel = 'admin';

echo "<pre>";
echo "Iniciando configuração do usuário administrador...\n";

try {
    $db = DB::getInstance();
    $pdo = $db->getConnection();

    // Verificar se o usuário já existe
    $sql_check = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':email', $admin_email, PDO::PARAM_STR);
    $stmt_check->execute();

    if ($stmt_check->fetch()) {
        echo "Usuário administrador com email '{$admin_email}' já existe.\n";
        echo "Nenhuma ação foi tomada.\n";
        echo "</pre>";
        exit;
    }

    // Criar o usuário
    $admin_id = generate_uuid();
    $hashed_password = hash_password($admin_password);

    $sql_insert = "INSERT INTO usuarios (id, nome, email, senha_hash, nivel_acesso)
                   VALUES (:id, :nome, :email, :senha_hash, :nivel_acesso)";
    $stmt_insert = $pdo->prepare($sql_insert);

    $stmt_insert->bindParam(':id', $admin_id, PDO::PARAM_STR);
    $stmt_insert->bindParam(':nome', $admin_nome, PDO::PARAM_STR);
    $stmt_insert->bindParam(':email', $admin_email, PDO::PARAM_STR);
    $stmt_insert->bindParam(':senha_hash', $hashed_password, PDO::PARAM_STR);
    $stmt_insert->bindParam(':nivel_acesso', $admin_nivel, PDO::PARAM_STR);

    if ($stmt_insert->execute()) {
        echo "Usuário administrador criado com sucesso!\n";
        echo "----------------------------------------\n";
        echo "Nome: " . escape_html($admin_nome) . "\n";
        echo "Email: " . escape_html($admin_email) . "\n";
        echo "Senha: " . escape_html($admin_password) . " (esta é a senha que você definiu no script)\n";
        echo "Nível de Acesso: " . escape_html($admin_nivel) . "\n";
        echo "ID do Usuário (UUID): " . escape_html($admin_id) . "\n";
        echo "----------------------------------------\n";
        echo "IMPORTANTE: Remova ou proteja este arquivo (setup_initial_user.php) imediatamente.\n";

        // Log da criação do usuário admin (opcional, pois o sistema de log pode não estar 100% pronto)
        // add_log('info', 'setup_admin', 'Usuário administrador padrão criado: ' . $admin_email, $admin_id);

    } else {
        echo "Falha ao criar usuário administrador.\n";
        // Informações de erro do PDO, se disponíveis
        $errorInfo = $stmt_insert->errorInfo();
        if ($errorInfo[0] !== '00000') {
            echo "Erro PDO [{$errorInfo[0]}]: {$errorInfo[2]}\n";
        }
    }

} catch (PDOException $e) {
    echo "Erro de PDO ao configurar usuário: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Erro geral ao configurar usuário: " . $e->getMessage() . "\n";
}

echo "</pre>";

?>
