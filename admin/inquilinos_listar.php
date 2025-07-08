<?php
$page_title = "Listar Inquilinos";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Inquilino.php';

protect_page(['admin', 'vendedor']); // Admins e Vendedores podem listar

$inquilinoModel = new Inquilino();
$inquilinos = $inquilinoModel->getAll();

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/inquilinos_listar.php" class="nav-link active">Inquilinos</a></li>
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

          <?php if ($user_level === 'admin'): ?>
            <li class="nav-header">ADMINISTRAÇÃO</li>
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
          <?php endif; ?>

          <?php if (in_array($user_level, ['admin', 'vendedor'])): ?>
            <li class="nav-item menu-open"> <!-- Manter aberto o menu de inquilinos -->
                <a href="#" class="nav-link active"><i class="nav-icon fas fa-users"></i><p>Inquilinos <i class="right fas fa-angle-left"></i></p></a>
                <ul class="nav nav-treeview">
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilinos_listar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Listar Inquilinos</p></a></li>
                <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilino_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Inquilino</p></a></li>
                </ul>
            </li>
            <li class="nav-item">
              <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link"> <!-- Supondo que exista -->
                <i class="nav-icon fas fa-book-open"></i><p>Reservas</p>
              </a>
            </li>
          <?php endif; ?>

          <?php if ($user_level === 'admin'): ?>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="nav-link"><i class="nav-icon fas fa-user-cog"></i><p>Gerenciar Usuários</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/logs_listar.php" class="nav-link"><i class="nav-icon fas fa-clipboard-list"></i><p>Logs do Sistema</p></a></li>
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
                <h3 class="card-title">Lista de todos os inquilinos cadastrados</h3>
                <div class="card-tools">
                  <a href="<?php echo APP_URL; ?>/admin/inquilino_criar.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Novo Inquilino</a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($inquilinos)): ?>
                  <div class="alert alert-info">Nenhum inquilino cadastrado ainda.</div>
                <?php else: ?>
                  <table id="inquilinosTable" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Email</th>
                      <th>Telefone</th>
                      <th>Documento</th>
                      <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($inquilinos as $inq): ?>
                    <tr>
                      <td><?php echo escape_html($inq['nome']); ?></td>
                      <td><?php echo escape_html($inq['email'] ?? 'N/A'); ?></td>
                      <td><?php echo escape_html($inq['telefone'] ?? 'N/A'); ?></td>
                      <td><?php echo escape_html($inq['documento'] ?? 'N/A'); ?></td>
                      <td>
                        <a href="<?php echo APP_URL; ?>/admin/inquilino_editar.php?id=<?php echo $inq['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Editar</a>
                        <?php if ($user_level === 'admin'): // Apenas admin pode excluir diretamente pela lista ?>
                        <button class="btn btn-sm btn-danger btn-delete-inquilino" data-id="<?php echo $inq['id']; ?>" data-nome="<?php echo escape_html($inq['nome']); ?>"><i class="fas fa-trash"></i> Excluir</button>
                        <?php endif; ?>
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
  <!-- /.content-wrapper -->

  <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteInquilinoModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir o inquilino "<span id="inquilinoNomeModal"></span>"?
                    <br><small class="text-danger">Atenção: Se houver reservas ou outras dependências associadas a este inquilino, a exclusão pode ser impedida.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="deleteInquilinoForm" action="<?php echo APP_URL; ?>/controllers/InquilinoController.php" method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="inquilinoIdToDelete">
                        <button type="submit" class="btn btn-danger">Excluir</button>
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
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#inquilinosTable').DataTable({"language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"},"order": [[0, "asc"]]});
    <?php if ($user_level === 'admin'): ?>
    $('.btn-delete-inquilino').on('click', function() {
        var inqId = $(this).data('id');
        var inqNome = $(this).data('nome');
        $('#inquilinoIdToDelete').val(inqId);
        $('#inquilinoNomeModal').text(inqNome);
        $('#deleteInquilinoModal').modal('show');
    });
    <?php endif; ?>
});
</script>
</body>
</html>
