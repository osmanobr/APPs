<?php
$page_title = "Importar CSV de Pagamentos";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/ImportacaoCSV.php'; // Para listar importações anteriores

// Apenas Admin pode importar
protect_page('admin');

$importacaoModel = new ImportacaoCSV();
$importacoes_anteriores = $importacaoModel->getAllImportacoes(); // Limitar se necessário

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

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
      <li class="nav-item d-none d-sm-inline-block"><a href="#" class="nav-link active"><?php echo $page_title; ?></a></li>
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
              <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-8 offset-md-2">
            <div class="card card-success">
              <div class="card-header"><h3 class="card-title">Upload de Arquivo CSV para Baixa de Pagamentos</h3></div>
              <form action="<?php echo APP_URL; ?>/controllers/ImportacaoCSVController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_e_processar">
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5>Formato Esperado do CSV</h5>
                        <p>O arquivo CSV deve ter o seguinte cabeçalho na primeira linha: <code>Data,Valor,Identificador,Descrição</code></p>
                        <ul>
                            <li><strong>Data:</strong> Data do pagamento (formato dd/mm/aaaa).</li>
                            <li><strong>Valor:</strong> Valor pago (formato 123,45). Apenas valores positivos serão processados para baixa.</li>
                            <li><strong>Identificador:</strong> O ID do pagamento no sistema (UUID).</li>
                            <li><strong>Descrição:</strong> Descrição da transação no extrato/arquivo.</li>
                        </ul>
                        <p>Linhas com valores não positivos (zero ou negativos) serão ignoradas para baixa.</p>
                    </div>

                  <div class="form-group">
                    <label for="arquivo_csv">Selecione o arquivo CSV <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="arquivo_csv" name="arquivo_csv" accept=".csv" required>
                        <label class="custom-file-label" for="arquivo_csv">Escolher arquivo...</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Importar e Processar</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Histórico de Importações</h3></div>
                    <div class="card-body">
                        <?php if(empty($importacoes_anteriores)): ?>
                            <p>Nenhuma importação realizada anteriormente.</p>
                        <?php else: ?>
                            <table class="table table-sm table-bordered table-hover" id="importacoesTable">
                                <thead>
                                    <tr>
                                        <th>ID Importação</th>
                                        <th>Arquivo</th>
                                        <th>Data Importação</th>
                                        <th>Usuário</th>
                                        <th>Linhas Totais</th>
                                        <th>Sucesso</th>
                                        <th>Falhas</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($importacoes_anteriores as $imp): ?>
                                    <tr>
                                        <td><small><?php echo escape_html($imp['id']); ?></small></td>
                                        <td><?php echo escape_html($imp['nome_arquivo']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($imp['data_importacao'])); ?></td>
                                        <td><?php echo escape_html($imp['nome_usuario'] ?? 'N/A'); ?></td>
                                        <td><?php echo $imp['total_linhas']; ?></td>
                                        <td><?php echo $imp['linhas_processadas_sucesso']; ?></td>
                                        <td><?php echo $imp['linhas_falha']; ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_importacao_classes[$imp['status_importacao']] ?? 'badge-light'; ?>">
                                                <?php echo escape_html($status_importacao_map[$imp['status_importacao']] ?? ucfirst($imp['status_importacao'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo APP_URL; ?>/admin/importacao_csv_status.php?id=<?php echo $imp['id']; ?>" class="btn btn-xs btn-info">
                                                <i class="fas fa-eye"></i> Ver Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
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
<!-- bs-custom-file-input -->
<script src="<?php echo APP_URL; ?>/assets/js/bs-custom-file-input.min.js"></script>
<!-- DataTables (para o histórico) -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
  bsCustomFileInput.init();

  $('#importacoesTable').DataTable({
      "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"},
      "order": [[2, "desc"]] // Ordenar por Data Importação descendente
  });
});
</script>
</body>
</html>
