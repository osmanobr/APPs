<?php
$page_title = "Comissões de Vendedores";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/ComissaoVendedor.php';
require_once __DIR__ . '/../models/Usuario.php'; // Para buscar vendedores para filtro

// Níveis de acesso: admin (para ver todas e pagar), vendedor (para ver apenas as suas)
protect_page(['admin', 'vendedor']);

$comissaoModel = new ComissaoVendedor();
// Supondo um model Usuario com método para buscar vendedores
$usuarioModel = new Usuario();
$vendedores_para_filtro = $usuarioModel->getByNivelAcesso(['vendedor', 'admin']); // Admins também podem ser vendedores


$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');
$logged_in_user_id_comissao = get_logged_in_user_id();

// Filtros
$filtro_vendedor_id = $_GET['vendedor_id'] ?? '';
$filtro_status_comissao = $_GET['status_comissao'] ?? '';

$filtros_aplicados = [];
if ($user_level === 'vendedor') {
    // Vendedor só pode ver suas próprias comissões
    $filtros_aplicados['vendedor_id'] = $logged_in_user_id_comissao;
    if ($filtro_status_comissao) $filtros_aplicados['status'] = $filtro_status_comissao;
} else { // Admin pode filtrar por qualquer vendedor
    if ($filtro_vendedor_id) $filtros_aplicados['vendedor_id'] = $filtro_vendedor_id;
    if ($filtro_status_comissao) $filtros_aplicados['status'] = $filtro_status_comissao;
}

$comissoes = $comissaoModel->getAllWithDetails($filtros_aplicados);

$status_comissao_map = [
    'pendente' => 'Pendente',
    'paga' => 'Paga',
    'cancelada' => 'Cancelada'
];
$status_comissao_classes = [
    'pendente' => 'badge-warning',
    'paga' => 'badge-success',
    'cancelada' => 'badge-danger',
];
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/comissoes_vendedor_listar.php" class="nav-link active">Comissões</a></li>
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
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/comissoes_vendedor_listar.php" class="nav-link active">
                    <i class="nav-icon fas fa-percent"></i><p>Comissões</p>
                </a>
            </li>

            <li class="nav-header">GESTÃO OPERACIONAL</li>
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-book-open"></i><p>Reservas</p>
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
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="nav-icon fas fa-calendar-alt"></i><p>Eventos <i class="right fas fa-angle-left"></i></p></a>
                <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/eventos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Eventos</p></a></li>
                     <?php if ($user_level === 'admin'): ?>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/evento_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Evento</p></a></li>
                     <?php endif; ?>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/evento_checkin_participante.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Check-in em Evento</p></a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/hoteis_listar.php" class="nav-link"><i class="nav-icon fas fa-hotel"></i><p>Hotéis</p></a>
            </li>
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/apartamentos_listar.php" class="nav-link"><i class="nav-icon fas fa-door-open"></i><p>Apartamentos</p></a>
            </li>
            <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/inquilinos_listar.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Inquilinos</p></a>
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
              <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <?php if ($user_level === 'admin'): // Filtros apenas para admin ?>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Filtros</h3></div>
            <div class="card-body">
                <form method="GET" action="<?php echo APP_URL; ?>/admin/comissoes_vendedor_listar.php" class="form-inline">
                    <div class="form-group mb-2 mr-sm-2">
                        <label for="filtro_vendedor_id" class="mr-sm-2">Vendedor:</label>
                        <select name="vendedor_id" id="filtro_vendedor_id" class="form-control select2bs4-filter" style="width: 250px;">
                            <option value="">Todos</option>
                            <?php foreach($vendedores_para_filtro as $vend): ?>
                                <option value="<?php echo $vend['id']; ?>" <?php echo ($filtro_vendedor_id == $vend['id']) ? 'selected' : ''; ?>>
                                    <?php echo escape_html($vend['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-2 mr-sm-2">
                        <label for="filtro_status_comissao" class="mr-sm-2">Status Comissão:</label>
                        <select name="status_comissao" id="filtro_status_comissao" class="form-control select2bs4-filter" style="width: 200px;">
                            <option value="">Todos</option>
                            <?php foreach($status_comissao_map as $key => $value): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($filtro_status_comissao == $key) ? 'selected' : ''; ?>>
                                    <?php echo $value; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-filter"></i> Filtrar</button>
                    <a href="<?php echo APP_URL; ?>/admin/comissoes_vendedor_listar.php" class="btn btn-secondary mb-2 ml-2"><i class="fas fa-eraser"></i> Limpar</a>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Lista de Comissões</h3></div>
              <div class="card-body">
                <?php if (empty($comissoes)): ?>
                  <div class="alert alert-info">Nenhuma comissão encontrada para os filtros aplicados.</div>
                <?php else: ?>
                  <div class="table-responsive">
                  <table id="comissoesTable" class="table table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                      <th>ID Comissão</th>
                      <th>Vendedor</th>
                      <th>Reserva (Apto/Hotel)</th>
                      <th>Inquilino Reserva</th>
                      <th class="text-right">Valor Base (R$)</th>
                      <th class="text-right">Percentual</th>
                      <th class="text-right">Valor Comissão (R$)</th>
                      <th>Status</th>
                      <th>Data Pag. Comissão</th>
                      <?php if ($user_level === 'admin'): ?>
                      <th>Ações</th>
                      <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($comissoes as $com): ?>
                    <tr>
                      <td><small><?php echo escape_html($com['id']); ?></small></td>
                      <td><?php echo escape_html($com['nome_vendedor']); ?></td>
                      <td>
                        <a href="<?php echo APP_URL; ?>/admin/reserva_editar.php?id=<?php echo $com['id_reserva_ref']; ?>" target="_blank" title="Ver Reserva">
                            <?php echo escape_html($com['desc_apartamento_reserva'] ?? $com['id_reserva_ref']); ?>
                        </a>
                      </td>
                      <td><?php echo escape_html($com['nome_inquilino_reserva']); ?></td>
                      <td class="text-right"><?php echo escape_html(number_format($com['valor_base_comissao'], 2, ',', '.')); ?></td>
                      <td class="text-right"><?php echo escape_html(number_format($com['percentual_comissao'] * 100, 2, ',', '.')); ?>%</td>
                      <td class="text-right"><strong><?php echo escape_html(number_format($com['valor_comissao'], 2, ',', '.')); ?></strong></td>
                      <td>
                        <span class="badge <?php echo $status_comissao_classes[$com['status']] ?? 'badge-light'; ?>">
                            <?php echo escape_html($status_comissao_map[$com['status']] ?? ucfirst($com['status'])); ?>
                        </span>
                      </td>
                      <td><?php echo $com['data_pagamento_comissao'] ? escape_html(date('d/m/Y H:i', strtotime($com['data_pagamento_comissao']))) : 'N/A'; ?></td>
                      <?php if ($user_level === 'admin'): ?>
                      <td>
                        <?php if ($com['status'] === 'pendente'): ?>
                          <button class="btn btn-xs btn-success btn-marcar-paga" data-id="<?php echo $com['id']; ?>" title="Marcar Comissão como Paga"><i class="fas fa-check-circle"></i> Pagar</button>
                        <?php elseif ($com['status'] === 'paga'): ?>
                          <button class="btn btn-xs btn-warning btn-marcar-pendente" data-id="<?php echo $com['id']; ?>" title="Reverter para Pendente"><i class="fas fa-undo"></i> Reverter</button>
                        <?php endif; ?>
                         <!-- Adicionar botão para cancelar comissão, se aplicável -->
                      </td>
                      <?php endif; ?>
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

  <!-- Modal para Marcar Paga/Pendente (usará AJAX) -->
  <div class="modal fade" id="updateComissaoStatusModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Atualizar Status da Comissão</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <form id="updateComissaoStatusForm">
                  <div class="modal-body">
                      <p>Tem certeza que deseja alterar o status desta comissão para <strong id="novoStatusComissaoDisplay"></strong>?</p>
                      <input type="hidden" name="comissao_id_update" id="comissao_id_update">
                      <input type="hidden" name="novo_status_comissao_val" id="novo_status_comissao_val">
                      <div class="form-group" id="dataPagamentoComissaoDiv" style="display:none;">
                          <label for="data_pagamento_comissao_input">Data do Pagamento da Comissão</label>
                          <input type="datetime-local" class="form-control" name="data_pagamento_comissao" id="data_pagamento_comissao_input">
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                      <button type="button" class="btn btn-primary" id="btnSubmitUpdateComissaoStatus">Confirmar</button>
                  </div>
              </form>
          </div>
      </div>
  </div>


  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.2/select2-bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#comissoesTable').DataTable({
        "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"},
        "order": [[0, "desc"]],
        "responsive": true,
         "columnDefs": [
            { "responsivePriority": 1, "targets": 1 }, // Vendedor
            { "responsivePriority": 2, "targets": 6 }, // Valor Comissão
            { "responsivePriority": 3, "targets": 7 }  // Status
        ]
    });
    $('.select2bs4-filter').select2({
      theme: 'bootstrap4',
      allowClear: true,
      placeholder: "Selecione"
    });

    // Marcar Paga
    $('.btn-marcar-paga').on('click', function() {
        var comissaoId = $(this).data('id');
        $('#comissao_id_update').val(comissaoId);
        $('#novo_status_comissao_val').val('paga');
        $('#novoStatusComissaoDisplay').text('PAGA');
        $('#dataPagamentoComissaoDiv').show();
        // Set current datetime for data_pagamento_comissao_input
        var now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        $('#data_pagamento_comissao_input').val(now.toISOString().slice(0,16));
        $('#updateComissaoStatusModal').modal('show');
    });

    // Marcar Pendente (Reverter)
    $('.btn-marcar-pendente').on('click', function() {
        var comissaoId = $(this).data('id');
        $('#comissao_id_update').val(comissaoId);
        $('#novo_status_comissao_val').val('pendente');
        $('#novoStatusComissaoDisplay').text('PENDENTE');
        $('#dataPagamentoComissaoDiv').hide();
        $('#data_pagamento_comissao_input').val(''); // Clear date
        $('#updateComissaoStatusModal').modal('show');
    });

    $('#btnSubmitUpdateComissaoStatus').on('click', function() {
        var comissaoId = $('#comissao_id_update').val();
        var novoStatus = $('#novo_status_comissao_val').val();
        var dataPagamento = (novoStatus === 'paga') ? $('#data_pagamento_comissao_input').val() : null;

        if (novoStatus === 'paga' && !dataPagamento) {
            alert('Por favor, informe a data do pagamento da comissão.');
            return;
        }

        $.ajax({
            url: '<?php echo APP_URL; ?>/controllers/ComissaoController.php', // CRIAR ESTE CONTROLLER E AÇÃO
            type: 'POST',
            data: {
                action: 'update_comissao_status',
                comissao_id: comissaoId,
                novo_status: novoStatus,
                data_pagamento_comissao: dataPagamento
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#updateComissaoStatusModal').modal('hide');
                    $('body').prepend('<div class="alert alert-success alert-dismissible fade show global-message" role="alert" style="position: fixed; top: 10px; right: 10px; z-index: 1050;">Status da comissão atualizado!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    setTimeout(function(){ location.reload(); }, 1500);
                } else {
                    alert('Erro ao atualizar status: ' + (response.message || 'Erro desconhecido.'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
                alert('Erro de comunicação ao tentar atualizar o status da comissão.');
            }
        });
    });


});
</script>
</body>
</html>
