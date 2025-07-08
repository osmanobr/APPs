<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Apartamento.php'; // Para buscar detalhes do apto, se necessário
require_once __DIR__ . '/../models/Pagamento.php'; // Para interagir com pagamentos

// Proteção: Admin e Vendedor podem gerenciar reservas
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
if (!is_logged_in() || !in_array(get_user_access_level(), ['admin', 'vendedor'])) {
    $_SESSION['error_message'] = "Acesso negado.";
    redirect(APP_URL . '/login.php');
}

$action = $_REQUEST['action'] ?? null;
$reservaModel = new Reserva();
$apartamentoModel = new Apartamento(); // Instanciar para buscar valor da diária
$pagamentoModel = new Pagamento();


try {
    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validação básica
                if (empty($_POST['evento_id']) || empty($_POST['apartamento_id']) || empty($_POST['inquilino_id']) || empty($_POST['data_checkin']) || empty($_POST['data_checkout'])) {
                    $_SESSION['error_message'] = "Todos os campos marcados com * são obrigatórios.";
                    // $_SESSION['form_data_reserva'] = $_POST; // Para repopular formulário
                    redirect(APP_URL . '/admin/reserva_criar.php');
                    exit;
                }

                $data_checkin = new DateTime($_POST['data_checkin']);
                $data_checkout = new DateTime($_POST['data_checkout']);

                if ($data_checkout <= $data_checkin) {
                    $_SESSION['error_message'] = "Data de checkout deve ser posterior à data de check-in.";
                    // $_SESSION['form_data_reserva'] = $_POST;
                    redirect(APP_URL . '/admin/reserva_criar.php');
                    exit;
                }

                // Calcular número de diárias
                $intervalo = $data_checkin->diff($data_checkout);
                $num_diarias = $intervalo->days;
                if ($num_diarias <=0) $num_diarias = 1; // Mínimo 1 diária

                // Buscar valor da diária do apartamento
                $apartamento = $apartamentoModel->getById($_POST['apartamento_id']);
                if (!$apartamento) {
                    $_SESSION['error_message'] = "Apartamento selecionado inválido.";
                    redirect(APP_URL . '/admin/reserva_criar.php');
                    exit;
                }
                $valor_total_calculado = $num_diarias * $apartamento['valor_diaria'];

                $dados = [
                    'evento_id' => $_POST['evento_id'],
                    'apartamento_id' => $_POST['apartamento_id'],
                    'inquilino_id' => $_POST['inquilino_id'],
                    'data_checkin' => $data_checkin->format('Y-m-d H:i:s'),
                    'data_checkout' => $data_checkout->format('Y-m-d H:i:s'),
                    'valor_total' => $valor_total_calculado, // Usar o valor calculado
                    // 'status_pagamento' será 'pendente' por padrão no Model
                ];

                // Verificar disponibilidade novamente (server-side)
                $aptos_disponiveis_check = $reservaModel->getApartamentosDisponiveis($dados['data_checkin'], $dados['data_checkout'], null, $apartamento['hotel_id']);
                $is_still_available = false;
                foreach($aptos_disponiveis_check as $apt_disp){
                    if($apt_disp['id'] == $dados['apartamento_id']){
                        $is_still_available = true;
                        break;
                    }
                }
                if(!$is_still_available){
                    $_SESSION['error_message'] = "Desculpe, o apartamento selecionado não está mais disponível para o período escolhido. Por favor, verifique novamente.";
                    redirect(APP_URL . '/admin/reserva_criar.php');
                    exit;
                }


                $reserva_id = $reservaModel->create($dados);
                if ($reserva_id) {
                    // Registrar no histórico de responsáveis (o inquilino principal da reserva é o primeiro responsável)
                    // Esta lógica pode ser movida para dentro do ReservaModel->create se for sempre assim
                    require_once __DIR__ . '/../models/HistoricoResponsavelReserva.php';
                    $histRespModel = new HistoricoResponsavelReserva();
                    $histRespModel->addEntry([
                        'reserva_id' => $reserva_id,
                        'responsavel_id' => $dados['inquilino_id'], // Assumindo que o inquilino da reserva é o responsável
                        'tipo_responsavel' => 'inquilino', // Ou 'usuario_sistema' se o $dados['inquilino_id'] for de um usuário
                        'alterado_por_id' => get_logged_in_user_id(),
                        'motivo_alteracao' => 'Criação da reserva'
                    ]);

                    $_SESSION['success_message'] = "Reserva criada com sucesso! Um pagamento pendente foi gerado.";
                    redirect(APP_URL . '/admin/reservas_listar.php');
                } else {
                    // A mensagem de erro específica é definida no Model
                     if (!isset($_SESSION['error_message'])) {
                        $_SESSION['error_message'] = "Erro ao criar a reserva.";
                     }
                    // $_SESSION['form_data_reserva'] = $_POST;
                    redirect(APP_URL . '/admin/reserva_criar.php');
                }
            } else {
                redirect(APP_URL . '/admin/reserva_criar.php');
            }
            break;

        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                if (empty($id) || empty($_POST['evento_id']) || empty($_POST['apartamento_id']) || empty($_POST['inquilino_id']) || empty($_POST['data_checkin']) || empty($_POST['data_checkout'])) {
                    $_SESSION['error_message'] = "Todos os campos marcados com * são obrigatórios para edição.";
                    redirect(APP_URL . ( $id ? '/admin/reserva_editar.php?id=' . $id : '/admin/reservas_listar.php'));
                    exit;
                }

                $data_checkin = new DateTime($_POST['data_checkin']);
                $data_checkout = new DateTime($_POST['data_checkout']);

                if ($data_checkout <= $data_checkin) {
                    $_SESSION['error_message'] = "Data de checkout deve ser posterior à data de check-in.";
                    redirect(APP_URL . '/admin/reserva_editar.php?id=' . $id);
                    exit;
                }

                $intervalo = $data_checkin->diff($data_checkout);
                $num_diarias = $intervalo->days;
                if ($num_diarias <=0) $num_diarias = 1;

                $apartamento = $apartamentoModel->getById($_POST['apartamento_id']);
                if (!$apartamento) {
                    $_SESSION['error_message'] = "Apartamento selecionado inválido.";
                     redirect(APP_URL . '/admin/reserva_editar.php?id=' . $id);
                    exit;
                }
                $valor_total_calculado = $num_diarias * $apartamento['valor_diaria'];

                $dados_update = [
                    'evento_id' => $_POST['evento_id'],
                    'apartamento_id' => $_POST['apartamento_id'],
                    'inquilino_id' => $_POST['inquilino_id'],
                    'data_checkin' => $data_checkin->format('Y-m-d H:i:s'),
                    'data_checkout' => $data_checkout->format('Y-m-d H:i:s'),
                    'valor_total' => $valor_total_calculado,
                    // 'status_pagamento' // Não é atualizado diretamente aqui
                ];

                // Verificar disponibilidade novamente, ignorando a própria reserva
                $aptos_disponiveis_check = $reservaModel->getApartamentosDisponiveis($dados_update['data_checkin'], $dados_update['data_checkout'], null, $apartamento['hotel_id'], $id);
                $is_still_available = false;
                foreach($aptos_disponiveis_check as $apt_disp){
                    if($apt_disp['id'] == $dados_update['apartamento_id']){
                        $is_still_available = true;
                        break;
                    }
                }
                 // Se o apartamento não mudou, ele estará disponível (pois ignoramos a própria reserva)
                $reserva_original = $reservaModel->getByIdWithDetails($id);
                if ($reserva_original && $reserva_original['apartamento_id'] == $dados_update['apartamento_id']) {
                    $is_still_available = true;
                }


                if(!$is_still_available){
                    $_SESSION['error_message'] = "Desculpe, o novo apartamento/período selecionado não está disponível.";
                    redirect(APP_URL . '/admin/reserva_editar.php?id=' . $id);
                    exit;
                }


                if ($reservaModel->update($id, $dados_update)) {
                    // Lógica de histórico de responsável se o inquilino_id mudou
                    if ($reserva_original && $reserva_original['inquilino_id'] != $dados_update['inquilino_id']) {
                        require_once __DIR__ . '/../models/HistoricoResponsavelReserva.php';
                        $histRespModel = new HistoricoResponsavelReserva();
                        $histRespModel->addEntry([
                            'reserva_id' => $id,
                            'responsavel_id' => $dados_update['inquilino_id'],
                            'tipo_responsavel' => 'inquilino', // Ajustar se necessário
                            'alterado_por_id' => get_logged_in_user_id(),
                            'motivo_alteracao' => 'Alteração do inquilino principal na edição da reserva.'
                        ]);
                    }
                    $_SESSION['success_message'] = "Reserva atualizada com sucesso!";
                    if(isset($_SESSION['warning_message'])) { // Se o model setou um warning (ex: valor mudou e pagamento processado)
                        $_SESSION['success_message'] .= " ATENÇÃO: " . $_SESSION['warning_message'];
                        unset($_SESSION['warning_message']);
                    }
                    redirect(APP_URL . '/admin/reservas_listar.php');
                } else {
                    $_SESSION['error_message'] = "Erro ao atualizar a reserva.";
                    redirect(APP_URL . '/admin/reserva_editar.php?id=' . $id);
                }
            } else {
                // GET request para editar, redireciona para a página de edição (que carrega os dados)
                $id = $_GET['id'] ?? null;
                if ($id) {
                    redirect(APP_URL . '/admin/reserva_editar.php?id=' . $id);
                } else {
                    $_SESSION['error_message'] = "ID da reserva não especificado para edição.";
                    redirect(APP_URL . '/admin/reservas_listar.php');
                }
            }
            break;

        case 'cancel':
            // Geralmente por POST para segurança, mas pode ser GET com confirmação
            if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm_cancel'])) {
                $id = $_POST['id'] ?? $_GET['id'] ?? null;
                if (!$id) {
                    $_SESSION['error_message'] = "ID da reserva não fornecido para cancelamento.";
                    redirect(APP_URL . '/admin/reservas_listar.php');
                    exit;
                }
                if ($reservaModel->cancel($id)) {
                    $_SESSION['success_message'] = "Reserva ID {$id} cancelada com sucesso.";
                } else {
                    $_SESSION['error_message'] = "Erro ao cancelar a reserva ID {$id}.";
                }
                redirect(APP_URL . '/admin/reservas_listar.php');
            } else {
                 $_SESSION['error_message'] = "Ação de cancelamento inválida.";
                redirect(APP_URL . '/admin/reservas_listar.php');
            }
            break;

        case 'get_apartamentos_disponiveis': // AJAX
            header('Content-Type: application/json');
            $data_inicio = $_GET['data_inicio'] ?? null;
            $data_fim = $_GET['data_fim'] ?? null;
            $tipo_acomodacao = $_GET['tipo_acomodacao'] ?? null;
            $hotel_id = $_GET['hotel_id'] ?? null;
            $ignore_reserva_id = $_GET['ignore_reserva_id'] ?? null;

            if (!$data_inicio || !$data_fim) {
                echo json_encode(['error' => 'Datas de início e fim são obrigatórias.']);
                exit;
            }
            // Validar formato das datas se necessário
            $apartamentos = $reservaModel->getApartamentosDisponiveis($data_inicio, $data_fim, $tipo_acomodacao, $hotel_id, $ignore_reserva_id);
            echo json_encode($apartamentos);
            exit;


        default:
            redirect(APP_URL . '/admin/reservas_listar.php');
            break;
    }
} catch (Exception $e) {
    add_log('erro', 'ReservaController_Exception', $e->getMessage(), get_logged_in_user_id());
    $_SESSION['error_message'] = "Ocorreu um erro inesperado no sistema de reservas: " . $e->getMessage();
    redirect(APP_URL . '/admin/reservas_listar.php');
}
?>
