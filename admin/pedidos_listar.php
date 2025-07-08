<?php
$page_title = "Listar Pedidos";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Inquilino.php'; // Para filtro

// Níveis de acesso: admin, funcionario, vendedor
protect_page(['admin', 'funcionario', 'vendedor']);

$pedidoModel = new Pedido();
$inquilinoModel = new Inquilino(); // Para popular filtro

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

// Filtros
$filtro_inquilino_id = $_GET['inquilino_id'] ?? '';
$filtro_status_pedido = $_GET['status_pedido'] ?? '';

$filtros_aplicados = [];
if ($filtro_inquilino_id) $filtros_aplicados['inquilino_id'] = $filtro_inquilino_id;
if ($filtro_status_pedido) $filtros_aplicados['status'] = $filtro_status_pedido;


$pedidos = $pedidoModel->getAllWithDetails($filtros_aplicados);
$inquilinos_para_filtro = $inquilinoModel->getAll();

$status_pedido_map = [
    'aberto' => 'Aberto',
    'fechado' => 'Fechado (Aguardando Pag.)',
    'pago' => 'Pago',
    'cancelado' => 'Cancelado'
];
$status_pedido_classes = [
    'aberto' => 'badge-primary',
    'fechado' => 'badge-info',
    'pago' => 'badge-success',
    'cancelado' => 'badge-danger',
];
$status_pagamento_classes = [ // Para o status do pagamento associado
    'pendente' => 'badge-warning',
    'pago' => 'badge-success',
    'cancelado' => 'badge-danger',
];

?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link active">Pedidos</a></li>
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
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-book-open"></i><p>Reservas <i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Reservas</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reserva_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Reserva</p></a></li>
                </ul>
            </li>
             <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/estacionamentos_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-car"></i><p>Estacionamento <i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/estacionamentos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Entradas/Saídas</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/estacionamento_checkin.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Registrar Check-in</p></a></li>
                </ul>
            </li>
            <li class="nav-item menu-open"> <!-- Menu de Pedidos aberto -->
                <a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link active">
                    <i class="nav-icon fas fa-concierge-bell"></i><p>Pedidos <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Listar Pedidos</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/pedido_gerenciar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Novo Pedido</p></a></li>
                </ul>
            </li>
             <li class="nav-item"> <!-- Outros itens do menu -->
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filtros</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="form-inline">
                    <div class="form-group mb-2 mr-sm-2">
                        <label for="filtro_inquilino_id" class="mr-sm-2">Inquilino:</label>
                        <select name="inquilino_id" id="filtro_inquilino_id" class="form-control select2bs4-filter" style="width: 250px;">
                            <option value="">Todos</option>
                            <?php foreach($inquilinos_para_filtro as $inq): ?>
                                <option value="<?php echo $inq['id']; ?>" <?php echo ($filtro_inquilino_id == $inq['id']) ? 'selected' : ''; ?>>
                                    <?php echo escape_html($inq['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-2 mr-sm-2">
                        <label for="filtro_status_pedido" class="mr-sm-2">Status Pedido:</label>
                        <select name="status_pedido" id="filtro_status_pedido" class="form-control select2bs4-filter" style="width: 200px;">
                            <option value="">Todos</option>
                            <?php foreach($status_pedido_map as $key => $value): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($filtro_status_pedido == $key) ? 'selected' : ''; ?>>
                                    <?php echo $value; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-filter"></i> Filtrar</button>
                    <a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="btn btn-secondary mb-2 ml-2"><i class="fas fa-eraser"></i> Limpar</a>
                </form>
            </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de todos os pedidos</h3>
                <div class="card-tools">
                  <a href="<?php echo APP_URL; ?>/admin/pedido_gerenciar.php" class="btn btn-success"><i class="fas fa-plus"></i> Novo Pedido</a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($pedidos)): ?>
                  <div class="alert alert-info">Nenhum pedido encontrado para os filtros aplicados.</div>
                <?php else: ?>
                  <div class="table-responsive">
                  <table id="pedidosTable" class="table table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                      <th>ID Pedido</th>
                      <th>Data Criação</th>
                      <th>Inquilino</th>
                      <th>Funcionário</th>
                      <th class="text-right">Valor Total (R$)</th>
                      <th>Status Pedido</th>
                      <th>Status Pag.</th>
                      <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                      <td><small><?php echo escape_html($pedido['id']); ?></small></td>
                      <td><?php echo escape_html(date('d/m/Y H:i', strtotime($pedido['criado_em']))); ?></td>
                      <td><?php echo escape_html($pedido['nome_inquilino']); ?></td>
                      <td><?php echo escape_html($pedido['nome_funcionario'] ?? 'N/A'); ?></td>
                      <td class="text-right"><?php echo escape_html(number_format($pedido['valor_total_calculado'], 2, ',', '.')); ?></td>
                      <td>
                        <span class="badge <?php echo $status_pedido_classes[$pedido['status']] ?? 'badge-light'; ?>">
                            <?php echo escape_html($status_pedido_map[$pedido['status']] ?? ucfirst($pedido['status'])); ?>
                        </span>
                      </td>
                      <td>
                        <?php if ($pedido['status_pagamento_atual']): ?>
                             <span class="badge <?php echo $status_pagamento_classes[$pedido['status_pagamento_atual']] ?? 'badge-light'; ?>">
                                <?php echo strtoupper(escape_html($pedido['status_pagamento_atual'])); ?>
                            </span>
                        <?php elseif ($pedido['status'] === 'aberto'): echo 'N/A (Aberto)'; ?>
                        <?php elseif ($pedido['status'] === 'pago' && $pedido['valor_total_calculado'] == 0): echo '<span class="badge badge-success">PAGO (R$0)</span>'; ?>
                        <?php else: echo 'N/A'; endif; ?>
                      </td>
                      <td>
                        <a href="<?php echo APP_URL; ?>/admin/pedido_gerenciar.php?id=<?php echo $pedido['id']; ?>" class="btn btn-xs btn-info" title="Gerenciar/Ver Itens">
                            <i class="fas <?php echo ($pedido['status'] == 'aberto' ? 'fa-edit' : 'fa-eye'); ?>"></i> <?php echo ($pedido['status'] == 'aberto' ? 'Gerenciar' : 'Ver'); ?>
                        </a>
                         <?php if ($pedido['status_pagamento_atual'] === 'pendente' && $pedido['status'] !== 'aberto'): ?>
                            <a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php?origem_tipo=pedido&origem_id=<?php echo $pedido['id']; ?>" class="btn btn-xs btn-warning" title="Ver/Registrar Pagamento">
                                <i class="fas fa-dollar-sign"></i> Pagar
                            </a>
                        <?php endif; ?>
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

  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.2/select2-bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#pedidosTable').DataTable({
        "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"},
        "order": [[1, "desc"]], // Ordenar por Data Criação descendente
        "responsive": true,
         "columnDefs": [
            { "responsivePriority": 1, "targets": 0 }, // ID
            { "responsivePriority": 2, "targets": 2 }, // Inquilino
            { "responsivePriority": 3, "targets": 7 }  // Ações
        ]
    });
     $('.select2bs4-filter').select2({
      theme: 'bootstrap4',
      allowClear: true,
      placeholder: "Selecione"
    });
});
</script>
</body>
</html>
