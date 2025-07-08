<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Apartamento.php';

// Proteção: Apenas administradores
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
if (!is_logged_in() || get_user_access_level() !== 'admin') {
    $_SESSION['error_message'] = "Acesso negado. Você precisa ser administrador.";
    redirect(APP_URL . '/login.php');
}

$action = $_REQUEST['action'] ?? null;
$apartamentoModel = new Apartamento();

try {
    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validação básica
                if (empty($_POST['hotel_id']) || empty($_POST['numero_apartamento']) || empty($_POST['tipo_acomodacao']) || !isset($_POST['valor_diaria'])) {
                    $_SESSION['error_message'] = "Hotel, número do apartamento, tipo de acomodação e valor da diária são obrigatórios.";
                    // Salvar dados do post para repopular o formulário (opcional)
                    // $_SESSION['form_data_apartamento'] = $_POST;
                    redirect(APP_URL . '/admin/apartamento_criar.php');
                    exit;
                }
                if (!is_numeric($_POST['valor_diaria']) || $_POST['valor_diaria'] < 0) {
                     $_SESSION['error_message'] = "Valor da diária deve ser um número válido.";
                     redirect(APP_URL . '/admin/apartamento_criar.php');
                     exit;
                }

                $dados = [
                    'hotel_id' => $_POST['hotel_id'],
                    'numero_piso' => trim($_POST['numero_piso'] ?? ''),
                    'numero_apartamento' => trim($_POST['numero_apartamento']),
                    'tipo_acomodacao' => $_POST['tipo_acomodacao'],
                    'valor_diaria' => $_POST['valor_diaria'],
                    'vendedor_id' => !empty($_POST['vendedor_id']) ? $_POST['vendedor_id'] : null,
                    'responsavel_id' => !empty($_POST['responsavel_id']) ? $_POST['responsavel_id'] : null,
                ];

                if ($apartamentoModel->create($dados)) {
                    $_SESSION['success_message'] = "Apartamento '" . escape_html($dados['numero_apartamento']) . "' criado com sucesso!";
                    redirect(APP_URL . '/admin/apartamentos_listar.php');
                } else {
                    $_SESSION['error_message'] = "Erro ao criar o apartamento.";
                    // $_SESSION['form_data_apartamento'] = $_POST;
                    redirect(APP_URL . '/admin/apartamento_criar.php');
                }
            } else {
                redirect(APP_URL . '/admin/apartamento_criar.php');
            }
            break;

        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                if (empty($id) || empty($_POST['hotel_id']) || empty($_POST['numero_apartamento']) || empty($_POST['tipo_acomodacao']) || !isset($_POST['valor_diaria'])) {
                    $_SESSION['error_message'] = "ID, Hotel, número do apartamento, tipo de acomodação e valor da diária são obrigatórios para edição.";
                    redirect(APP_URL . ( $id ? '/admin/apartamento_editar.php?id=' . $id : '/admin/apartamentos_listar.php'));
                    exit;
                }
                 if (!is_numeric($_POST['valor_diaria']) || $_POST['valor_diaria'] < 0) {
                     $_SESSION['error_message'] = "Valor da diária deve ser um número válido.";
                     redirect(APP_URL . '/admin/apartamento_editar.php?id=' . $id);
                     exit;
                }

                $dados = [
                    'hotel_id' => $_POST['hotel_id'],
                    'numero_piso' => trim($_POST['numero_piso'] ?? ''),
                    'numero_apartamento' => trim($_POST['numero_apartamento']),
                    'tipo_acomodacao' => $_POST['tipo_acomodacao'],
                    'valor_diaria' => $_POST['valor_diaria'],
                    'vendedor_id' => !empty($_POST['vendedor_id']) ? $_POST['vendedor_id'] : null,
                    'responsavel_id' => !empty($_POST['responsavel_id']) ? $_POST['responsavel_id'] : null,
                ];

                // Buscar dados originais para comparar se o vendedor mudou
                $apartamento_original = $apartamentoModel->getById($id);
                if (!$apartamento_original) {
                    $_SESSION['error_message'] = "Apartamento original não encontrado para verificar alteração de vendedor.";
                    redirect(APP_URL . '/admin/apartamentos_listar.php');
                    exit;
                }

                if ($apartamentoModel->update($id, $dados)) {
                    // Verificar se o vendedor_id mudou para adicionar ao histórico
                    // Normalizar NULL e string vazia para comparação
                    $vendedor_original_id = $apartamento_original['vendedor_id'] ?? null;
                    $novo_vendedor_id = $dados['vendedor_id'] ?? null;

                    if ($vendedor_original_id !== $novo_vendedor_id) {
                        require_once __DIR__ . '/../models/HistoricoVendedorApartamento.php';
                        $histVendModel = new HistoricoVendedorApartamento();
                        $histVendModel->addEntry([
                            'apartamento_id' => $id,
                            'vendedor_id' => $novo_vendedor_id, // Pode ser NULL se o vendedor foi removido
                            'alterado_por_id' => get_logged_in_user_id(),
                            'motivo_alteracao' => 'Alteração de vendedor na edição do apartamento.'
                        ]);
                    }

                    $_SESSION['success_message'] = "Apartamento '" . escape_html($dados['numero_apartamento']) . "' atualizado com sucesso!";
                    redirect(APP_URL . '/admin/apartamentos_listar.php');
                } else {
                    $_SESSION['error_message'] = "Erro ao atualizar o apartamento.";
                    redirect(APP_URL . '/admin/apartamento_editar.php?id=' . $id);
                }
            } else {
                $id = $_GET['id'] ?? null;
                if ($id) {
                    redirect(APP_URL . '/admin/apartamento_editar.php?id=' . $id);
                } else {
                    $_SESSION['error_message'] = "ID do apartamento não especificado para edição.";
                    redirect(APP_URL . '/admin/apartamentos_listar.php');
                }
            }
            break;

        case 'delete':
             if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm_delete'])) {
                $id = $_POST['id'] ?? $_GET['id'] ?? null;
                if (!$id) {
                    $_SESSION['error_message'] = "ID do apartamento não fornecido para exclusão.";
                    redirect(APP_URL . '/admin/apartamentos_listar.php');
                    exit;
                }
                $apartamento = $apartamentoModel->getById($id); // Para obter o nome/número para a mensagem
                if (!$apartamento) {
                    $_SESSION['error_message'] = "Apartamento não encontrado para exclusão.";
                    redirect(APP_URL . '/admin/apartamentos_listar.php');
                    exit;
                }
                $aptIdentifier = "Apt " . escape_html($apartamento['numero_apartamento']) . " (Hotel: " . escape_html($apartamento['nome_hotel']) . ")";

                if ($apartamentoModel->delete($id)) {
                     if (!isset($_SESSION['error_message'])) {
                        $_SESSION['success_message'] = "Apartamento '{$aptIdentifier}' excluído com sucesso!";
                     }
                } else {
                    if (!isset($_SESSION['error_message'])) {
                        $_SESSION['error_message'] = "Erro ao excluir o apartamento '{$aptIdentifier}'.";
                    }
                }
                redirect(APP_URL . '/admin/apartamentos_listar.php');
            } else {
                $_SESSION['error_message'] = "Ação de exclusão inválida.";
                redirect(APP_URL . '/admin/apartamentos_listar.php');
            }
            break;

        default:
            redirect(APP_URL . '/admin/apartamentos_listar.php');
            break;
    }
} catch (Exception $e) {
    add_log('erro', 'ApartamentoController_Exception', $e->getMessage(), get_logged_in_user_id());
    $_SESSION['error_message'] = "Ocorreu um erro inesperado no sistema de apartamentos.";
    redirect(APP_URL . '/admin/apartamentos_listar.php');
}
?>
