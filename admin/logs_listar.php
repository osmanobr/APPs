<?php
$page_title = "Logs do Sistema";
require_once __DIR__ . '/../views/partials/header.php';
// Não vamos criar um Model Log.php por enquanto, a query será feita aqui ou em functions.php
require_once __DIR__ . '/../core/db.php'; // Para acesso direto ao DB se necessário

protect_page('admin'); // Apenas administradores podem ver os logs

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

function get_all_logs() {
    try {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        // Vamos buscar também o nome do usuário que realizou a ação
        $sql = "SELECT l.*, u.nome as nome_usuario, u.email as email_usuario
                FROM logs l
                LEFT JOIN usuarios u ON l.usuario_id = u.id
                ORDER BY l.data_ocorrencia DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Não usar add_log aqui para evitar loop infinito se o log de logs falhar
        error_log("Erro ao buscar logs do sistema: " . $e->getMessage());
        return [];
    }
}

$logs = get_all_logs();

$nivel_classes = [
    'info' => 'badge-info',
    'erro' => 'badge-danger',
    'aviso' => 'badge-warning',
    'debug' => 'badge-secondary',
];
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/logs_listar.php" class="nav-link active">Logs</a></li>
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
          <li class="nav-item">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Inquilinos <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilinos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Inquilinos</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilino_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Inquilino</p></a></li>
            </ul>
          </li>
          <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="nav-link"><i class="nav-icon fas fa-user-cog"></i><p>Gerenciar Usuários</p></a></li>
          <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/logs_listar.php" class="nav-link active"><i class="nav-icon fas fa-clipboard-list"></i><p>Logs do Sistema</p></a></li>
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
                <h3 class="card-title">Registros de atividades e erros do sistema</h3>
              </div>
              <div class="card-body">
                <?php if (empty($logs)): ?>
                  <div class="alert alert-info">Nenhum log registrado ainda.</div>
                <?php else: ?>
                  <table id="logsTable" class="table table-bordered table-hover table-sm"> <!-- table-sm para mais densidade -->
                    <thead>
                    <tr>
                      <th>Data/Hora</th>
                      <th>Nível</th>
                      <th>Usuário</th>
                      <th>Ação</th>
                      <th>Detalhes</th>
                      <th>IP Origem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                      <td><?php echo escape_html(date('d/m/Y H:i:s', strtotime($log['data_ocorrencia']))); ?></td>
                      <td>
                        <span class="badge <?php echo $nivel_classes[$log['nivel']] ?? 'badge-light'; ?>">
                          <?php echo strtoupper(escape_html($log['nivel'])); ?>
                        </span>
                      </td>
                      <td><?php echo escape_html($log['nome_usuario'] ?? ($log['email_usuario'] ?? 'Sistema')); ?></td>
                      <td><?php echo escape_html($log['acao']); ?></td>
                      <td><small><?php echo nl2br(escape_html($log['detalhes'])); ?></small></td>
                      <td><?php echo escape_html($log['ip_origem']); ?></td>
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
    $('#logsTable').DataTable({
        "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"},
        "order": [[0, "desc"]], // Ordenar por data/hora descendente
        "responsive": true,
        "columnDefs": [
            { "responsivePriority": 1, "targets": 0 }, // Data
            { "responsivePriority": 2, "targets": 1 }, // Nível
            { "responsivePriority": 3, "targets": 3 }, // Ação
            { "responsivePriority": 4, "targets": 4 }  // Detalhes
        ]
    });
});
</script>
</body>
</html>
