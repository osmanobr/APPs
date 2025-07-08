<?php
$page_title = "Listar Pagamentos";
require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Pagamento.php';
require_once __DIR__ . '/../models/Inquilino.php'; // Para popular o select no modal de vínculo

protect_page('admin');

$pagamentoModel = new Pagamento();
$inquilinoModel = new Inquilino();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 25;
$offset = ($page - 1) * $limit;

$pagamentos = $pagamentoModel->getAllWithDetails($limit, $offset);
$totalPagamentos = $pagamentoModel->countAll();
$totalPages = ceil($totalPagamentos / $limit);

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

$status_classes = [
    'pendente' => 'badge-warning',
    'pago' => 'badge-success',
    'cancelado' => 'badge-danger',
    'reembolsado' => 'badge-info',
    'falhou' => 'badge-secondary',
    'parcial' => 'badge-primary'
];

$forma_pagamento_map = [
    'pix' => 'PIX',
    'cartao_credito' => 'Cartão de Crédito',
    'cartao_debito' => 'Cartão de Débito',
    'boleto' => 'Boleto',
    'dinheiro' => 'Dinheiro',
    'transferencia' => 'Transferência',
    'haver' => 'Utilizar Crédito (Haver)',
    'outro' => 'Outro'
];
$todosInquilinos = $inquilinoModel->getAll();
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php" class="nav-link active">Pagamentos</a></li>
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
           <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php" class="nav-link active">
              <i class="nav-icon fas fa-dollar-sign"></i><p>Pagamentos</p>
            </a>
          </li>
           <li class="nav-item">
            <a href="<?php echo APP_URL; ?>/admin/comissoes_vendedor_listar.php" class="nav-link">
              <i class="nav-icon fas fa-percent"></i><p>Comissões</p>
            </a>
          </li>
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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header"><h3 class="card-title">Registros de todos os pagamentos</h3></div>
              <div class="card-body">
                <?php if (empty($pagamentos)): ?>
                  <div class="alert alert-info">Nenhum pagamento registrado ainda.</div>
                <?php else: ?>
                  <div class="table-responsive">
                  <table id="pagamentosTable" class="table table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                      <th>ID Pag.</th>
                      <th>Data Criação</th>
                      <th>Inquilino</th>
                      <th>Origem</th>
                      <th>Descrição Origem</th>
                      <th class="text-right">Valor (R$)</th>
                      <th>Status</th>
                      <th>Forma Pag.</th>
                      <th>Data Venc.</th>
                      <th>Data Pag.</th>
                      <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pagamentos as $pag): ?>
                    <tr>
                      <td><small><?php echo escape_html($pag['id']); ?></small></td>
                      <td><?php echo escape_html(date('d/m/Y H:i', strtotime($pag['criado_em']))); ?></td>
                      <td><?php echo escape_html($pag['nome_inquilino'] ?? 'N/A'); ?><br><small><?php echo escape_html($pag['email_inquilino'] ?? ''); ?></small></td>
                      <td><?php echo ucfirst(escape_html($pag['origem_tipo'])); ?></td>
                      <td><small><?php echo escape_html($pag['descricao_origem']); ?></small></td>
                      <td class="text-right"><?php echo escape_html(number_format($pag['valor'], 2, ',', '.')); ?></td>
                      <td>
                        <span class="badge <?php echo $status_classes[$pag['status']] ?? 'badge-light'; ?>">
                          <?php echo strtoupper(escape_html($pag['status'])); ?>
                        </span>
                      </td>
                      <td><?php echo escape_html($forma_pagamento_map[$pag['forma_pagamento']] ?? ($pag['forma_pagamento'] ? ucfirst($pag['forma_pagamento']) : 'N/A')); ?></td>
                      <td><?php echo $pag['data_vencimento'] ? escape_html(date('d/m/Y', strtotime($pag['data_vencimento']))) : 'N/A'; ?></td>
                      <td><?php echo $pag['data_pagamento'] ? escape_html(date('d/m/Y H:i', strtotime($pag['data_pagamento']))) : 'N/A'; ?></td>
                      <td>
                        <button class="btn btn-xs btn-info btn-view-pagamento" data-id="<?php echo $pag['id']; ?>" title="Ver Detalhes/Editar Status">
                            <i class="fas fa-eye"></i>
                        </button>
                         <?php if ($pag['status'] === 'pendente'): ?>
                            <button class="btn btn-xs btn-success btn-registrar-pagamento-manual" data-id="<?php echo $pag['id']; ?>" data-valor="<?php echo $pag['valor']; ?>" title="Registrar Pagamento Manual">
                                <i class="fas fa-cash-register"></i>
                            </button>
                        <?php endif; ?>
                        <?php if (empty($pag['inquilino_id']) || ($pag['origem_tipo'] && empty($pag['origem_id'])) || $pag['status'] === 'pendente'): ?>
                            <button class="btn btn-xs btn-purple btn-vincular-pagamento"
                                    data-pagamento-id="<?php echo $pag['id']; ?>"
                                    data-pagamento-valor="<?php echo $pag['valor']; ?>"
                                    data-pagamento-descricao="<?php echo escape_html($pag['descricao'] . ($pag['descricao_origem'] ? ' | Origem: ' . $pag['descricao_origem'] : '')); ?>"
                                    title="Vincular Pagamento Órfão/Pendente a uma Duplicata ou Inquilino">
                                <i class="fas fa-link"></i> Vincular
                            </button>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
                  </div>
                  <?php if ($totalPages > 1): ?>
                  <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-3">
                      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                          <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                      <?php endfor; ?>
                    </ul>
                  </nav>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

    <div class="modal fade" id="viewPagamentoModal" tabindex="-1" role="dialog" aria-labelledby="viewPagamentoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPagamentoModalLabel">Detalhes do Pagamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="pagamentoDetailsContent"><p class="text-center">Carregando detalhes...</p></div>
                    <hr>
                    <h5>Alterar Status</h5>
                    <form id="updatePagamentoStatusForm">
                        <input type="hidden" name="pagamento_id_update" id="pagamento_id_update">
                        <div class="form-group">
                            <label for="novo_status_pagamento">Novo Status</label>
                            <select name="novo_status" id="novo_status_pagamento" class="form-control">
                                <option value="pendente">Pendente</option>
                                <option value="pago">Pago</option>
                                <option value="cancelado">Cancelado</option>
                                <option value="reembolsado">Reembolsado</option>
                                <option value="falhou">Falhou</option>
                            </select>
                        </div>
                         <div class="form-group">
                            <label for="forma_pagamento_update">Forma de Pagamento (se aplicando)</label>
                            <select name="forma_pagamento" id="forma_pagamento_update" class="form-control">
                                <option value="">Não alterar / Não aplicável</option>
                                <?php foreach($forma_pagamento_map as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data_pagamento_update">Data do Pagamento (se status 'Pago')</label>
                            <input type="datetime-local" class="form-control" name="data_pagamento" id="data_pagamento_update">
                        </div>
                        <div class="form-group">
                            <label for="descricao_update">Descrição Adicional / Motivo</label>
                            <textarea class="form-control" name="descricao" id="descricao_update" rows="2"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" id="btnSubmitUpdateStatus">Salvar Alteração de Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registrarPagamentoManualModal" tabindex="-1" role="dialog" aria-labelledby="registrarPagamentoManualModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registrarPagamentoManualModalLabel">Registrar Pagamento Manual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Registrando pagamento para o ID: <strong id="manual_pagamento_id_display"></strong></p>
                    <p>Valor: <strong id="manual_pagamento_valor_display"></strong></p>
                    <form id="registrarPagamentoManualForm">
                        <input type="hidden" name="manual_pagamento_id" id="manual_pagamento_id">
                        <div class="form-group">
                            <label for="manual_forma_pagamento">Forma de Pagamento <span class="text-danger">*</span></label>
                            <select name="manual_forma_pagamento" id="manual_forma_pagamento" class="form-control" required>
                                <option value="">Selecione...</option>
                                <?php foreach($forma_pagamento_map as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="manual_data_pagamento">Data do Pagamento <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="manual_data_pagamento" id="manual_data_pagamento" required value="<?php echo date('Y-m-d\TH:i'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="manual_descricao">Descrição / Observações</label>
                            <textarea class="form-control" name="manual_descricao" id="manual_descricao" rows="2" placeholder="Ex: Pagamento em dinheiro recebido por X"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnSubmitManualPayment">Confirmar Pagamento</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Vincular Pagamento -->
    <div class="modal fade" id="vincularPagamentoModal" tabindex="-1" role="dialog" aria-labelledby="vincularPagamentoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vincularPagamentoModalLabel">Vincular Pagamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formVincularPagamento">
                    <div class="modal-body">
                        <p>Vinculando Pagamento ID: <strong id="vincular_pagamento_id_display_modal"></strong></p>
                        <p>Valor do Pagamento: <strong id="vincular_pagamento_valor_display_modal"></strong></p>
                        <p>Descrição Original do Pagamento: <em id="vincular_pagamento_descricao_display_modal"></em></p>

                        <input type="hidden" name="pagamento_id_vincular_hidden" id="pagamento_id_vincular_hidden">

                        <div class="form-group">
                            <label for="vincular_inquilino_id_modal">Vincular ao Inquilino (opcional se vincular à duplicata)</label>
                            <select name="vincular_inquilino_id" id="vincular_inquilino_id_modal" class="form-control select2bs4-modal" style="width:100%;">
                                <option value="">Selecione um Inquilino...</option>
                                <?php
                                foreach($todosInquilinos as $inq) {
                                    echo "<option value='".escape_html($inq['id'])."'>".escape_html($inq['nome'])."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <hr>
                        <p><strong>OU</strong> Vincular a uma Duplicata Pendente:</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vincular_tipo_origem_modal">Tipo de Duplicata</label>
                                    <select name="vincular_tipo_origem" id="vincular_tipo_origem_modal" class="form-control">
                                        <option value="">Selecione...</option>
                                        <option value="reserva">Reserva</option>
                                        <option value="pedido">Pedido</option>
                                        <option value="estacionamento">Estacionamento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                 <div class="form-group">
                                    <label for="vincular_origem_id_modal">Duplicata Específica</label>
                                    <select name="vincular_origem_id" id="vincular_origem_id_modal" class="form-control select2bs4-modal-ajax" style="width:100%;" disabled>
                                        <option value="">Selecione o tipo primeiro...</option>
                                    </select>
                                    <small>Serão listadas duplicatas com status de pagamento pendente/parcial.</small>
                                    <div id="vincular_origem_detalhes_display" class="mt-1 text-muted small"></div>
                                 </div>
                            </div>
                        </div>
                         <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="confirmar_baixa_vinculo_modal" name="confirmar_baixa_vinculo" checked>
                            <label class="form-check-label" for="confirmar_baixa_vinculo_modal">
                                Tentar dar baixa automática na duplicata selecionada com este pagamento (se o valor for compatível e status da duplicata permitir).
                            </label>
                        </div>
                         <div class="form-group mt-2">
                            <label for="vincular_observacoes_modal">Observações para este Vínculo</label>
                            <textarea class="form-control" name="vincular_observacoes" id="vincular_observacoes_modal" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnSubmitVinculoPagamento">Salvar Vínculo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.2/select2-bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2bs4-modal').select2({
      theme: 'bootstrap4',
      dropdownParent: $('#vincularPagamentoModal'),
      allowClear: true,
      placeholder: "Selecione..."
    });
     $('.select2bs4-modal-ajax').select2({
      theme: 'bootstrap4',
      dropdownParent: $('#vincularPagamentoModal'),
      placeholder: "Selecione o tipo primeiro...",
      allowClear: true
    });

    $('.btn-view-pagamento').on('click', function() {
        var pagamentoId = $(this).data('id');
        $('#pagamento_id_update').val(pagamentoId);
        $('#pagamentoDetailsContent').html('<p class="text-center">Carregando detalhes...</p>');
        var row = $(this).closest('tr');
        var currentStatus = row.find('td:eq(6) span').text().trim().toLowerCase();
        var currentForma = row.find('td:eq(7)').text().trim();
        var currentDataPag = row.find('td:eq(9)').text().trim();
        $('#novo_status_pagamento').val(currentStatus);
        $('#pagamentoDetailsContent').html(
            `<strong>ID:</strong> ${pagamentoId}<br>
             <strong>Inquilino:</strong> ${row.find('td:eq(2)').html()}<br>
             <strong>Origem:</strong> ${row.find('td:eq(3)').text()} - ${row.find('td:eq(4)').text()}<br>
             <strong>Valor:</strong> ${row.find('td:eq(5)').text()}<br>
             <strong>Status Atual:</strong> ${currentStatus.toUpperCase()}<br>
             <strong>Forma Pag. Atual:</strong> ${currentForma}<br>
             <strong>Data Venc.:</strong> ${row.find('td:eq(8)').text()}<br>
             <strong>Data Pag. Atual:</strong> ${currentDataPag}`
        );
        $('#viewPagamentoModal').modal('show');
    });

    $('#btnSubmitUpdateStatus').on('click', function() {
        var pagamentoId = $('#pagamento_id_update').val();
        var novoStatus = $('#novo_status_pagamento').val();
        var formaPagamento = $('#forma_pagamento_update').val();
        var dataPagamento = $('#data_pagamento_update').val();
        var descricao = $('#descricao_update').val();
        $.ajax({
            url: '<?php echo APP_URL; ?>/controllers/PagamentoController.php',
            type: 'POST',
            data: {
                action: 'update_payment_details',
                id: pagamentoId,
                status: novoStatus,
                forma_pagamento: formaPagamento,
                data_pagamento: dataPagamento,
                descricao: descricao
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#viewPagamentoModal').modal('hide');
                    $('body').prepend('<div class="alert alert-success alert-dismissible fade show global-message" role="alert" style="position: fixed; top: 10px; right: 10px; z-index: 1050;">Status do pagamento atualizado!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    setTimeout(function(){ location.reload(); }, 1500);
                } else {
                    alert('Erro ao atualizar status: ' + (response.message || 'Erro desconhecido.'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
                alert('Erro de comunicação ao tentar atualizar o status do pagamento.');
            }
        });
    });

    $('.btn-registrar-pagamento-manual').on('click', function(){
        var pagamentoId = $(this).data('id');
        var valorPagamento = $(this).data('valor');
        $('#manual_pagamento_id').val(pagamentoId);
        $('#manual_pagamento_id_display').text(pagamentoId);
        $('#manual_pagamento_valor_display').text(parseFloat(valorPagamento).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
        $('#manual_forma_pagamento').val(null).trigger('change');;
        $('#manual_data_pagamento').val('<?php echo date('Y-m-d\TH:i'); ?>');
        $('#manual_descricao').val('');
        $('#registrarPagamentoManualModal').modal('show');
    });

    $('#btnSubmitManualPayment').on('click', function() {
        var pagamentoId = $('#manual_pagamento_id').val();
        var formaPagamento = $('#manual_forma_pagamento').val();
        var dataPagamento = $('#manual_data_pagamento').val();
        var descricao = $('#manual_descricao').val();
        if (!formaPagamento) { alert('Por favor, selecione a forma de pagamento.'); return; }
        if (!dataPagamento) { alert('Por favor, informe a data do pagamento.'); return; }
        $.ajax({
            url: '<?php echo APP_URL; ?>/controllers/PagamentoController.php',
            type: 'POST',
            data: {
                action: 'update_payment_details',
                id: pagamentoId,
                status: 'pago',
                forma_pagamento: formaPagamento,
                data_pagamento: dataPagamento,
                descricao: descricao
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#registrarPagamentoManualModal').modal('hide');
                     $('body').prepend('<div class="alert alert-success alert-dismissible fade show global-message" role="alert" style="position: fixed; top: 10px; right: 10px; z-index: 1050;">Pagamento manual registrado com sucesso!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    setTimeout(function(){ location.reload(); }, 1500);
                } else {
                    alert('Erro ao registrar pagamento manual: ' + (response.message || 'Erro desconhecido.'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                 console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
                alert('Erro de comunicação ao tentar registrar o pagamento manual.');
            }
        });
    });

    $('.btn-vincular-pagamento').on('click', function() {
        var pagId = $(this).data('pagamento-id');
        var pagValor = $(this).data('pagamento-valor');
        var pagDesc = $(this).data('pagamento-descricao');

        $('#pagamento_id_vincular_hidden').val(pagId);
        $('#vincular_pagamento_id_display_modal').text(pagId);
        $('#vincular_pagamento_valor_display_modal').text(parseFloat(pagValor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
        $('#vincular_pagamento_descricao_display_modal').text(pagDesc);

        $('#vincular_inquilino_id_modal').val(null).trigger('change');
        $('#vincular_tipo_origem_modal').val('');
        $('#vincular_origem_id_modal').html('<option value=\"\">Selecione o tipo primeiro...</option>').prop('disabled', true).trigger('change');
        $('#vincular_origem_detalhes_display').empty();
        $('#confirmar_baixa_vinculo_modal').prop('checked', true);
        $('#vincular_observacoes_modal').val('');

        $('#vincularPagamentoModal').modal('show');
    });

    $('#vincular_tipo_origem_modal').on('change', function() {
        var tipoOrigem = $(this).val();
        var selectOrigemId = $('#vincular_origem_id_modal');
        selectOrigemId.html('<option value=\"\">Carregando...</option>').prop('disabled', true);
        $('#vincular_origem_detalhes_display').empty();

        if (!tipoOrigem) {
            selectOrigemId.html('<option value=\"\">Selecione o tipo primeiro...</option>').prop('disabled', true).trigger('change');
            return;
        }

        $.ajax({
            url: '<?php echo APP_URL; ?>/controllers/PagamentoController.php',
            type: 'GET',
            data: {
                action: 'get_duplicatas_pendentes',
                tipo_origem: tipoOrigem
            },
            dataType: 'json',
            success: function(response) {
                selectOrigemId.empty().prop('disabled', false);
                if (response.success && response.data.length > 0) {
                    selectOrigemId.append('<option value=\"\">Selecione uma duplicata...</option>');
                    response.data.forEach(function(item) {
                        selectOrigemId.append(`<option value="${item.id}" data-valor="${item.valor_pendente}" data-inquilino-id="${item.inquilino_id || ''}" data-inquilino-nome="${item.nome_inquilino || ''}">${item.descricao_completa}</option>`);
                    });
                } else {
                    selectOrigemId.append('<option value=\"\">Nenhuma duplicata pendente encontrada</option>');
                }
                selectOrigemId.trigger('change');
            },
            error: function() {
                selectOrigemId.html('<option value=\"\">Erro ao buscar duplicatas</option>').prop('disabled', false);
                alert('Erro ao buscar duplicatas pendentes.');
            }
        });
    });

    $('#vincular_origem_id_modal').on('change', function(){
        var selectedOption = $(this).find('option:selected');
        var detalhesDiv = $('#vincular_origem_detalhes_display');
        if(selectedOption.val()){
            var valorPendente = parseFloat(selectedOption.data('valor')).toFixed(2);
            var inquilinoNome = selectedOption.data('inquilino-nome');
            detalhesDiv.html(`Valor Pendente: R$ ${valorPendente.replace('.',',')} <br> Inquilino Associado: ${inquilinoNome || 'N/A'}`);
            var inquilinoIdDaDuplicata = selectedOption.data('inquilino-id');
            if (inquilinoIdDaDuplicata) {
                 $('#vincular_inquilino_id_modal').val(inquilinoIdDaDuplicata).trigger('change');
            }
        } else {
            detalhesDiv.empty();
        }
    });

    $('#btnSubmitVinculoPagamento').on('click', function() {
        var pagamentoId = $('#pagamento_id_vincular_hidden').val();
        var inquilinoId = $('#vincular_inquilino_id_modal').val();
        var tipoOrigem = $('#vincular_tipo_origem_modal').val();
        var origemId = $('#vincular_origem_id_modal').val();
        var confirmarBaixa = $('#confirmar_baixa_vinculo_modal').is(':checked');
        var observacoes = $('#vincular_observacoes_modal').val();

        if (!inquilinoId && !origemId) {
            alert('Você deve selecionar um inquilino ou uma duplicata para vincular o pagamento.');
            return;
        }
        if (origemId && !tipoOrigem) {
            alert('Se uma duplicata foi selecionada, o tipo de duplicata também deve ser informado.');
            return;
        }

        $.ajax({
            url: '<?php echo APP_URL; ?>/controllers/PagamentoController.php',
            type: 'POST',
            data: {
                action: 'vincular_pagamento_orfao',
                pagamento_id: pagamentoId,
                vincular_inquilino_id: inquilinoId,
                vincular_tipo_origem: tipoOrigem,
                vincular_origem_id: origemId,
                confirmar_baixa: confirmarBaixa,
                observacoes: observacoes
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#vincularPagamentoModal').modal('hide');
                    $('body').prepend(`<div class="alert alert-success alert-dismissible fade show global-message" role="alert" style="position: fixed; top: 10px; right: 10px; z-index: 1050;">${response.message}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`);
                    setTimeout(function(){ location.reload(); }, 2000);
                } else {
                    alert('Erro ao vincular pagamento: ' + (response.message || 'Erro desconhecido.'));
                }
            },
            error: function() {
                alert('Erro de comunicação ao tentar vincular o pagamento.');
            }
        });
    });
});
</script>
</body>
</html>
