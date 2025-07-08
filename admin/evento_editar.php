<?php
$page_title = "Editar Evento";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Evento.php';

protect_page('admin');

$evento_id = $_GET['id'] ?? null;
if (!$evento_id) {
    $_SESSION['error_message'] = "ID do evento não fornecido para edição.";
    redirect(APP_URL . '/admin/eventos_listar.php');
    exit;
}

$eventoModel = new Evento();
$evento = $eventoModel->getById($evento_id);
$organizadores = $eventoModel->getPotenciaisOrganizadores();

if (!$evento) {
    $_SESSION['error_message'] = "Evento não encontrado (ID: " . escape_html($evento_id) . ").";
    redirect(APP_URL . '/admin/eventos_listar.php');
    exit;
}

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

// Formatar datas para o input datetime-local
$data_inicio_formatted = !empty($evento['data_inicio']) ? date('Y-m-d\TH:i', strtotime($evento['data_inicio'])) : '';
$data_fim_formatted = !empty($evento['data_fim']) ? date('Y-m-d\TH:i', strtotime($evento['data_fim'])) : '';

?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="nav-link">Eventos</a></li>
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
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active"><i class="nav-icon fas fa-calendar-alt"></i><p>Eventos <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Listar Eventos</p></a></li>
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
          <div class="col-sm-6"><h1><?php echo $page_title; ?>: <?php echo escape_html($evento['nome']); ?></h1></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php">Eventos</a></li>
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
            <div class="card card-warning"> <!-- Card warning para edição -->
              <div class="card-header"><h3 class="card-title">Dados do Evento</h3></div>
              <!-- /.card-header -->
              <form action="<?php echo APP_URL; ?>/controllers/EventoController.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo escape_html($evento['id']); ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label for="nome">Nome do Evento <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nome" name="nome" required value="<?php echo escape_html($evento['nome']); ?>">
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="data_inicio">Data e Hora de Início <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio" required value="<?php echo $data_inicio_formatted; ?>">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="data_fim">Data e Hora de Fim <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="data_fim" name="data_fim" required value="<?php echo $data_fim_formatted; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="organizador_id">Organizador</label>
                    <select class="form-control" id="organizador_id" name="organizador_id">
                      <option value="">Nenhum / A definir</option>
                      <?php foreach ($organizadores as $organizador): ?>
                        <option value="<?php echo escape_html($organizador['id']); ?>" <?php echo ($evento['organizador_id'] == $organizador['id']) ? 'selected' : ''; ?>>
                          <?php echo escape_html($organizador['nome']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Detalhes sobre o evento..."><?php echo escape_html($evento['descricao']); ?></textarea>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                  <a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="btn btn-secondary">Cancelar</a>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong>
    Todos os direitos reservados.
  </footer>
</div>
<!-- ./wrapper -->
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<script>
$(function () {
  // Validar se data_fim é maior que data_inicio (cliente-side básico)
  $("#data_fim").on("change", function() {
    var startDate = $("#data_inicio").val();
    var endDate = $(this).val();
    if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
      alert("A data de fim não pode ser anterior à data de início.");
      // Para edição, talvez não limpar, mas mostrar um aviso mais persistente ou validar no submit
      // $(this).val('');
    }
  });
   $("#data_inicio").on("change", function() {
    var endDate = $("#data_fim").val();
    if(endDate) {
        $("#data_fim").trigger("change");
    }
  });
});
</script>
</body>
</html>
