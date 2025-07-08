<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/ParticipacaoEvento.php';
require_once __DIR__ . '/../models/Evento.php'; // Para buscar lista de eventos
require_once __DIR__ . '/../models/Inquilino.php'; // Para buscar lista de inquilinos

// Níveis de acesso: admin, funcionario (para check-in no evento)
// Vendedor pode apenas visualizar, talvez.
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
// Ajustar permissões conforme a necessidade de cada ação
// protect_page(['admin', 'funcionario']); // Proteção geral, pode ser mais granular por ação

$action = $_REQUEST['action'] ?? null;
$participacaoEventoModel = new ParticipacaoEvento();
$logged_in_user_id = get_logged_in_user_id();

try {
    switch ($action) {
        case 'checkin_participante': // Processa o formulário de check-in no evento
            if (!in_array(get_user_access_level(), ['admin', 'funcionario'])) {
                 $_SESSION['error_message'] = "Acesso negado para registrar check-in.";
                 redirect(APP_URL . '/admin/dashboard.php');
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['evento_id']) || empty($_POST['inquilino_id'])) {
                    $_SESSION['error_message'] = "Evento e Inquilino são obrigatórios para o check-in.";
                    // $_SESSION['form_data_checkin_evento'] = $_POST;
                    redirect(APP_URL . '/admin/evento_checkin_participante.php' . (!empty($_POST['evento_id']) ? '?evento_id='.$_POST['evento_id'] : ''));
                    exit;
                }
                $dados_checkin = [
                    'evento_id' => $_POST['evento_id'],
                    'inquilino_id' => $_POST['inquilino_id'],
                    'tipo_participacao' => trim($_POST['tipo_participacao'] ?? ''),
                    'comprovante_checkin' => trim($_POST['comprovante_checkin'] ?? ''),
                    'observacoes' => trim($_POST['observacoes'] ?? '')
                ];

                $result_checkin_id = $participacaoEventoModel->create($dados_checkin);
                if ($result_checkin_id) {
                    $_SESSION['success_message'] = "Check-in de participante realizado com sucesso!";
                    redirect(APP_URL . '/admin/evento_participantes_listar.php?evento_id=' . $dados_checkin['evento_id']);
                } else {
                    // A mensagem de erro específica (ex: duplicado) é definida no Model
                    $_SESSION['error_message'] = $_SESSION['error_message'] ?? "Erro ao realizar o check-in do participante.";
                    // $_SESSION['form_data_checkin_evento'] = $_POST;
                    redirect(APP_URL . '/admin/evento_checkin_participante.php?evento_id=' . $dados_checkin['evento_id']);
                }
            } else {
                // Se for GET, redireciona para a página do formulário de checkin (pode passar evento_id)
                $evento_id_get = $_GET['evento_id'] ?? '';
                redirect(APP_URL . '/admin/evento_checkin_participante.php' . ($evento_id_get ? '?evento_id='.$evento_id_get : ''));
            }
            break;

        case 'delete_participacao':
            if (!in_array(get_user_access_level(), ['admin'])) { // Apenas admin pode deletar
                 $_SESSION['error_message'] = "Acesso negado para excluir participação.";
                 redirect(APP_URL . '/admin/dashboard.php');
            }
             // Geralmente por POST para segurança, ou GET com confirmação
            $participacao_id = $_REQUEST['participacao_id'] ?? null;
            $evento_id_redirect = $_REQUEST['evento_id_redirect'] ?? null; // Para redirecionar de volta

            if (!$participacao_id) {
                $_SESSION['error_message'] = "ID da participação não fornecido para exclusão.";
            } else {
                $participacao = $participacaoEventoModel->getById($participacao_id);
                if (!$participacao) {
                     $_SESSION['error_message'] = "Registro de participação não encontrado.";
                } else {
                    if ($participacaoEventoModel->delete($participacao_id)) {
                        $_SESSION['success_message'] = "Registro de participação excluído com sucesso.";
                    } else {
                        $_SESSION['error_message'] = "Erro ao excluir o registro de participação.";
                    }
                }
            }
            redirect(APP_URL . ($evento_id_redirect ? '/admin/evento_participantes_listar.php?evento_id=' . $evento_id_redirect : '/admin/eventos_listar.php'));
            break;

        // A listagem é feita diretamente pela view evento_participantes_listar.php

        default:
             // Se a ação for GET e tiver um evento_id, provavelmente é para listar participantes
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['evento_id'])) {
                 redirect(APP_URL . '/admin/evento_participantes_listar.php?evento_id=' . $_GET['evento_id']);
            } else {
                // Ou redirecionar para a lista de eventos para escolher um
                redirect(APP_URL . '/admin/eventos_listar.php');
            }
            break;
    }
} catch (Exception $e) {
    add_log('erro', 'ParticipacaoEventoController_Exception', $e->getMessage(), $logged_in_user_id);
    $_SESSION['error_message'] = "Ocorreu um erro inesperado no sistema de participação em eventos: " . $e->getMessage();
    redirect(APP_URL . '/admin/eventos_listar.php');
}
?>
