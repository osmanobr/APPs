<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/ImportacaoCSV.php';
require_once __DIR__ . '/../models/Pagamento.php'; // Para buscar e atualizar pagamentos

// Apenas Admin pode importar CSV
if (session_status() == PHP_SESSION_NONE) {
    start_secure_session();
}
if (!is_logged_in() || get_user_access_level() !== 'admin') {
    $_SESSION['error_message'] = "Acesso negado.";
    redirect(APP_URL . '/login.php');
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$importacaoCSVModel = new ImportacaoCSV();
$pagamentoModel = new Pagamento();
$logged_in_user_id = get_logged_in_user_id();

// Define o diretório de uploads. Certifique-se de que este diretório existe e tem permissão de escrita.
define('UPLOAD_DIR_CSV', __DIR__ . '/../uploads/csv/');
if (!is_dir(UPLOAD_DIR_CSV)) {
    mkdir(UPLOAD_DIR_CSV, 0775, true); // Tenta criar se não existir
}


try {
    switch ($action) {
        case 'upload_e_processar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['arquivo_csv']) && $_FILES['arquivo_csv']['error'] == UPLOAD_ERR_OK) {
                    $nome_arquivo_original = basename($_FILES['arquivo_csv']['name']);
                    $extensao_arquivo = strtolower(pathinfo($nome_arquivo_original, PATHINFO_EXTENSION));

                    if ($extensao_arquivo !== 'csv') {
                        $_SESSION['error_message'] = "Formato de arquivo inválido. Apenas arquivos .csv são permitidos.";
                        redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
                        exit;
                    }

                    // Gerar um nome de arquivo único para evitar sobrescrever
                    // $nome_arquivo_servidor = uniqid('importacao_', true) . '.' . $extensao_arquivo;
                    // $caminho_arquivo_servidor = UPLOAD_DIR_CSV . $nome_arquivo_servidor;

                    // Por simplicidade, vamos usar o nome original, mas em produção, um nome único é melhor.
                    // E o arquivo será processado e depois talvez removido ou arquivado.
                    // Para este exemplo, vamos processar diretamente do arquivo temporário.
                    $caminho_arquivo_temporario = $_FILES['arquivo_csv']['tmp_name'];

                    // Abrir e ler o arquivo CSV
                    $linhas_sucesso = 0;
                    $linhas_falha = 0;
                    $total_linhas_dados = 0;
                    $linhas_processadas = []; // Para armazenar detalhes de cada linha

                    if (($handle = fopen($caminho_arquivo_temporario, "r")) !== FALSE) {
                        $cabecalho = fgetcsv($handle, 1000, ","); // Lê o cabeçalho

                        // Validar cabeçalho esperado: Data,Valor,Identificador,Descrição
                        $cabecalho_esperado = ['Data', 'Valor', 'Identificador', 'Descrição'];
                        if (!$cabecalho || array_map('trim', $cabecalho) !== $cabecalho_esperado) {
                             $_SESSION['error_message'] = "Cabeçalho do CSV inválido. Esperado: Data,Valor,Identificador,Descrição";
                             fclose($handle);
                             redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
                             exit;
                        }

                        $num_linha_csv = 1; // Começa em 1 após o cabeçalho
                        while (($linha_dados = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            if (count($linha_dados) < 3) { // Precisa de pelo menos Data, Valor, Identificador
                                $linhas_processadas[] = [
                                    'linha_csv' => $num_linha_csv,
                                    'status_baixa' => 'falha',
                                    'mensagem_erro' => 'Linha com colunas insuficientes.',
                                    'dados_linha_originais' => json_encode($linha_dados)
                                ];
                                $linhas_falha++;
                                $total_linhas_dados++;
                                $num_linha_csv++;
                                continue;
                            }
                            $total_linhas_dados++;

                            $data_csv = trim($linha_dados[0]);
                            $valor_csv_str = trim($linha_dados[1]);
                            $identificador_csv = trim($linha_dados[2]);
                            $descricao_csv = trim($linha_dados[3] ?? '');

                            // Normalizar valor: remover pontos de milhar, trocar vírgula por ponto decimal
                            $valor_csv_num = str_replace('.', '', $valor_csv_str); // Remove pontos de milhar
                            $valor_csv_num = (float) str_replace(',', '.', $valor_csv_num); // Troca vírgula por ponto e converte para float

                            $detalhe_linha = [
                                'linha_csv' => $num_linha_csv,
                                'identificador_pagamento_csv' => $identificador_csv,
                                'valor_baixado_csv' => $valor_csv_num, // Já convertido para float
                                'data_pagamento_csv' => $data_csv, // Model vai formatar
                                'status_baixa' => 'falha', // Default
                                'pagamento_id' => null,
                                'mensagem_erro' => '',
                                'dados_linha_originais' => json_encode($linha_dados)
                            ];

                            if ($valor_csv_num <= 0) { // Ignorar valores negativos ou zero para baixa de recebimento
                                $detalhe_linha['status_baixa'] = 'ignorado';
                                $detalhe_linha['mensagem_erro'] = 'Valor não positivo, ignorado para baixa de recebimento.';
                                // Não conta como falha, mas também não como sucesso de baixa.
                                $linhas_processadas[] = $detalhe_linha;
                                $num_linha_csv++;
                                continue;
                            }

                            // Tentar encontrar o pagamento no sistema pelo identificador
                            // Assumindo que o Identificador no CSV é o pagamentos.id
                            $pagamento_sistema = $pagamentoModel->getById($identificador_csv);

                            if (!$pagamento_sistema) {
                                $detalhe_linha['status_baixa'] = 'nao_encontrado';
                                $detalhe_linha['mensagem_erro'] = 'Pagamento não encontrado no sistema com este identificador.';
                                $linhas_falha++;
                            } elseif ($pagamento_sistema['status'] === 'pago') {
                                $detalhe_linha['status_baixa'] = 'ja_baixado';
                                $detalhe_linha['pagamento_id'] = $pagamento_sistema['id'];
                                $detalhe_linha['mensagem_erro'] = 'Pagamento já estava baixado no sistema.';
                                // Não é uma falha de processamento, mas não é um novo sucesso.
                            } elseif ($pagamento_sistema['status'] === 'pendente') {
                                // Tentar dar baixa
                                $data_pag_formatada_model = null;
                                if (!empty($data_csv)) {
                                    try { $dt = DateTime::createFromFormat('d/m/Y', $data_csv); if($dt) $data_pag_formatada_model = $dt->format('Y-m-d H:i:s'); } catch (Exception $e) {}
                                }

                                if ($pagamentoModel->updatePaymentDetails(
                                    $pagamento_sistema['id'],
                                    'pago',
                                    $pagamento_sistema['forma_pagamento'], // Mantém a forma original ou pode pegar do CSV se houver
                                    $data_pag_formatada_model, // Usa a data do CSV
                                    $pagamento_sistema['comprovante_id_externo'], // Mantém
                                    $pagamento_sistema['descricao'] . " | Baixado via CSV: " . $descricao_csv // Concatena descrições
                                )) {
                                    $detalhe_linha['status_baixa'] = 'sucesso';
                                    $detalhe_linha['pagamento_id'] = $pagamento_sistema['id'];
                                    $linhas_sucesso++;

                                    // Atualizar status da entidade de origem (Reserva, Pedido)
                                    if ($pagamento_sistema['origem_tipo'] === 'reserva') {
                                        require_once __DIR__ . '/../models/Reserva.php';
                                        $reservaModel = new Reserva();
                                        $reservaModel->updateStatusPagamento($pagamento_sistema['origem_id'], 'pago');
                                    } elseif ($pagamento_sistema['origem_tipo'] === 'pedido') {
                                        require_once __DIR__ . '/../models/Pedido.php';
                                        $pedidoModel = new Pedido();
                                        $pedidoModel->updateStatus($pagamento_sistema['origem_id'], 'pago');
                                    }
                                    // Adicionar para estacionamento se necessário
                                } else {
                                    $detalhe_linha['status_baixa'] = 'falha';
                                    $detalhe_linha['pagamento_id'] = $pagamento_sistema['id'];
                                    $detalhe_linha['mensagem_erro'] = 'Falha ao atualizar o status do pagamento no sistema.';
                                    $linhas_falha++;
                                }
                            } else { // Status diferente de pendente ou pago (ex: cancelado)
                                 $detalhe_linha['status_baixa'] = 'ignorado';
                                 $detalhe_linha['pagamento_id'] = $pagamento_sistema['id'];
                                 $detalhe_linha['mensagem_erro'] = 'Pagamento no sistema com status "' . $pagamento_sistema['status'] . '", não pode ser baixado.';
                                 // Não conta como falha de processamento.
                            }
                            $linhas_processadas[] = $detalhe_linha;
                            $num_linha_csv++;
                        }
                        fclose($handle);

                        // Registrar a importação e os detalhes
                        if ($total_linhas_dados > 0) {
                            $importacao_id = $importacaoCSVModel->criarRegistroImportacao($nome_arquivo_original, $total_linhas_dados, $logged_in_user_id);
                            if ($importacao_id) {
                                foreach ($linhas_processadas as $detalhe) {
                                    $detalhe['importacao_id'] = $importacao_id;
                                    $importacaoCSVModel->adicionarDetalheBaixa($detalhe);
                                }
                                $status_final_importacao = ($linhas_falha > 0) ? 'concluido_com_erros' : 'concluido';
                                $importacaoCSVModel->finalizarImportacao($importacao_id, $linhas_sucesso, $linhas_falha, $status_final_importacao);
                                $_SESSION['success_message'] = "Arquivo CSV processado. Sucessos: {$linhas_sucesso}, Falhas: {$linhas_falha}.";
                                redirect(APP_URL . '/admin/importacao_csv_status.php?id=' . $importacao_id);
                            } else {
                                $_SESSION['error_message'] = "Erro ao registrar a importação do arquivo no sistema.";
                                redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
                            }
                        } else {
                             $_SESSION['warning_message'] = "Arquivo CSV vazio ou sem linhas de dados válidas.";
                             redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
                        }

                    } else {
                        $_SESSION['error_message'] = "Não foi possível abrir o arquivo CSV.";
                        redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
                    }
                } else {
                    $_SESSION['error_message'] = "Nenhum arquivo enviado ou erro no upload.";
                    redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
                }
            } else {
                $_SESSION['error_message'] = "Método de requisição inválido.";
                redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
            }
            break;

        default:
            redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
            break;
    }
} catch (Exception $e) {
    add_log('erro', 'ImportacaoCSVController_Exception', $e->getMessage(), $logged_in_user_id);
    $_SESSION['error_message'] = "Ocorreu um erro inesperado durante a importação: " . $e->getMessage();
    redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
}
?>
