<?php
$page_title = "Criar Novo Hotel";
require_once __DIR__ . '/../views/partials/header.php';
protect_page('admin');

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
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php" class="nav-link">Hotéis</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="#" class="nav-link active"><?php echo $page_title; ?></a></li>
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
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active"><i class="nav-icon fas fa-hotel"></i><p>Hotéis <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Hotéis</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hotel_criar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Criar Hotel</p></a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-door-open"></i><p>Apartamentos <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Apartamentos</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/apartamento_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Apartamento</p></a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i><p>Inquilinos <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilinos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Inquilinos</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilino_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Inquilino</p></a></li>
            </ul>
          </li>
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
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php">Hotéis</a></li>
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
          <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
              <div class="card-header"><h3 class="card-title">Dados do Novo Hotel</h3></div>
              <form action="<?php echo APP_URL; ?>/controllers/HotelController.php" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="card-body">
                  <div class="form-group">
                    <label for="nome">Nome do Hotel <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                  </div>
                  <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <textarea class="form-control" id="endereco" name="endereco" rows="3" placeholder="Endereço completo do hotel..."></textarea>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Salvar Hotel</button>
                  <a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php" class="btn btn-secondary">Cancelar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<!-- ./wrapper -->
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
</body>
</html>
