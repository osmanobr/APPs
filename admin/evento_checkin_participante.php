<?php
$page_title = "Registrar Check-in de Participante no Evento";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/ParticipacaoEvento.php'; // Para buscar eventos e inquilinos

// Níveis de acesso: admin, funcionario
protect_page(['admin', 'funcionario']);

$participacaoEventoModel = new ParticipacaoEvento();

$eventos = $participacaoEventoModel->getAllEventosAtivosSimple();
$inquilinos = $participacaoEventoModel->getAllInquilinosSimple();

$evento_id_selecionado = $_GET['evento_id'] ?? null;

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

// $form_data = $_SESSION['form_data_checkin_evento'] ?? [];
// unset($_SESSION['form_data_checkin_evento']);
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="nav-link">Eventos</a></li>
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
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-book-open"></i><p>Reservas</p> <!-- Simplificado, sem sub-menu aqui -->
                </a>
            </li>
             <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/estacionamentos_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-car"></i><p>Estacionamento</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-concierge-bell"></i><p>Pedidos</p>
                </a>
            </li>
            <li class="nav-item menu-open"> <!-- Menu de Eventos aberto -->
                <a href="#" class="nav-link active"><i class="nav-icon fas fa-calendar-alt"></i><p>Eventos <i class="right fas fa-angle-left"></i></p></a>
                <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Eventos</p></a></li>
                    <?php if ($user_level === 'admin'): ?>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/evento_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Evento</p></a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/evento_checkin_participante.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Check-in em Evento</p></a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-hotel"></i><p>Hotéis</p></a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-door-open"></i><p>Apartamentos</p></a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Inquilinos</p></a>
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
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php">Eventos</a></li>
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
              <div class="card-header"><h3 class="card-title">Dados do Check-in no Evento</h3></div>
              <form id="formCheckinEvento" action="<?php echo APP_URL; ?>/controllers/ParticipacaoEventoController.php" method="POST">
                <input type="hidden" name="action" value="checkin_participante">
                <div class="card-body">

                  <div class="form-group">
                    <label for="evento_id">Evento <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4" id="evento_id" name="evento_id" required style="width: 100%;">
                      <option value="">Selecione um Evento</option>
                      <?php foreach ($eventos as $evento): ?>
                        <option value="<?php echo escape_html($evento['id']); ?>" <?php echo ($evento_id_selecionado == $evento['id']) ? 'selected' : ''; ?>>
                            <?php echo escape_html($evento['nome']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="inquilino_id">Inquilino/Participante <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4" id="inquilino_id" name="inquilino_id" required style="width: 100%;">
                      <option value="">Selecione um Inquilino</option>
                      <?php foreach ($inquilinos as $inquilino): ?>
                        <option value="<?php echo escape_html($inquilino['id']); ?>">
                            <?php echo escape_html($inquilino['nome']); ?>
                            (<?php echo escape_html($inquilino['email'] ? 'Email: '.$inquilino['email'] : ($inquilino['documento'] ? 'Doc: '.$inquilino['documento'] : 'N/A')); ?>)
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="tipo_participacao">Tipo de Participação (opcional)</label>
                    <input type="text" class="form-control" id="tipo_participacao" name="tipo_participacao" placeholder="Ex: Congressista, Palestrante, Convidado">
                  </div>

                  <div class="form-group">
                    <label for="comprovante_checkin">Comprovante de Check-in (opcional)</label>
                    <input type="text" class="form-control" id="comprovante_checkin" name="comprovante_checkin" placeholder="Ex: ID Crachá, Nº Pulseira">
                  </div>

                  <div class="form-group">
                    <label for="observacoes">Observações (opcional)</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" rows="2"></textarea>
                  </div>

                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">Registrar Check-in no Evento</button>
                  <a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="btn btn-secondary">Cancelar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.2/select2-bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
});
</script>
</body>
</html>
