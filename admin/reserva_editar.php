<?php
$page_title = "Editar Reserva";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Apartamento.php';

protect_page(['admin', 'vendedor']);

$reserva_id = $_GET['id'] ?? null;
if (!$reserva_id) {
    $_SESSION['error_message'] = "ID da reserva não fornecido para edição.";
    redirect(APP_URL . '/admin/reservas_listar.php');
    exit;
}

$reservaModel = new Reserva();
$reserva = $reservaModel->getByIdWithDetails($reserva_id);

if (!$reserva) {
    $_SESSION['error_message'] = "Reserva não encontrada (ID: " . escape_html($reserva_id) . ").";
    redirect(APP_URL . '/admin/reservas_listar.php');
    exit;
}

$apartamentoModel = new Apartamento();
$eventos = $reservaModel->getAllEventosAtivos();
$inquilinos = $reservaModel->getAllInquilinos();
$hoteis = $apartamentoModel->getAllHoteisSimple();

$tipos_acomodacao_map = [
    'solteiro' => 'Solteiro',
    'duplo' => 'Duplo',
    'casal' => 'Casal',
    'triplo' => 'Triplo'
];

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

// Formatar datas para os inputs datetime-local
$data_checkin_formatted = !empty($reserva['data_checkin']) ? date('Y-m-d\TH:i', strtotime($reserva['data_checkin'])) : '';
$data_checkout_formatted = !empty($reserva['data_checkout']) ? date('Y-m-d\TH:i', strtotime($reserva['data_checkout'])) : '';

?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link">Reservas</a></li>
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
            <li class="nav-item menu-open">
                <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link active">
                    <i class="nav-icon fas fa-book-open"></i><p>Reservas <i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Listar Reservas</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reserva_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Reserva</p></a></li>
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

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1><?php echo $page_title; ?> <small>(ID: <?php echo escape_html($reserva['id']); ?>)</small></h1></div>
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

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <div class="card card-warning"> <!-- Card warning para edição -->
              <div class="card-header"><h3 class="card-title">Dados da Reserva</h3></div>
              <form id="formEditarReserva" action="<?php echo APP_URL; ?>/controllers/ReservaController.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="reserva_id_edit" value="<?php echo escape_html($reserva['id']); ?>">
                <div class="card-body">

                  <div class="form-group">
                    <label for="evento_id">Evento <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4" id="evento_id" name="evento_id" required>
                      <option value="">Selecione um Evento</option>
                      <?php foreach ($eventos as $evento): ?>
                        <option value="<?php echo escape_html($evento['id']); ?>" <?php echo ($reserva['evento_id'] == $evento['id']) ? 'selected' : ''; ?>>
                          <?php echo escape_html($evento['nome']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data_checkin">Data e Hora de Check-in <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="data_checkin" name="data_checkin" required value="<?php echo $data_checkin_formatted; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data_checkout">Data e Hora de Check-out <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="data_checkout" name="data_checkout" required value="<?php echo $data_checkout_formatted; ?>">
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
                                    <option value="<?php echo escape_html($hotel['id']); ?>" <?php echo ($reserva['nome_hotel'] == $hotel['nome']) ? 'selected' : ''; // Pré-seleciona o hotel atual da reserva? Pode ser complicado se o apto mudar. Melhor deixar o usuário filtrar. Se o apto atual for de um hotel X, selecionar X.
                                    // Para simplificar, vamos pré-selecionar o hotel do apartamento atual da reserva
                                    $apto_atual_hotel_id = null;
                                    if(isset($reserva['apartamento_id'])) {
                                        $apto_info_atual = $apartamentoModel->getById($reserva['apartamento_id']);
                                        if($apto_info_atual) $apto_atual_hotel_id = $apto_info_atual['hotel_id'];
                                    }
                                    echo ($apto_atual_hotel_id == $hotel['id']) ? 'selected' : '';
                                    ?>>
                                    <?php echo escape_html($hotel['nome']); ?>
                                    </option>
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
                                    <option value="<?php echo $key; ?>" <?php echo ($reserva['tipo_acomodacao_apt'] == $key) ? 'selected' : '';?>>
                                        <?php echo $value; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                  </div>
                  <button type="button" id="btnBuscarApartamentos" class="btn btn-info mb-3">Buscar Apartamentos Disponíveis</button>

                  <div class="form-group">
                    <label for="apartamento_id">Apartamento <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4" id="apartamento_id" name="apartamento_id" required>
                        <!-- Opção do apartamento atual da reserva -->
                        <option value="<?php echo escape_html($reserva['apartamento_id']); ?>" selected data-valor-diaria="<?php echo $reserva['valor_total'] / ( (new DateTime($reserva['data_checkout']))->diff(new DateTime($reserva['data_checkin']))->days > 0 ? (new DateTime($reserva['data_checkout']))->diff(new DateTime($reserva['data_checkin']))->days : 1) ; // Aproximação da diária ?>" data-tipo="<?php echo $reserva['tipo_acomodacao_apt']; ?>" data-hotel="<?php echo $reserva['nome_hotel']; ?>">
                            <?php echo escape_html($reserva['nome_hotel']); ?> - Apto <?php echo escape_html($reserva['numero_apartamento']); ?> (Atual)
                        </option>
                        <!-- Outras options serão populadas via AJAX -->
                    </select>
                     <div id="apartamento_details" class="mt-2 text-muted"></div>
                  </div>

                  <div class="form-group">
                    <label for="inquilino_id">Inquilino Principal <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4" id="inquilino_id" name="inquilino_id" required>
                      <option value="">Selecione um Inquilino</option>
                      <?php foreach ($inquilinos as $inquilino): ?>
                        <option value="<?php echo escape_html($inquilino['id']); ?>" <?php echo ($reserva['inquilino_id'] == $inquilino['id']) ? 'selected' : ''; ?>>
                          <?php echo escape_html($inquilino['nome']); ?> (<?php echo escape_html($inquilino['email'] ?? 'N/A'); ?>)
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="valor_total_display">Valor Total Estimado (R$)</label>
                    <input type="text" class="form-control" id="valor_total_display" value="<?php echo number_format($reserva['valor_total'], 2, ',', '.'); ?>" readonly>
                  </div>

                  <div class="form-group">
                    <label>Status do Pagamento Atual:</label>
                    <p><span class="badge <?php echo $status_pagamento_classes[$reserva['status_pagamento_atual']] ?? 'badge-light'; ?>"><?php echo strtoupper(escape_html($reserva['status_pagamento_atual'])); ?></span></p>
                    <small class="form-text text-muted">Para alterar o status do pagamento, utilize a tela de Pagamentos ou registre um novo pagamento manual.</small>
                  </div>

                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                  <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="btn btn-secondary">Cancelar</a>
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
    $('.select2bs4').select2({ theme: 'bootstrap4' });

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
             // Se não conseguir calcular com o selecionado, tenta pegar do valor original da reserva se o apto for o mesmo
            var originalAptoId = "<?php echo escape_html($reserva['apartamento_id']); ?>";
            if (aptoSelecionado.val() === originalAptoId) {
                 $('#valor_total_display').val("<?php echo number_format($reserva['valor_total'], 2, ',', '.'); ?>");
            } else {
                $('#valor_total_display').val('');
            }
        }
    }

    // Calcular diárias e valor ao carregar a página
    calcularValorTotal();

    // Atualizar detalhes do apartamento selecionado ao carregar, se já houver um
    var currentAptoSelected = $('#apartamento_id').find('option:selected');
    if (currentAptoSelected.val()) {
         $('#apartamento_details').html(
            `Hotel: ${currentAptoSelected.data('hotel')} <br>
             Tipo: ${tipos_acomodacao_map[currentAptoSelected.data('tipo')] || currentAptoSelected.data('tipo')} <br>
             Valor Diária: R$ ${parseFloat(currentAptoSelected.data('valor-diaria')).toFixed(2)}`
        );
    }


    $('#data_checkin, #data_checkout').on('change', function() {
        calcularValorTotal();
        // Não limpar apartamentos aqui na edição, apenas permitir nova busca se desejado
    });

    var tipos_acomodacao_map = <?php echo json_encode($tipos_acomodacao_map); ?>;

    $('#btnBuscarApartamentos').on('click', function() {
        var dataInicio = $('#data_checkin').val();
        var dataFim = $('#data_checkout').val();
        var tipoAcomodacao = $('#filtro_tipo_acomodacao').val();
        var hotelId = $('#filtro_hotel_id').val();
        var ignoreReservaId = $('#reserva_id_edit').val();

        if (!dataInicio || !dataFim) {
            alert('Por favor, selecione as datas de check-in e check-out.');
            return;
        }
         if (new Date(dataFim) <= new Date(dataInicio)) {
            alert('Data de checkout deve ser posterior à data de check-in.');
            return;
        }

        var originalAptId = "<?php echo escape_html($reserva['apartamento_id']); ?>";
        var originalAptText = $('#apartamento_id').find(`option[value="${originalAptId}"]`).text();
        var originalAptData = $('#apartamento_id').find(`option[value="${originalAptId}"]`).data();


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
                var aptoOriginalEncontradoNaBusca = false;

                if (response && response.length > 0) {
                    $('#apartamento_id').append('<option value="">Selecione um Apartamento</option>');
                    response.forEach(function(apto) {
                        if(apto.id === originalAptId) aptoOriginalEncontradoNaBusca = true;
                        $('#apartamento_id').append(
                            `<option value="${apto.id}" data-valor-diaria="${apto.valor_diaria}" data-tipo="${apto.tipo_acomodacao}" data-hotel="${apto.nome_hotel}">
                                ${apto.nome_hotel} - Apto ${apto.numero_apartamento} (Piso: ${apto.numero_piso || 'N/A'}) - ${tipos_acomodacao_map[apto.tipo_acomodacao] || apto.tipo_acomodacao} - R$ ${parseFloat(apto.valor_diaria).toFixed(2)}
                            </option>`
                        );
                    });
                }

                // Se o apartamento original não estiver na nova lista (ex: por mudança de data),
                // ainda o adicionamos para que o usuário possa mantê-lo se for um erro de filtro,
                // mas o controller fará a validação final.
                // Ou, melhor, se o apto original NÃO ESTIVER MAIS DISPONÍVEL para as NOVAS DATAS, ele não deve ser uma opção.
                // O que fazemos é: se o apto original não está na lista E as datas mudaram, ele não é mais válido para seleção.
                // A complexidade aqui é que o usuário pode querer manter o mesmo apto e só mudar o inquilino.
                // A busca já considera ignore_reserva_id. Se o apto atual não aparecer, significa que ele conflita com OUTRA reserva no novo período.

                // Adicionar o apartamento original de volta à lista SE ele não foi encontrado E as datas NÃO mudaram,
                // OU se ele foi encontrado (para garantir que está lá e pode ser selecionado).
                // A lógica de disponibilidade final é do backend.
                // Para edição, sempre mostramos o apartamento atual da reserva como selecionável.
                // A validação de disponibilidade se o apartamento/datas mudarem é feita no backend.

                var currentSelectedAptId = "<?php echo escape_html($reserva['apartamento_id']); ?>";
                var foundCurrentInNewList = false;
                $('#apartamento_id option').each(function() {
                    if ($(this).val() == currentSelectedAptId) {
                        foundCurrentInNewList = true;
                    }
                });

                if (!foundCurrentInNewList && originalAptId && originalAptText) {
                     $('#apartamento_id').prepend(
                        `<option value="${originalAptId}" data-valor-diaria="${originalAptData.valorDiaria}" data-tipo="${originalAptData.tipo}" data-hotel="${originalAptData.hotel}">
                            ${originalAptText} (Manter Atual)
                        </option>`
                    );
                }

                $('#apartamento_id').val(currentSelectedAptId).trigger('change'); // Tenta reselecionar o atual

                if ($('#apartamento_id option').length <=1 && !$('#apartamento_id').val() ) { // <=1 porque pode ter o "Selecione"
                     $('#apartamento_id').html('<option value="">Nenhum apartamento disponível para os critérios</option>');
                }


            },
            error: function() {
                $('#apartamento_id').html('<option value="">Erro ao buscar. Tente novamente.</option>').prop('disabled', false);
                alert('Erro ao buscar apartamentos disponíveis.');
            }
        });
    });

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

    // Disparar cálculo inicial e busca de apartamentos se as datas já estiverem preenchidas
    if ($('#data_checkin').val() && $('#data_checkout').val()) {
        // calcularValorTotal(); // Já é chamado ao carregar
        // $('#btnBuscarApartamentos').trigger('click'); // O usuário deve clicar para confirmar a busca com as datas atuais
    }


});
</script>
</body>
</html>
