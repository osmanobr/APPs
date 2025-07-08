<?php
$page_title = "Status da Importação CSV";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/ImportacaoCSV.php';

protect_page('admin');

$importacao_id = $_GET['id'] ?? null;
if (!$importacao_id) {
    $_SESSION['error_message'] = "ID da importação não fornecido.";
    redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
    exit;
}

$importacaoModel = new ImportacaoCSV();
$importacao_info = $importacaoModel->getImportacaoById($importacao_id);

if (!$importacao_info) {
    $_SESSION['error_message'] = "Importação não encontrada.";
    redirect(APP_URL . '/admin/importar_csv_pagamentos.php');
    exit;
}

$detalhes_importacao = $importacaoModel->getDetalhesByImportacaoId($importacao_id);

$page_title .= " - " . escape_html($importacao_info['nome_arquivo']);
$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

$status_baixa_map = [
    'sucesso' => 'Sucesso',
    'falha' => 'Falha',
    'nao_encontrado' => 'Pag. Não Encontrado',
    'ja_baixado' => 'Pag. Já Baixado',
    'ignorado' => 'Ignorado (Ex: valor <=0)'
];
$status_baixa_classes = [
    'sucesso' => 'badge-success',
    'falha' => 'badge-danger',
    'nao_encontrado' => 'badge-warning',
    'ja_baixado' => 'badge-info',
    'ignorado' => 'badge-secondary'
];
$status_importacao_map = [
    'pendente' => 'Pendente',
    'processando' => 'Processando',
    'concluido' => 'Concluído',
    'concluido_com_erros' => 'Concluído com Erros',
    'falhou' => 'Falhou'
];
$status_importacao_classes = [
    'pendente' => 'badge-secondary',
    'processando' => 'badge-info',
    'concluido' => 'badge-success',
    'concluido_com_erros' => 'badge-warning',
    'falhou' => 'badge-danger'
];
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/importar_csv_pagamentos.php" class="nav-link">Importar CSV</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="#" class="nav-link active">Status da Importação</a></li>
    </ul>
    <ul class="navbar-nav ml-auto"><li class="nav-item"><a class="nav-link" href="<?php echo APP_URL; ?>/logout.php" role="button"><i class="fas fa-sign-out-alt"></i> Logout</a></li></ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="brand-link">
      <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo escape_html(SITE_NAME); ?></span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image"><img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"></div>
        <div class="info"><a href="#" class="d-block"><?php echo $user_name; ?></a><span class="text-muted"><?php echo ucfirst($user_level); ?></span></div>
      </div>
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
            <li class="nav-header">GESTÃO FINANCEIRA</li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php" class="nav-link"><i class="nav-icon fas fa-dollar-sign"></i><p>Pagamentos</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/comissoes_vendedor_listar.php" class="nav-link"><i class="nav-icon fas fa-percent"></i><p>Comissões</p></a></li>

            <li class="nav-header">GESTÃO OPERACIONAL</li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link"><i class="nav-icon fas fa-book-open"></i><p>Reservas</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/estacionamentos_listar.php" class="nav-link"><i class="nav-icon fas fa-car"></i><p>Estacionamento</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link"><i class="nav-icon fas fa-concierge-bell"></i><p>Pedidos</p></a></li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-calendar-alt"></i><p>Eventos <i class="right fas fa-angle-left"></i></p></a>
                <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Eventos</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/evento_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Evento</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/evento_checkin_participante.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Check-in em Evento</p></a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php" class="nav-link"><i class="nav-icon fas fa-hotel"></i><p>Hotéis</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php" class="nav-link"><i class="nav-icon fas fa-door-open"></i><p>Apartamentos</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilinos_listar.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Inquilinos</p></a></li>

            <?php if ($user_level === 'admin'): ?>
            <li class="nav-header">ADMINISTRAÇÃO DO SISTEMA</li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="nav-link"><i class="nav-icon fas fa-user-cog"></i><p>Gerenciar Usuários</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/logs_listar.php" class="nav-link"><i class="nav-icon fas fa-clipboard-list"></i><p>Logs do Sistema</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/importar_csv_pagamentos.php" class="nav-link active"><i class="nav-icon fas fa-file-csv"></i><p>Importar CSV Pag.</p></a></li>
            <?php endif; ?>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/logout.php" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1><?php echo $page_title; ?></h1></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/importar_csv_pagamentos.php">Importar CSV</a></li>
              <li class="breadcrumb-item active">Status da Importação</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resumo da Importação</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">ID da Importação:</dt>
                    <dd class="col-sm-9"><?php echo escape_html($importacao_info['id']); ?></dd>

                    <dt class="col-sm-3">Nome do Arquivo:</dt>
                    <dd class="col-sm-9"><?php echo escape_html($importacao_info['nome_arquivo']); ?></dd>

                    <dt class="col-sm-3">Data da Importação:</dt>
                    <dd class="col-sm-9"><?php echo date('d/m/Y H:i:s', strtotime($importacao_info['data_importacao'])); ?></dd>

                    <dt class="col-sm-3">Importado por:</dt>
                    <dd class="col-sm-9"><?php echo escape_html($importacao_info['nome_usuario'] ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Status Geral:</dt>
                    <dd class="col-sm-9">
                        <span class="badge <?php echo $status_importacao_classes[$importacao_info['status_importacao']] ?? 'badge-light'; ?>">
                            <?php echo escape_html($status_importacao_map[$importacao_info['status_importacao']] ?? ucfirst($importacao_info['status_importacao'])); ?>
                        </span>
                    </dd>

                    <dt class="col-sm-3">Total de Linhas de Dados no Arquivo:</dt>
                    <dd class="col-sm-9"><?php echo $importacao_info['total_linhas']; ?></dd>

                    <dt class="col-sm-3">Linhas Processadas com Sucesso (Baixas Realizadas):</dt>
                    <dd class="col-sm-9 text-success"><strong><?php echo $importacao_info['linhas_processadas_sucesso']; ?></strong></dd>

                    <dt class="col-sm-3">Linhas com Falha no Processamento:</dt>
                    <dd class="col-sm-9 text-danger"><strong><?php echo $importacao_info['linhas_falha']; ?></strong></dd>
                </dl>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Detalhes do Processamento por Linha</h3></div>
                    <div class="card-body">
                        <?php if(empty($detalhes_importacao)): ?>
                            <p>Nenhum detalhe de processamento encontrado para esta importação (ou o processamento ainda não ocorreu).</p>
                        <?php else: ?>
                            <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover" id="detalhesImportacaoTable">
                                <thead>
                                    <tr>
                                        <th>Linha CSV</th>
                                        <th>Identificador CSV</th>
                                        <th>Valor CSV</th>
                                        <th>Data Pag. CSV</th>
                                        <th>Status Baixa</th>
                                        <th>Pagamento ID (Sistema)</th>
                                        <th>Mensagem/Erro</th>
                                        <!-- <th>Dados Originais</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($detalhes_importacao as $det): ?>
                                    <tr>
                                        <td><?php echo $det['linha_csv']; ?></td>
                                        <td><?php echo escape_html($det['identificador_pagamento_csv']); ?></td>
                                        <td class="text-right"><?php echo number_format($det['valor_baixado_csv'] ?? 0, 2, ',', '.'); ?></td>
                                        <td><?php echo $det['data_pagamento_csv'] ? date('d/m/Y', strtotime($det['data_pagamento_csv'])) : 'N/A'; ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_baixa_classes[$det['status_baixa']] ?? 'badge-light'; ?>">
                                                <?php echo escape_html($status_baixa_map[$det['status_baixa']] ?? ucfirst($det['status_baixa'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($det['pagamento_id']): ?>
                                                <a href="<?php echo APP_URL . '/admin/pagamentos_listar.php?id_pag=' . $det['pagamento_id']; // Link para o pagamento se existir ?>" target="_blank">
                                                    <small><?php echo escape_html($det['pagamento_id']); ?></small>
                                                </a>
                                            <?php else: echo 'N/A'; endif; ?>
                                        </td>
                                        <td><small><?php echo escape_html($det['mensagem_erro'] ?? ''); ?></small></td>
                                        <!-- <td><small><?php // echo escape_html($det['dados_linha_originais']); ?></small></td> -->
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo APP_URL; ?>/admin/importar_csv_pagamentos.php" class="btn btn-secondary">Voltar para Importações</a>
                    </div>
                </div>
            </div>
        </div>

      </div>
    </section>
  </div>

  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
  $('#detalhesImportacaoTable').DataTable({
      "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"},
      "order": [[0, "asc"]], // Ordenar por Linha CSV
      "pageLength": 50 // Mostrar mais linhas por padrão
  });
});
</script>
</body>
</html>
