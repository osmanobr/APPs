<?php
$page_title = "Dashboard";
// Inclui o header, que já carrega config, functions e inicia a sessão
require_once __DIR__ . '/../views/partials/header.php';

// Protege a página - requer que o usuário esteja logado.
// Pode-se especificar níveis de acesso: protect_page('admin'); ou protect_page(['admin', 'vendedor']);
protect_page(); // Apenas logado por enquanto, ajustaremos os níveis conforme necessário

// Carregar o template do AdminLTE (sidebar, navbar, etc.)
// Por simplicidade, vamos adicionar um conteúdo básico aqui.
// Em uma aplicação completa, você teria includes para sidebar, navbar, etc.

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

// Incluir models para buscar dados para os cards do dashboard
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Estacionamento.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Evento.php';
require_once __DIR__ . '/../models/Pagamento.php';
require_once __DIR__ . '/../models/ComissaoVendedor.php';
require_once __DIR__ . '/../models/Inquilino.php';
require_once __DIR__ . '/../models/Apartamento.php';


$reservaModel = new Reserva();
$estacionamentoModel = new Estacionamento();
$pedidoModel = new Pedido();
$eventoModel = new Evento();
$pagamentoModel = new Pagamento();
$comissaoModel = new ComissaoVendedor();
$inquilinoModel = new Inquilino();
$apartamentoModel = new Apartamento();


// FINANCEIRO
$data_atual = date('Y-m-d H:i:s');
$inicio_mes_atual = date('Y-m-01 00:00:00');
$total_arrecadado_mes = $pagamentoModel->getTotalArrecadadoNoPeriodo($inicio_mes_atual, $data_atual);
$total_pendente_geral = $pagamentoModel->getTotalPendente();
$pagamentos_orfaos_count = $pagamentoModel->countPagamentosOrfaos();
$comissoes_pendentes_total = $comissaoModel->getTotalComissoesPendentes();
// $comissoes_pagas_mes = $comissaoModel->getTotalComissoesPagasNoPeriodo($inicio_mes_atual, $data_atual); // Implementar se necessário granularidade por data de pagamento da comissão

// OPERACIONAL
$reservas_ativas_eventos_futuros = $reservaModel->countReservasAtivasEventosFuturos();
// Taxa de ocupação é mais complexa, pode precisar de um evento específico ou período. Deixar para depois ou simplificar.
// $checkins_evento_hoje = $participacaoEventoModel->countCheckinsHoje(); // Precisaria de ParticipacaoEventoModel
$veiculos_estacionados_count = $estacionamentoModel->countVeiculosEstacionadosAtualmente(); // Já tínhamos
$pedidos_abertos_count = $pedidoModel->countPedidosAbertos(); // Já tínhamos

// GERAL
$eventos_futuros_count = $eventoModel->countEventosFuturos(); // Já tínhamos
$total_inquilinos = $inquilinoModel->countTotalInquilinos();
$total_apartamentos = $apartamentoModel->countTotalApartamentos();


?>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/logout.php" role="button">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="brand-link">
      <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo escape_html(SITE_NAME); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- Você pode adicionar uma imagem de usuário aqui -->
          <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $user_name; ?></a>
          <span class="text-muted"><?php echo ucfirst($user_level); ?></span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <?php if ($user_level === 'admin'): ?>
          <li class="nav-header">ADMINISTRAÇÃO</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Eventos <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Eventos</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/evento_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Evento</p></a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-hotel"></i>
              <p>Hotéis <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Hotéis</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/hotel_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Hotel</p></a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-door-open"></i>
              <p>Apartamentos <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Apartamentos</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/apartamento_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Apartamento</p></a></li>
            </ul>
          </li>
           <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Inquilinos <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilinos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Inquilinos</p></a></li>
              <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/inquilino_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Inquilino</p></a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/admin/usuarios_listar.php" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Gerenciar Usuários</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/admin/logs_listar.php" class="nav-link">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>Logs do Sistema</p>
            </a>
          </li>
          <?php endif; ?>

          <?php if (in_array($user_level, ['admin', 'vendedor'])): ?>
          <li class="nav-header">VENDAS & RESERVAS</li>
          <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p>Reservas</p>
            </a>
          </li>
          <?php endif; ?>

          <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $page_title; ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Bem-vindo(a), <?php echo $user_name; ?>!</h3>
              </div>
              <div class="card-body">
                <p>Este é o seu painel de controle. Você está logado como: <strong><?php echo ucfirst($user_level); ?></strong>.</p>
                <p>Use o menu lateral para navegar pelas funcionalidades do sistema.</p>

                <h4>Ações rápidas:</h4>
                <ul>
                    <?php if ($user_level === 'admin'): ?>
                        <li><a href="<?php echo APP_URL; ?>/admin/evento_criar.php">Criar Novo Evento</a></li>
                        <li><a href="<?php echo APP_URL; ?>/admin/hotel_criar.php">Criar Novo Hotel</a></li>
                        <li><a href="<?php echo APP_URL; ?>/admin/apartamento_criar.php">Criar Novo Apartamento</a></li>
                    <?php endif; ?>
                    <?php if (in_array($user_level, ['admin', 'vendedor'])): ?>
                         <li><a href="#">Criar Nova Reserva</a> (a implementar na Fase 2)</li>
                         <li><a href="<?php echo APP_URL; ?>/admin/inquilino_criar.php">Criar Novo Inquilino</a></li>
                    <?php endif; ?>
                </ul>

              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong>
    Todos os direitos reservados.
    <div class="float-right d-none d-sm-inline-block">
      <b>Versão</b> 1.0.0
    </div>
  </footer>

</div>
<!-- ./wrapper -->

<?php
// Inclui o footer (scripts JS, etc.)
require_once __DIR__ . '/../views/partials/footer.php';
?>
</body>
</html>
