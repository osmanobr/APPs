<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Evento.php';

// Proteção geral do controller: Apenas administradores podem acessar as ações de evento.
// Funções específicas podem ter verificações adicionais se necessário.
// A chamada a protect_page() já está no header das views, mas é uma boa prática proteger o controller também.
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
if (!is_logged_in() || get_user_access_level() !== 'admin') {
    $_SESSION['error_message'] = "Acesso negado. Você precisa ser administrador.";
    redirect(APP_URL . '/login.php');
}


$action = $_REQUEST['action'] ?? null; // Usar $_REQUEST para pegar tanto GET quanto POST

$eventoModel = new Evento();

try {
    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validação básica (pode ser mais robusta)
                if (empty($_POST['nome']) || empty($_POST['data_inicio']) || empty($_POST['data_fim'])) {
                    $_SESSION['error_message'] = "Nome, data de início e data de fim são obrigatórios.";
                    redirect(APP_URL . '/admin/evento_criar.php'); // Redireciona de volta para o form
                    exit;
                }
                // Validar formato das datas, se data_fim é maior que data_inicio, etc.
                // ...

                $dados = [
                    'nome' => trim($_POST['nome']),
                    'data_inicio' => $_POST['data_inicio'],
                    'data_fim' => $_POST['data_fim'],
                    'organizador_id' => !empty($_POST['organizador_id']) ? $_POST['organizador_id'] : null,
                    'descricao' => trim($_POST['descricao'] ?? '')
                ];

                $result = $eventoModel->create($dados);
                if ($result) {
                    $_SESSION['success_message'] = "Evento '" . escape_html($dados['nome']) . "' criado com sucesso!";
                    redirect(APP_URL . '/admin/eventos_listar.php');
                } else {
                    $_SESSION['error_message'] = "Erro ao criar o evento.";
                    // Manter os dados do post na sessão para repopular o formulário (opcional)
                    // $_SESSION['form_data'] = $_POST;
                    redirect(APP_URL . '/admin/evento_criar.php');
                }
            } else {
                // Se acessado via GET, redirecionar para o formulário de criação
                redirect(APP_URL . '/admin/evento_criar.php');
            }
            break;

        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                if (!$id) {
                    $_SESSION['error_message'] = "ID do evento não fornecido para edição.";
                    redirect(APP_URL . '/admin/eventos_listar.php');
                    exit;
                }
                if (empty($_POST['nome']) || empty($_POST['data_inicio']) || empty($_POST['data_fim'])) {
                    $_SESSION['error_message'] = "Nome, data de início e data de fim são obrigatórios.";
                    redirect(APP_URL . '/admin/evento_editar.php?id=' . $id);
                    exit;
                }

                $dados = [
                    'nome' => trim($_POST['nome']),
                    'data_inicio' => $_POST['data_inicio'],
                    'data_fim' => $_POST['data_fim'],
                    'organizador_id' => !empty($_POST['organizador_id']) ? $_POST['organizador_id'] : null,
                    'descricao' => trim($_POST['descricao'] ?? '')
                ];

                if ($eventoModel->update($id, $dados)) {
                    $_SESSION['success_message'] = "Evento '" . escape_html($dados['nome']) . "' atualizado com sucesso!";
                    redirect(APP_URL . '/admin/eventos_listar.php');
                } else {
                    $_SESSION['error_message'] = "Erro ao atualizar o evento.";
                    // $_SESSION['form_data'] = $_POST; // Para repopular
                    redirect(APP_URL . '/admin/evento_editar.php?id=' . $id);
                }
            } else {
                // Se acessado via GET com ID, redirecionar para o formulário de edição
                // A lógica de carregar dados para edição está na view evento_editar.php
                $id = $_GET['id'] ?? null;
                if ($id) {
                    redirect(APP_URL . '/admin/evento_editar.php?id=' . $id);
                } else {
                    $_SESSION['error_message'] = "ID do evento não especificado para edição.";
                    redirect(APP_URL . '/admin/eventos_listar.php');
                }
            }
            break;

        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm_delete'])) { // Permitir deleção via POST (formulário) ou GET com confirmação
                $id = $_POST['id'] ?? $_GET['id'] ?? null;
                 if (!$id) {
                    $_SESSION['error_message'] = "ID do evento não fornecido para exclusão.";
                    redirect(APP_URL . '/admin/eventos_listar.php');
                    exit;
                }

                $evento = $eventoModel->getById($id);
                if (!$evento) {
                    $_SESSION['error_message'] = "Evento não encontrado para exclusão.";
                    redirect(APP_URL . '/admin/eventos_listar.php');
                    exit;
                }

                if ($eventoModel->delete($id)) {
                    // A mensagem de erro em caso de FK constraint é definida no Model
                    if (!isset($_SESSION['error_message'])) {
                         $_SESSION['success_message'] = "Evento '" . escape_html($evento['nome']) . "' excluído com sucesso!";
                    }
                } else {
                    // Se já não houver uma mensagem de erro específica (como FK), define uma genérica
                    if (!isset($_SESSION['error_message'])) {
                        $_SESSION['error_message'] = "Erro ao excluir o evento '" . escape_html($evento['nome']) . "'.";
                    }
                }
                redirect(APP_URL . '/admin/eventos_listar.php');

            } else {
                 // Se for um GET sem confirmação, talvez mostrar uma página de confirmação
                 // ou simplesmente redirecionar se a exclusão for apenas por POST.
                 // Por simplicidade, vamos assumir que a confirmação está na view de listagem (modal).
                $_SESSION['error_message'] = "Ação de exclusão inválida.";
                redirect(APP_URL . '/admin/eventos_listar.php');
            }
            break;

        default:
            // Ação padrão: listar eventos (se nenhuma ação específica for chamada)
            // A listagem é feita pela view eventos_listar.php diretamente
            redirect(APP_URL . '/admin/eventos_listar.php');
            break;
    }
} catch (Exception $e) {
    // Logar a exceção
    add_log('erro', 'EventoController_Exception', $e->getMessage(), get_logged_in_user_id());
    $_SESSION['error_message'] = "Ocorreu um erro inesperado no sistema. Tente novamente ou contate o suporte.";
    // Redirecionar para uma página de erro ou de volta para a listagem
    redirect(APP_URL . '/admin/eventos_listar.php');
}
?>
