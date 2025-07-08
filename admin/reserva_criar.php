<?php
$page_title = "Criar Nova Reserva";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Reserva.php'; // Para buscar eventos, inquilinos, aptos
require_once __DIR__ . '/../models/Apartamento.php'; // Para tipos de acomodação

protect_page(['admin', 'vendedor']);

$reservaModel = new Reserva();
$apartamentoModel = new Apartamento(); // Para buscar tipos de acomodação e hotéis

$eventos = $reservaModel->getAllEventosAtivos();
$inquilinos = $reservaModel->getAllInquilinos();
$hoteis = $apartamentoModel->getAllHoteisSimple(); // Usar do ApartamentoModel

$tipos_acomodacao_map = [
    'solteiro' => 'Solteiro',
    'duplo' => 'Duplo',
    'casal' => 'Casal',
    'triplo' => 'Triplo'
];


$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

// $form_data = $_SESSION['form_data_reserva'] ?? [];
// unset($_SESSION['form_data_reserva']);
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link">Reservas</a></li>
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
            <li class="nav-header">GESTÃO FINANCEIRA</li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php" class="nav-link"><i class="nav-icon fas fa-dollar-sign"></i><p>Pagamentos</p></a></li>
            <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/comissoes_vendedor_listar.php" class="nav-link"><i class="nav-icon fas fa-percent"></i><p>Comissões</p></a></li>
            <li class="nav-header">GESTÃO OPERACIONAL</li>
            <li class="nav-item menu-open">
                <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link active">
                    <i class="nav-icon fas fa-book-open"></i><p>Reservas <i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Reservas</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reserva_criar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Criar Reserva</p></a></li>
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
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php">Reservas</a></li>
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
            <div class="card card-primary">
              <div class="card-header"><h3 class="card-title">Dados da Nova Reserva</h3></div>
              <form id="formCriarReserva" action="<?php echo APP_URL; ?>/controllers/ReservaController.php" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="card-body">

                  <div class="form-group">
                    <label for="evento_id">Evento <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4" id="evento_id" name="evento_id" required>
                      <option value="">Selecione um Evento</option>
                      <?php foreach ($eventos as $evento): ?>
                        <option value="<?php echo escape_html($evento['id']); ?>"><?php echo escape_html($evento['nome']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data_checkin">Data e Hora de Check-in <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="data_checkin" name="data_checkin" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data_checkout">Data e Hora de Check-out <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="data_checkout" name="data_checkout" required>
                        </div>
                    </div>
                  </div>
                  <p id="num_diarias_info" class="text-muted"></p>


                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filtro_hotel_id">Filtrar por Hotel (opcional)</label>
                            <select class="form-control select2bs4" id="filtro_hotel_id" name="filtro_hotel_id">
                                <option value="">Todos os Hotéis</option>
                                <?php foreach ($hoteis as $hotel): ?>
                                    <option value="<?php echo escape_html($hotel['id']); ?>"><?php echo escape_html($hotel['nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filtro_tipo_acomodacao">Filtrar por Tipo de Acomodação (opcional)</label>
                            <select class="form-control select2bs4" id="filtro_tipo_acomodacao" name="filtro_tipo_acomodacao">
                                <option value="">Todos os Tipos</option>
                                <?php foreach ($tipos_acomodacao_map as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                  </div>
                  <button type="button" id="btnBuscarApartamentos" class="btn btn-info mb-3">Buscar Apartamentos Disponíveis</button>

                  <div class="form-group">
                    <label for="apartamento_id">Apartamento Disponível <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4" id="apartamento_id" name="apartamento_id" required>
                      <option value="">Selecione as datas e clique em buscar</option>
                      <!-- Options serão populadas via AJAX -->
                    </select>
                    <div id="apartamento_details" class="mt-2 text-muted"></div>
                  </div>

                  <div class="form-group">
                    <label for="inquilino_id">Inquilino Principal <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4" id="inquilino_id" name="inquilino_id" required>
                      <option value="">Selecione um Inquilino</option>
                      <?php foreach ($inquilinos as $inquilino): ?>
                        <option value="<?php echo escape_html($inquilino['id']); ?>"><?php echo escape_html($inquilino['nome']); ?> (<?php echo escape_html($inquilino['email'] ?? 'N/A'); ?>)</option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="valor_total_display">Valor Total Estimado (R$)</label>
                    <input type="text" class="form-control" id="valor_total_display" readonly>
                  </div>

                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Criar Reserva</button>
                  <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="btn btn-secondary">Cancelar</a>
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
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.2/select2-bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });

    function calcularDiarias() {
        var checkinStr = $('#data_checkin').val();
        var checkoutStr = $('#data_checkout').val();
        $('#num_diarias_info').empty();
        $('#valor_total_display').val('');


        if (checkinStr && checkoutStr) {
            var checkinDate = new Date(checkinStr);
            var checkoutDate = new Date(checkoutStr);

            if (checkoutDate > checkinDate) {
                var diffTime = Math.abs(checkoutDate - checkinDate);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                if (diffDays <= 0) diffDays = 1;
                $('#num_diarias_info').text(`Número de diárias: ${diffDays}`);
                return diffDays;
            } else {
                 $('#num_diarias_info').text('Data de checkout deve ser maior que check-in.');
                return 0;
            }
        }
        return 0;
    }

    function calcularValorTotal() {
        var numDiarias = calcularDiarias();
        var aptoSelecionado = $('#apartamento_id').find('option:selected');
        var valorDiaria = parseFloat(aptoSelecionado.data('valor-diaria'));

        if (numDiarias > 0 && valorDiaria > 0) {
            var valorTotal = numDiarias * valorDiaria;
            $('#valor_total_display').val(valorTotal.toFixed(2).replace('.', ','));
        } else {
            $('#valor_total_display').val('');
        }
    }

    $('#data_checkin, #data_checkout').on('change', function() {
        calcularDiarias();
        // Limpar apartamentos se as datas mudarem, forçando nova busca
        $('#apartamento_id').html('<option value="">Selecione as datas e clique em buscar</option>').trigger('change');
        $('#apartamento_details').empty();
        $('#valor_total_display').val('');
    });

    $('#btnBuscarApartamentos').on('click', function() {
        var dataInicio = $('#data_checkin').val();
        var dataFim = $('#data_checkout').val();
        var tipoAcomodacao = $('#filtro_tipo_acomodacao').val();
        var hotelId = $('#filtro_hotel_id').val();
        var ignoreReservaId = $('#reserva_id_edit').val(); // Para edição, se houver

        if (!dataInicio || !dataFim) {
            alert('Por favor, selecione as datas de check-in e check-out.');
            return;
        }
        if (new Date(dataFim) <= new Date(dataInicio)) {
            alert('Data de checkout deve ser posterior à data de check-in.');
            return;
        }

        $('#apartamento_id').html('<option value="">Buscando...</option>').prop('disabled', true);
        $('#apartamento_details').empty();
        $('#valor_total_display').val('');


        $.ajax({
            url: '<?php echo APP_URL; ?>/controllers/ReservaController.php',
            type: 'GET',
            data: {
                action: 'get_apartamentos_disponiveis',
                data_inicio: dataInicio,
                data_fim: dataFim,
                tipo_acomodacao: tipoAcomodacao,
                hotel_id: hotelId,
                ignore_reserva_id: ignoreReservaId
            },
            dataType: 'json',
            success: function(response) {
                $('#apartamento_id').empty().prop('disabled', false);
                if (response && response.length > 0) {
                    $('#apartamento_id').append('<option value="">Selecione um Apartamento</option>');
                    response.forEach(function(apto) {
                        $('#apartamento_id').append(
                            `<option value="${apto.id}" data-valor-diaria="${apto.valor_diaria}" data-tipo="${apto.tipo_acomodacao}" data-hotel="${apto.nome_hotel}">
                                ${apto.nome_hotel} - Apto ${apto.numero_apartamento} (Piso: ${apto.numero_piso || 'N/A'}) - ${tipos_acomodacao_map[apto.tipo_acomodacao] || apto.tipo_acomodacao} - R$ ${parseFloat(apto.valor_diaria).toFixed(2)}
                            </option>`
                        );
                    });
                } else {
                    $('#apartamento_id').append('<option value="">Nenhum apartamento disponível para os critérios</option>');
                }
                $('#apartamento_id').trigger('change'); // Notificar Select2
            },
            error: function() {
                $('#apartamento_id').html('<option value="">Erro ao buscar. Tente novamente.</option>').prop('disabled', false);
                alert('Erro ao buscar apartamentos disponíveis.');
            }
        });
    });

    var tipos_acomodacao_map = <?php echo json_encode($tipos_acomodacao_map); ?>;

    $('#apartamento_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            var valorDiaria = selectedOption.data('valor-diaria');
            var tipo = selectedOption.data('tipo');
            var hotel = selectedOption.data('hotel');
            $('#apartamento_details').html(
                `Hotel: ${hotel} <br>
                 Tipo: ${tipos_acomodacao_map[tipo] || tipo} <br>
                 Valor Diária: R$ ${parseFloat(valorDiaria).toFixed(2)}`
            );
            calcularValorTotal();
        } else {
            $('#apartamento_details').empty();
            $('#valor_total_display').val('');
        }
    });

});
</script>
</body>
</html>
