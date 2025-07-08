<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Hotel.php';

// Proteção: Apenas administradores
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
if (!is_logged_in() || get_user_access_level() !== 'admin') {
    $_SESSION['error_message'] = "Acesso negado. Você precisa ser administrador.";
    redirect(APP_URL . '/login.php');
}

$action = $_REQUEST['action'] ?? null;
$hotelModel = new Hotel();

try {
    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (empty($_POST['nome'])) {
                    $_SESSION['error_message'] = "O nome do hotel é obrigatório.";
                    redirect(APP_URL . '/admin/hotel_criar.php');
                    exit;
                }
                $dados = [
                    'nome' => trim($_POST['nome']),
                    'endereco' => trim($_POST['endereco'] ?? '')
                ];
                if ($hotelModel->create($dados)) {
                    $_SESSION['success_message'] = "Hotel '" . escape_html($dados['nome']) . "' criado com sucesso!";
                    redirect(APP_URL . '/admin/hoteis_listar.php');
                } else {
                    $_SESSION['error_message'] = "Erro ao criar o hotel.";
                    redirect(APP_URL . '/admin/hotel_criar.php');
                }
            } else {
                redirect(APP_URL . '/admin/hotel_criar.php');
            }
            break;

        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                if (!$id || empty($_POST['nome'])) {
                    $_SESSION['error_message'] = "ID e nome do hotel são obrigatórios para edição.";
                    redirect(APP_URL . ( $id ? '/admin/hotel_editar.php?id=' . $id : '/admin/hoteis_listar.php'));
                    exit;
                }
                $dados = [
                    'nome' => trim($_POST['nome']),
                    'endereco' => trim($_POST['endereco'] ?? '')
                ];
                if ($hotelModel->update($id, $dados)) {
                    $_SESSION['success_message'] = "Hotel '" . escape_html($dados['nome']) . "' atualizado com sucesso!";
                    redirect(APP_URL . '/admin/hoteis_listar.php');
                } else {
                    $_SESSION['error_message'] = "Erro ao atualizar o hotel.";
                    redirect(APP_URL . '/admin/hotel_editar.php?id=' . $id);
                }
            } else {
                $id = $_GET['id'] ?? null;
                if ($id) {
                    redirect(APP_URL . '/admin/hotel_editar.php?id=' . $id);
                } else {
                    $_SESSION['error_message'] = "ID do hotel não especificado para edição.";
                    redirect(APP_URL . '/admin/hoteis_listar.php');
                }
            }
            break;

        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm_delete'])) {
                $id = $_POST['id'] ?? $_GET['id'] ?? null;
                if (!$id) {
                    $_SESSION['error_message'] = "ID do hotel não fornecido para exclusão.";
                    redirect(APP_URL . '/admin/hoteis_listar.php');
                    exit;
                }
                $hotel = $hotelModel->getById($id);
                if (!$hotel) {
                    $_SESSION['error_message'] = "Hotel não encontrado para exclusão.";
                    redirect(APP_URL . '/admin/hoteis_listar.php');
                    exit;
                }

                if ($hotelModel->delete($id)) {
                    if (!isset($_SESSION['error_message'])) { // Se o model não setou erro de FK
                        $_SESSION['success_message'] = "Hotel '" . escape_html($hotel['nome']) . "' excluído com sucesso!";
                    }
                } else {
                     if (!isset($_SESSION['error_message'])) {
                        $_SESSION['error_message'] = "Erro ao excluir o hotel '" . escape_html($hotel['nome']) . "'.";
                     }
                }
                redirect(APP_URL . '/admin/hoteis_listar.php');
            } else {
                $_SESSION['error_message'] = "Ação de exclusão inválida.";
                redirect(APP_URL . '/admin/hoteis_listar.php');
            }
            break;

        default:
            redirect(APP_URL . '/admin/hoteis_listar.php');
            break;
    }
} catch (Exception $e) {
    add_log('erro', 'HotelController_Exception', $e->getMessage(), get_logged_in_user_id());
    $_SESSION['error_message'] = "Ocorreu um erro inesperado no sistema de hotéis.";
    redirect(APP_URL . '/admin/hoteis_listar.php');
}
?>
