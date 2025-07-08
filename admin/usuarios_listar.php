<?php
$page_title = "Gerenciar Usuários";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Usuario.php';

protect_page('admin'); // Apenas administradores podem gerenciar usuários

$usuarioModel = new Usuario();

// Filtros (exemplo, pode ser expandido)
$filtro_nivel = $_GET['nivel_acesso'] ?? '';
$filtros_aplicados = [];
if ($filtro_nivel) {
    $filtros_aplicados['nivel_acesso'] = $filtro_nivel;
}
$usuarios = $usuarioModel->getAll($filtros_aplicados);

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A'); // Nível do usuário logado
$logged_in_user_id_management = get_logged_in_user_id();

$niveis_acesso_map = Usuario::getNiveisAcessoPermitidos(); // Para o filtro
$niveis_acesso_display = [
    'admin' => 'Administrador',
    'vendedor' => 'Vendedor',
    'funcionario' => 'Funcionário',
    'valet' => 'Valet (Manobrista)'
];

?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="nav-link active">Usuários</a></li>
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
            <li class="nav-item menu-open">
                <a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="nav-link active">
                    <i class="nav-icon fas fa-user-cog"></i><p>Gerenciar Usuários <i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Listar Usuários</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/usuario_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Novo Usuário</p></a></li>
                </ul>
            </li>
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
            <div class="card-header"><h3 class="card-title">Filtros</h3></div>
            <div class="card-body">
                <form method="GET" action="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="form-inline">
                    <div class="form-group mb-2 mr-sm-2">
                        <label for="filtro_nivel" class="mr-sm-2">Nível de Acesso:</label>
                        <select name="nivel_acesso" id="filtro_nivel" class="form-control select2bs4-filter" style="width: 200px;">
                            <option value="">Todos</option>
                            <?php foreach($niveis_acesso_map as $nivel_key): ?>
                                <option value="<?php echo $nivel_key; ?>" <?php echo ($filtro_nivel == $nivel_key) ? 'selected' : ''; ?>>
                                    <?php echo $niveis_acesso_display[$nivel_key] ?? ucfirst($nivel_key); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-filter"></i> Filtrar</button>
                    <a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="btn btn-secondary mb-2 ml-2"><i class="fas fa-eraser"></i> Limpar</a>
                </form>
            </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de Usuários do Sistema</h3>
                <div class="card-tools">
                  <a href="<?php echo APP_URL; ?>/admin/usuario_criar.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Novo Usuário</a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($usuarios)): ?>
                  <div class="alert alert-info">Nenhum usuário encontrado para os filtros aplicados.</div>
                <?php else: ?>
                  <div class="table-responsive">
                  <table id="usuariosTable" class="table table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Email</th>
                      <th>Nível de Acesso</th>
                      <th>Data Criação</th>
                      <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($usuarios as $usr): ?>
                    <tr>
                      <td><?php echo escape_html($usr['nome']); ?></td>
                      <td><?php echo escape_html($usr['email']); ?></td>
                      <td>
                        <span class="badge badge-info">
                            <?php echo escape_html($niveis_acesso_display[$usr['nivel_acesso']] ?? ucfirst($usr['nivel_acesso'])); ?>
                        </span>
                        </td>
                      <td><?php echo escape_html(date('d/m/Y H:i', strtotime($usr['data_criacao']))); ?></td>
                      <td>
                        <a href="<?php echo APP_URL; ?>/admin/usuario_editar.php?id=<?php echo $usr['id']; ?>" class="btn btn-xs btn-info" title="Editar Usuário"><i class="fas fa-edit"></i></a>
                        <?php if ($usr['id'] !== $logged_in_user_id_management): // Não permitir excluir a si mesmo ?>
                        <button class="btn btn-xs btn-danger btn-delete-usuario"
                                data-id="<?php echo $usr['id']; ?>"
                                data-nome="<?php echo escape_html($usr['nome']); ?>"
                                title="Excluir Usuário">
                            <i class="fas fa-trash"></i>
                        </button>
                        <?php else: ?>
                             <button class="btn btn-xs btn-outline-secondary" disabled title="Não pode excluir seu próprio usuário"><i class="fas fa-trash"></i></button>
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

  <!-- Modal de Confirmação de Exclusão de Usuário -->
    <div class="modal fade" id="deleteUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="deleteUsuarioForm" action="<?php echo APP_URL; ?>/controllers/UsuarioController.php" method="POST" style="display: inline;">
                    <div class="modal-body">
                        Tem certeza que deseja excluir o usuário "<span id="usuarioNomeModal"></span>"?
                        <br><small class="text-danger">Atenção: Esta ação não pode ser desfeita. As associações deste usuário (ex: organizador de evento, vendedor de apartamento) serão definidas como NULAS se possível, mas o histórico de logs permanecerá.</small>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="usuarioIdToDelete">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir Usuário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.2/select2-bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#usuariosTable').DataTable({
        "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"},
        "order": [[0, "asc"]] // Ordenar por Nome ascendente
    });
    $('.select2bs4-filter').select2({
      theme: 'bootstrap4',
      allowClear: true,
      placeholder: "Selecione"
    });

    $('.btn-delete-usuario').on('click', function() {
        var usuarioId = $(this).data('id');
        var usuarioNome = $(this).data('nome');

        $('#usuarioIdToDelete').val(usuarioId);
        $('#usuarioNomeModal').text(usuarioNome);

        $('#deleteUsuarioModal').modal('show');
    });
});
</script>
</body>
</html>
