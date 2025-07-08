<?php
$page_title = "Editar Apartamento";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Apartamento.php';

protect_page('admin');

$apartamento_id = $_GET['id'] ?? null;
if (!$apartamento_id) {
    $_SESSION['error_message'] = "ID do apartamento não fornecido para edição.";
    redirect(APP_URL . '/admin/apartamentos_listar.php');
    exit;
}

$apartamentoModel = new Apartamento();
$apartamento = $apartamentoModel->getById($apartamento_id);

if (!$apartamento) {
    $_SESSION['error_message'] = "Apartamento não encontrado (ID: " . escape_html($apartamento_id) . ").";
    redirect(APP_URL . '/admin/apartamentos_listar.php');
    exit;
}

$hoteis = $apartamentoModel->getAllHoteisSimple();
$usuarios = $apartamentoModel->getPotenciaisUsuarios();

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

$tipos_acomodacao = [
    'solteiro' => 'Solteiro',
    'duplo' => 'Duplo',
    'casal' => 'Casal',
    'triplo' => 'Triplo'
];
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php" class="nav-link">Apartamentos</a></li>
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
          <li class="nav-item">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-hotel"></i><p>Hotéis <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Hotéis</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hotel_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Hotel</p></a></li>
            </ul>
          </li>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active"><i class="nav-icon fas fa-door-open"></i><p>Apartamentos <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Listar Apartamentos</p></a></li>
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
          <div class="col-sm-6"><h1><?php echo $page_title; ?>: Apt <?php echo escape_html($apartamento['numero_apartamento']); ?> (<?php echo escape_html($apartamento['nome_hotel']); ?>)</h1></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php">Apartamentos</a></li>
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
          <div class="col-md-10 offset-md-1">
            <div class="card card-warning">
              <div class="card-header"><h3 class="card-title">Dados do Apartamento</h3></div>
              <form action="<?php echo APP_URL; ?>/controllers/ApartamentoController.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo escape_html($apartamento['id']); ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hotel_id">Hotel <span class="text-danger">*</span></label>
                                <select class="form-control" id="hotel_id" name="hotel_id" required>
                                <option value="">Selecione um Hotel</option>
                                <?php foreach ($hoteis as $hotel): ?>
                                    <option value="<?php echo escape_html($hotel['id']); ?>" <?php echo ($apartamento['hotel_id'] == $hotel['id']) ? 'selected' : ''; ?>>
                                    <?php echo escape_html($hotel['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero_apartamento">Número do Apartamento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="numero_apartamento" name="numero_apartamento" required value="<?php echo escape_html($apartamento['numero_apartamento']); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero_piso">Piso/Andar</label>
                                <input type="text" class="form-control" id="numero_piso" name="numero_piso" value="<?php echo escape_html($apartamento['numero_piso']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_acomodacao">Tipo de Acomodação <span class="text-danger">*</span></label>
                                <select class="form-control" id="tipo_acomodacao" name="tipo_acomodacao" required>
                                <option value="">Selecione o Tipo</option>
                                <?php foreach ($tipos_acomodacao as $key => $value): ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($apartamento['tipo_acomodacao'] == $key) ? 'selected' : ''; ?>>
                                    <?php echo $value; ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="valor_diaria">Valor da Diária (R$) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="valor_diaria" name="valor_diaria" required value="<?php echo escape_html($apartamento['valor_diaria']); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vendedor_id">Vendedor Associado</label>
                                <select class="form-control" id="vendedor_id" name="vendedor_id">
                                <option value="">Nenhum / A definir</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <?php if (in_array($usuario['nivel_acesso'], ['vendedor', 'admin'])): ?>
                                    <option value="<?php echo escape_html($usuario['id']); ?>" <?php echo ($apartamento['vendedor_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                                        <?php echo escape_html($usuario['nome']); ?> (<?php echo ucfirst($usuario['nivel_acesso']);?>)
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="responsavel_id">Responsável Atual</label>
                                <select class="form-control" id="responsavel_id" name="responsavel_id">
                                <option value="">Nenhum / A definir</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?php echo escape_html($usuario['id']); ?>" <?php echo ($apartamento['responsavel_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                                        <?php echo escape_html($usuario['nome']); ?> (<?php echo ucfirst($usuario['nivel_acesso']);?>)
                                    </option>
                                <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">Pode ser um inquilino ou um usuário do sistema.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                  <a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php" class="btn btn-secondary">Cancelar</a>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#hotel_id').select2({ theme: 'bootstrap4', placeholder: "Selecione um Hotel", allowClear: true });
    $('#vendedor_id').select2({ theme: 'bootstrap4', placeholder: "Selecione um Vendedor", allowClear: true });
    $('#responsavel_id').select2({ theme: 'bootstrap4', placeholder: "Selecione um Responsável", allowClear: true });
    $('#tipo_acomodacao').select2({ theme: 'bootstrap4', placeholder: "Selecione o Tipo", minimumResultsForSearch: Infinity });
});
</script>
</body>
</html>
