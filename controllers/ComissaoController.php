<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/ComissaoVendedor.php';

if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}

$response = ['success' => false, 'message' => 'Ação inválida ou não permitida.'];
$action = $_POST['action'] ?? $_GET['action'] ?? null;

// Apenas admin pode atualizar status de comissões
if (!is_logged_in() || get_user_access_level() !== 'admin') {
    if ($action === 'update_comissao_status') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
        exit;
    } else {
        $_SESSION['error_message'] = "Acesso negado.";
        redirect(APP_URL . '/login.php');
    }
}

$comissaoModel = new ComissaoVendedor();
$logged_in_user_id = get_logged_in_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_comissao_status') {
    header('Content-Type: application/json');
    $comissao_id = $_POST['comissao_id'] ?? null;
    $novo_status = $_POST['novo_status'] ?? null;
    $data_pagamento_comissao_str = $_POST['data_pagamento_comissao'] ?? null;

    if (empty($comissao_id) || empty($novo_status)) {
        echo json_encode(['success' => false, 'message' => 'ID da comissão e novo status são obrigatórios.']);
        exit;
    }

    if (!in_array($novo_status, ['paga', 'pendente', 'cancelada'])) {
        echo json_encode(['success' => false, 'message' => 'Status inválido fornecido.']);
        exit;
    }

    $data_pagamento_comissao = null;
    if ($novo_status === 'paga' && !empty($data_pagamento_comissao_str)) {
        try {
            $dt = new DateTime($data_pagamento_comissao_str);
            $data_pagamento_comissao = $dt->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            // Data inválida, mas o model pode usar CURRENT_TIMESTAMP se for null
        }
    }


    if ($comissaoModel->updateStatus($comissao_id, $novo_status, $data_pagamento_comissao)) {
        // Log já é feito no model
        echo json_encode(['success' => true, 'message' => 'Status da comissão atualizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => $_SESSION['error_message'] ?? 'Erro ao atualizar o status da comissão.']);
        unset($_SESSION['error_message']); // Limpa se foi setada pelo model
    }
    exit;

} else if ($action) {
    $_SESSION['error_message'] = "Ação desconhecida para comissões.";
    redirect(APP_URL . '/admin/comissoes_vendedor_listar.php');
}

// Se nenhuma ação AJAX POST válida, e não outras ações, redireciona para a listagem
if (!$action || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(APP_URL . '/admin/comissoes_vendedor_listar.php');
}
?>
