<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Pagamento.php';

// Proteção: Apenas usuários logados com certos níveis podem interagir
// Ajustar níveis conforme necessário para cada ação
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}

$response = ['success' => false, 'message' => 'Ação inválida ou não permitida.'];
$action = $_POST['action'] ?? $_GET['action'] ?? null;

// Apenas admin pode atualizar detalhes de pagamento por enquanto
if (!is_logged_in() || !in_array(get_user_access_level(), ['admin'])) {
    if ($action === 'update_payment_details') { // Só retorna JSON para esta ação específica
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
        exit;
    } else {
        $_SESSION['error_message'] = "Acesso negado.";
        redirect(APP_URL . '/login.php'); // Redireciona para outras ações
    }
}

$pagamentoModel = new Pagamento();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_payment_details') {
    header('Content-Type: application/json');
    $pagamento_id = $_POST['id'] ?? null;
    $novo_status = $_POST['status'] ?? null;
    $forma_pagamento = $_POST['forma_pagamento'] ?? null;
    $data_pagamento_str = $_POST['data_pagamento'] ?? null; // Vem como string do datetime-local
    $descricao = $_POST['descricao'] ?? null;

    if (empty($pagamento_id) || empty($novo_status)) {
        echo json_encode(['success' => false, 'message' => 'ID do pagamento e novo status são obrigatórios.']);
        exit;
    }

    $data_pagamento = null;
    if (!empty($data_pagamento_str)) {
        // Converter para formato Y-m-d H:i:s se necessário
        // O input datetime-local já deve vir em um formato que o strtotime entenda bem
        try {
            $dt = new DateTime($data_pagamento_str);
            $data_pagamento = $dt->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            // Data inválida, não fazer nada ou retornar erro
            // echo json_encode(['success' => false, 'message' => 'Formato de data de pagamento inválido.']);
            // exit;
            // Por ora, se inválido, deixamos como null e o model decide.
        }
    }

    // Limpar strings vazias para null para não sobrescrever desnecessariamente no model
    $forma_pagamento = !empty($forma_pagamento) ? $forma_pagamento : null;
    $descricao = !empty($descricao) ? $descricao : null;


    if ($pagamentoModel->updatePaymentDetails($pagamento_id, $novo_status, $forma_pagamento, $data_pagamento, null, $descricao)) {
        // Adicionar lógica para atualizar status da entidade de origem (Reserva, Pedido, etc.) aqui ou no Model
        // Exemplo: Se o pagamento é de uma reserva e foi para 'pago', atualizar status_pagamento da reserva.
        $pagamento = $pagamentoModel->getById($pagamento_id); // Pega o pagamento atualizado para obter origem
        if ($pagamento && $novo_status === 'pago') {
            if ($pagamento['origem_tipo'] === 'reserva') {
                require_once __DIR__ . '/../models/Reserva.php';
                $reservaModel = new Reserva();
                if ($reservaModel->updateStatusPagamento($pagamento['origem_id'], $novo_status)) { // Passa o novo_status do pagamento
                    // Se pagamento foi 'pago', gerar comissão
                    if ($novo_status === 'pago') {
                        $reserva_details = $reservaModel->getByIdWithDetails($pagamento['origem_id']);
                        if ($reserva_details && isset($reserva_details['apartamento_id'])) {
                            // Precisamos do vendedor_id do apartamento no momento da reserva.
                            // O schema `reservas` não armazena `vendedor_id`.
                            // O `apartamentos` tem `vendedor_id`. Vamos assumir que é o vendedor do apartamento.
                            $apartamentoModel = new Apartamento(); // Incluir require_once no topo se não estiver
                            $apartamento_details = $apartamentoModel->getById($reserva_details['apartamento_id']);

                            if ($apartamento_details && $apartamento_details['vendedor_id']) {
                                require_once __DIR__ . '/../models/ComissaoVendedor.php';
                                $comissaoModel = new ComissaoVendedor();
                                $dados_comissao = [
                                    'vendedor_id' => $apartamento_details['vendedor_id'],
                                    'reserva_id' => $pagamento['origem_id'],
                                    'pagamento_id' => $pagamento_id, // Pagamento que quitou/confirmou
                                    'valor_base_comissao' => $reserva_details['valor_total'],
                                    // percentual e valor_comissao serão calculados no ComissaoVendedorModel
                                ];
                                $comissaoModel->create($dados_comissao);
                                // Não precisa tratar retorno aqui, o create da comissão já loga.
                            }
                        }
                    }
                }
            } elseif ($pagamento['origem_tipo'] === 'pedido') {
                require_once __DIR__ . '/../models/Pedido.php';
                $pedidoModel = new Pedido();
                // Se o pagamento foi 'pago', o status do pedido também deve ser 'pago'.
                // Se foi 'cancelado' e o pedido estava 'pago', o pedido pode voltar para 'fechado'.
                $status_pedido_correspondente = $novo_status; // Simplificação inicial
                if ($novo_status === 'cancelado' || $novo_status === 'falhou' || $novo_status === 'reembolsado') {
                    $status_pedido_correspondente = 'fechado'; // Ou 'aberto' se permitir reabrir
                }
                $pedidoModel->updateStatus($pagamento['origem_id'], $status_pedido_correspondente);
            }
            // Adicionar lógica para 'estacionamento' se estacionamentos tiverem um status de pagamento a ser atualizado
        }

        echo json_encode(['success' => true, 'message' => 'Status do pagamento atualizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => $_SESSION['error_message'] ?? 'Erro ao atualizar o status do pagamento.']);
        unset($_SESSION['error_message']);
    }
    exit;
} else if ($action) {
    // Lidar com outras ações GET/POST se houver (ex: visualização de um pagamento específico em uma página dedicada)
    // Por enquanto, a listagem é a principal interface.
     $_SESSION['error_message'] = "Ação desconhecida para pagamentos.";
    redirect(APP_URL . '/admin/pagamentos_listar.php');
}


// Se nenhuma ação AJAX POST válida, e não outras ações, redireciona para a listagem
// Ajuste: A ação get_duplicatas_pendentes é GET
if ($action === 'get_duplicatas_pendentes' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    $tipo_origem = $_GET['tipo_origem'] ?? null;
    $data = [];
    $success = false;

    // Proteção de acesso para esta ação AJAX específica
    if (!is_logged_in() || !in_array(get_user_access_level(), ['admin'])) {
        echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
        exit;
    }

    if ($tipo_origem) {
        require_once __DIR__ . '/../models/Reserva.php';
        require_once __DIR__ . '/../models/Pedido.php';
        require_once __DIR__ . '/../models/Estacionamento.php';
        // InquilinoModel já instanciado em pagamentos_listar.php, mas se precisar aqui, incluir.

        switch ($tipo_origem) {
            case 'reserva':
                $reservaModel = new Reserva();
                $todasReservas = $reservaModel->getAllWithDetails();
                foreach($todasReservas as $res) {
                    if (in_array($res['status_pagamento_atual'], ['pendente', 'parcial'])) {
                        $data[] = [
                            'id' => $res['id'],
                            'descricao_completa' => "Reserva Evento: {$res['nome_evento']} - Apto: {$res['numero_apartamento']} ({$res['nome_hotel']}) - Inquilino: {$res['nome_inquilino']} - Valor: R$ ".number_format($res['valor_total'],2,',','.'),
                            'valor_pendente' => $res['valor_total'],
                            'inquilino_id' => $res['inquilino_id'] ?? null,
                            'nome_inquilino' => $res['nome_inquilino'] ?? null
                        ];
                    }
                }
                $success = true;
                break;
            case 'pedido':
                $pedidoModel = new Pedido();
                $todosPedidos = $pedidoModel->getAllWithDetails(['status' => 'fechado']);
                 foreach($todosPedidos as $ped) {
                     $pagamentosDoPedido = $pagamentoModel->getByOrigem('pedido', $ped['id']);
                     $aindaPendenteReal = true; // Assume pendente até achar um pago
                     $valorJaPago = 0;
                     if (!empty($pagamentosDoPedido)) {
                         foreach($pagamentosDoPedido as $pg) {
                             if ($pg['status'] === 'pago') {
                                 $valorJaPago += $pg['valor'];
                             }
                         }
                     }
                     $valorPendenteCalculado = $ped['valor_total_calculado'] - $valorJaPago;

                     if ($valorPendenteCalculado > 0) { // Só lista se ainda há valor pendente
                        $data[] = [
                            'id' => $ped['id'],
                            'descricao_completa' => "Pedido ID: {$ped['id']} - Inquilino: {$ped['nome_inquilino']} - Total: R$ ".number_format($ped['valor_total_calculado'],2,',','.')." (Pendente: R$ ".number_format($valorPendenteCalculado,2,',','.').")",
                            'valor_pendente' => $valorPendenteCalculado,
                            'inquilino_id' => $ped['inquilino_id'] ?? null,
                            'nome_inquilino' => $ped['nome_inquilino'] ?? null
                        ];
                     }
                }
                $success = true;
                break;
            case 'estacionamento':
                $estacionamentoModel = new Estacionamento();
                $todosEst = $estacionamentoModel->getAllWithDetails(['status_veiculo' => 'saiu']);
                foreach($todosEst as $est) {
                    if ($est['pagamento_id'] && $est['status_pagamento'] === 'pendente') {
                         $pagOrig = $pagamentoModel->getById($est['pagamento_id']);
                         if ($pagOrig) {
                            $data[] = [
                                'id' => $est['id'], // ID do registro de estacionamento
                                'descricao_completa' => "Estacionamento Placa: {$est['placa']} - Hotel: {$est['nome_hotel']} - Inquilino: {$est['nome_inquilino']} - Valor: R$ ".number_format($pagOrig['valor'],2,',','.'),
                                'valor_pendente' => $pagOrig['valor'],
                                'inquilino_id' => $est['inquilino_id'] ?? null,
                                'nome_inquilino' => $est['nome_inquilino'] ?? null
                            ];
                         }
                    }
                }
                $success = true;
                break;
        }
    }
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $success ? '' : 'Tipo de origem inválido ou não implementado para busca de duplicatas.']);
    exit;

} else if ($action === 'vincular_pagamento_orfao' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    // Proteção de acesso para esta ação específica
    if (!is_logged_in() || !in_array(get_user_access_level(), ['admin'])) {
        echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
        exit;
    }

    $pagamento_id_a_vincular = $_POST['pagamento_id'] ?? null; // Renomeado para clareza
    $vincular_inquilino_id = $_POST['vincular_inquilino_id'] ?? null;
    $vincular_tipo_origem = $_POST['vincular_tipo_origem'] ?? null;
    $vincular_origem_id = $_POST['vincular_origem_id'] ?? null;
    $confirmar_baixa_str = $_POST['confirmar_baixa'] ?? 'false';
    $confirmar_baixa = ($confirmar_baixa_str === 'true' || $confirmar_baixa_str === true || $confirmar_baixa_str === 'on' || $confirmar_baixa_str === 1);
    $observacoes_vinculo = $_POST['observacoes'] ?? '';

    if (!$pagamento_id_a_vincular || (!$vincular_inquilino_id && !$vincular_origem_id)) {
        echo json_encode(['success' => false, 'message' => 'ID do Pagamento e ao menos um tipo de vínculo (Inquilino ou Duplicata) são obrigatórios.']);
        exit;
    }
    if ($vincular_origem_id && !$vincular_tipo_origem) {
        echo json_encode(['success' => false, 'message' => 'Tipo de duplicata é obrigatório se uma duplicata específica for selecionada.']);
        exit;
    }

    $pagamento_a_vincular_obj = $pagamentoModel->getById($pagamento_id_a_vincular);
    if (!$pagamento_a_vincular_obj) {
        echo json_encode(['success' => false, 'message' => 'Pagamento a ser vinculado não encontrado.']);
        exit;
    }

    $update_fields_pagamento = [];
    $log_messages = [];
    $mensagem_sucesso_final = "Vínculo realizado.";

    // 1. Atualizar Inquilino ID no pagamento, se fornecido
    if ($vincular_inquilino_id) {
        $update_fields_pagamento['inquilino_id'] = $vincular_inquilino_id;
        $log_messages[] = "Vinculado ao Inquilino ID: {$vincular_inquilino_id}.";
    }

    // 2. Atualizar Origem ID e Tipo no pagamento, se fornecido
    if ($vincular_origem_id && $vincular_tipo_origem) {
        $update_fields_pagamento['origem_id'] = $vincular_origem_id;
        $update_fields_pagamento['origem_tipo'] = $vincular_tipo_origem;
        $log_messages[] = "Vinculado à Origem: {$vincular_tipo_origem} ID: {$vincular_origem_id}.";

        // 3. Se "confirmar baixa" estiver marcado E o pagamento original estiver pendente E o valor for compatível
        if ($confirmar_baixa && $pagamento_a_vincular_obj['status'] === 'pendente') {
            // Aqui, a lógica de compatibilidade de valor seria importante.
            // Por simplicidade, se o admin marcou, vamos tentar baixar.
            // O valor do pagamento órfão é $pagamento_a_vincular_obj['valor']
            // O valor da duplicata precisaria ser buscado para comparação exata.
            // Para agora, vamos assumir que o admin verificou visualmente ou o valor é o mesmo.

            $update_fields_pagamento['status'] = 'pago';
            $update_fields_pagamento['data_pagamento'] = date('Y-m-d H:i:s'); // Data do vínculo/baixa
            // A forma_pagamento do pagamento órfão é mantida, ou pode ser sobrescrita se necessário.
            // $update_fields_pagamento['forma_pagamento'] = $pagamento_a_vincular_obj['forma_pagamento'] ?? 'outro';
            $log_messages[] = "Status alterado para PAGO e baixa aplicada na duplicata.";
            $mensagem_sucesso_final = "Pagamento vinculado e duplicata baixada com sucesso!";

            // Atualizar status da entidade de origem vinculada
            if ($vincular_tipo_origem === 'reserva') {
                require_once __DIR__ . '/../models/Reserva.php';
                $reservaModel = new Reserva();
                $reservaModel->updateStatusPagamento($vincular_origem_id, 'pago');
            } elseif ($vincular_tipo_origem === 'pedido') {
                require_once __DIR__ . '/../models/Pedido.php';
                $pedidoModel = new Pedido();
                $pedidoModel->updateStatus($vincular_origem_id, 'pago');
            } elseif ($vincular_tipo_origem === 'estacionamento') {
                 // Para estacionamento, o pagamento_id já está vinculado ao checkout.
                 // A baixa do pagamento aqui já reflete o pagamento do estacionamento.
                 // Poderia haver um status no próprio registro de estacionamento se necessário.
            }
        }
    }

    if (!empty($observacoes_vinculo)) {
        $update_fields_pagamento['descricao'] = trim(($pagamento_a_vincular_obj['descricao'] ?? '') . " | Vínculo Manual: " . $observacoes_vinculo);
    }

    $success_operacao = false;
    if (!empty($update_fields_pagamento)) {
        // Precisamos de um método no PagamentoModel para update genérico ou adaptar o updatePaymentDetails.
        // Vou criar um método updateGenerico no PagamentoModel para isso.
        if ($pagamentoModel->updateGeneric($pagamento_id_a_vincular, $update_fields_pagamento)) {
            add_log('info', 'pagamento_vinculado_manualmente', "Pagamento ID {$pagamento_id_a_vincular} vinculado. Detalhes: " . implode(" ", $log_messages), $logged_in_user_id);
            $success_operacao = true;
        } else {
            $mensagem_sucesso_final = 'Erro ao atualizar os dados do pagamento durante o vínculo.';
        }
    } else {
        // Nenhum campo para atualizar diretamente no pagamento, mas o log de vínculo ainda é útil se apenas observações foram adicionadas
        // ou se a intenção era apenas registrar a análise do admin.
        // No entanto, a validação inicial já impede isso se nem inquilino nem origem forem selecionados.
        $mensagem_sucesso_final = "Nenhuma alteração de vínculo de ID foi realizada, mas observações podem ter sido salvas se o model as tratar.";
        // Para realmente salvar só observações, o PagamentoModel->updateGeneric precisaria ser chamado só com a descrição.
    }

    if ($success_operacao) {
        echo json_encode(['success' => true, 'message' => $mensagem_sucesso_final]);
    } else {
        echo json_encode(['success' => false, 'message' => $_SESSION['error_message'] ?? $mensagem_sucesso_final]);
        unset($_SESSION['error_message']);
    }
    exit;
}


// Se nenhuma ação AJAX POST/GET válida, e não outras ações, redireciona para a listagem
if (!($action === 'get_duplicatas_pendentes' && $_SERVER['REQUEST_METHOD'] === 'GET') &&
    !($action === 'vincular_pagamento_orfao' && $_SERVER['REQUEST_METHOD'] === 'POST') &&
    !($action === 'update_payment_details' && $_SERVER['REQUEST_METHOD'] === 'POST')) {
    redirect(APP_URL . '/admin/pagamentos_listar.php');
}

?>
