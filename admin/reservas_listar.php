<?php
$page_title = "Listar Reservas";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Reserva.php';

protect_page(['admin', 'vendedor']);

$reservaModel = new Reserva();
$reservas = $reservaModel->getAllWithDetails();

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

$status_pagamento_classes = [
    'pendente' => 'badge-warning',
    'pago' => 'badge-success',
    'cancelado' => 'badge-danger',
    'parcial' => 'badge-info', // Se aplicável
];
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link active">Reservas</a></li>
    </ul>
    <ul class="navbar-nav ml-auto"><li class="nav-item"><a class="nav-link" href="<?php echo APP_URL; ?>/logout.php" role="button"><i class="fas fa-sign-out-alt"></i> Logout</a></li></ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
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
           <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php" class="nav-link">
              <i class="nav-icon fas fa-dollar-sign"></i><p>Pagamentos</p>
            </a>
          </li>
           <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/admin/comissoes_vendedor_listar.php" class="nav-link">
              <i class="nav-icon fas fa-percent"></i><p>Comissões</p>
            </a>
          </li>

          <li class="nav-header">GESTÃO OPERACIONAL</li>
            <li class="nav-item menu-open"> <!-- Menu de Reservas aberto -->
                <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link active">
                    <i class="nav-icon fas fa-book-open"></i><p>Reservas <i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Listar Reservas</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reserva_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Reserva</p></a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-calendar-alt"></i><p>Eventos <i class="right fas fa-angle-left"></i></p></a>
                <ul class="nav nav-treeview">
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Eventos</p></a></li>
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/evento_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Evento</p></a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-hotel"></i><p>Hotéis <i class="right fas fa-angle-left"></i></p></a>
                <ul class="nav nav-treeview">
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Hotéis</p></a></li>
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hotel_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Hotel</p></a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-door-open"></i><p>Apartamentos <i class="right fas fa-angle-left"></i></p></a>
                <ul class="nav nav-treeview">
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Apartamentos</p></a></li>
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/apartamento_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Apartamento</p></a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Inquilinos <i class="right fas fa-angle-left"></i></p></a>
                <ul class="nav nav-treeview">
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilinos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Inquilinos</p></a></li>
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilino_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Inquilino</p></a></li>
                </ul>
            </li>
             <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/estacionamentos_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-car"></i><p>Estacionamento</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-concierge-bell"></i><p>Pedidos (Bar/Serviços)</p>
                </a>
            </li>

          <?php if ($user_level === 'admin'): ?>
            <li class="nav-header">ADMINISTRAÇÃO DO SISTEMA</li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="nav-link"><i class="nav-icon fas fa-user-cog"></i><p>Gerenciar Usuários</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/logs_listar.php" class="nav-link"><i class="nav-icon fas fa-clipboard-list"></i><p>Logs do Sistema</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/importar_csv_pagamentos.php" class="nav-link"><i class="nav-icon fas fa-file-csv"></i><p>Importar CSV Pag.</p></a></li>
          <?php endif; ?>
          <li class="nav-item"><a href="<?php echo APP_URL; ?>/logout.php" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper. Contains page content -->
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

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de todas as reservas</h3>
                <div class="card-tools">
                  <a href="<?php echo APP_URL; ?>/admin/reserva_criar.php" class="btn btn-success"><i class="fas fa-plus"></i> Nova Reserva</a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($reservas)): ?>
                  <div class="alert alert-info">Nenhuma reserva cadastrada ainda.</div>
                <?php else: ?>
                  <div class="table-responsive">
                  <table id="reservasTable" class="table table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                      <th>ID Reserva</th>
                      <th>Evento</th>
                      <th>Hotel</th>
                      <th>Apto</th>
                      <th>Inquilino</th>
                      <th>Check-in</th>
                      <th>Check-out</th>
                      <th class="text-right">Valor (R$)</th>
                      <th>Status Pag.</th>
                      <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reservas as $res): ?>
                    <tr>
                      <td><small><?php echo escape_html($res['id']); ?></small></td>
                      <td><?php echo escape_html($res['nome_evento']); ?></td>
                      <td><?php echo escape_html($res['nome_hotel']); ?></td>
                      <td><?php echo escape_html($res['numero_apartamento']); ?></td>
                      <td><?php echo escape_html($res['nome_inquilino']); ?></td>
                      <td><?php echo escape_html(date('d/m/Y H:i', strtotime($res['data_checkin']))); ?></td>
                      <td><?php echo escape_html(date('d/m/Y H:i', strtotime($res['data_checkout']))); ?></td>
                      <td class="text-right"><?php echo escape_html(number_format($res['valor_total'], 2, ',', '.')); ?></td>
                      <td>
                        <span class="badge <?php echo $status_pagamento_classes[$res['status_pagamento_atual']] ?? 'badge-light'; ?>">
                          <?php echo strtoupper(escape_html($res['status_pagamento_atual'])); ?>
                        </span>
                      </td>
                      <td>
                        <a href="<?php echo APP_URL; ?>/admin/reserva_editar.php?id=<?php echo $res['id']; ?>" class="btn btn-xs btn-info" title="Editar Reserva"><i class="fas fa-edit"></i></a>
                        <?php if ($res['status_pagamento_atual'] !== 'cancelado'): ?>
                        <button class="btn btn-xs btn-warning btn-cancel-reserva" data-id="<?php echo $res['id']; ?>" data-nome="Reserva de <?php echo escape_html($res['nome_inquilino']); ?> em <?php echo escape_html($res['nome_evento']); ?>" title="Cancelar Reserva"><i class="fas fa-times-circle"></i></button>
                        <?php endif; ?>
                        <!-- Adicionar link para ver pagamento(s) associado(s) -->
                        <a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php?origem_tipo=reserva&origem_id=<?php echo $res['id']; ?>" class="btn btn-xs btn-outline-secondary" title="Ver Pagamentos"><i class="fas fa-dollar-sign"></i></a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

   <!-- Modal de Confirmação de Cancelamento -->
    <div class="modal fade" id="cancelReservaModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Confirmar Cancelamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja cancelar a reserva "<span id="reservaNomeModal"></span>"?
                    <br><small class="text-warning">Atenção: Esta ação marcará a reserva e seus pagamentos pendentes como cancelados.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não, manter reserva</button>
                    <form id="cancelReservaForm" action="<?php echo APP_URL; ?>/controllers/ReservaController.php" method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="cancel">
                        <input type="hidden" name="id" id="reservaIdToCancel">
                        <button type="submit" class="btn btn-warning">Sim, Cancelar Reserva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<!-- ./wrapper -->
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    $('#reservasTable').DataTable({
        "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"},
        "order": [[5, "desc"]], // Ordenar por check-in descendente
        "responsive": true,
         "columnDefs": [
            { "responsivePriority": 1, "targets": 0 }, // ID
            { "responsivePriority": 2, "targets": 4 }, // Inquilino
            { "responsivePriority": 3, "targets": 8 }, // Status Pag.
            { "responsivePriority": 4, "targets": 9 }  // Ações
        ]
    });

    $('.btn-cancel-reserva').on('click', function() {
        var reservaId = $(this).data('id');
        var reservaNome = $(this).data('nome');
        $('#reservaIdToCancel').val(reservaId);
        $('#reservaNomeModal').text(reservaNome);
        $('#cancelReservaModal').modal('show');
    });
});
</script>
</body>
</html>
