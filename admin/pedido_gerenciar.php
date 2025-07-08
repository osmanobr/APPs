<?php
// Página para criar um novo pedido (se nenhum ID fornecido) ou gerenciar um existente.
$pedido_id = $_GET['id'] ?? null;
$page_title = $pedido_id ? "Gerenciar Pedido" : "Novo Pedido";

require_once __DIR__ . '/../views/partials/header.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/ItemPedido.php';
require_once __DIR__ . '/../models/Inquilino.php'; // Para select de inquilinos
require_once __DIR__ . '/../models/Usuario.php';   // Para select de funcionários (se necessário)

// Níveis de acesso: admin, funcionario, vendedor
protect_page(['admin', 'funcionario', 'vendedor']);

$pedidoModel = new Pedido();
$itemPedidoModel = new ItemPedido();
$inquilinoModel = new Inquilino();

$pedido = null;
$itens_do_pedido = [];
$is_new_pedido = !$pedido_id;

if ($pedido_id) {
    $pedido = $pedidoModel->getByIdWithDetails($pedido_id);
    if ($pedido) {
        $itens_do_pedido = $itemPedidoModel->getByPedidoId($pedido_id);
        $page_title .= " (ID: " . escape_html($pedido_id) . ")";
    } else {
        $_SESSION['error_message'] = "Pedido não encontrado.";
        // Não redirecionar daqui, pois pode ser que o usuário queira criar um novo se o ID for inválido
        // redirect(APP_URL . '/admin/pedidos_listar.php');
        // exit;
        $is_new_pedido = true; // Tratar como novo se ID inválido
        $pedido_id = null; // Limpar ID inválido
        $page_title = "Novo Pedido";
    }
}

$inquilinos = $inquilinoModel->getAll(); // Para o select de inquilino ao criar novo pedido
// $funcionarios = $pedidoModel->getAllFuncionariosSimple(); // Se quiser selecionar funcionário manualmente

$user_name = escape_html($_SESSION['user_name'] ?? 'Usuário');
$user_level = escape_html($_SESSION['user_level'] ?? 'N/A');

$status_pedido_map = [
    'aberto' => 'Aberto',
    'fechado' => 'Fechado (Aguardando Pag.)',
    'pago' => 'Pago',
    'cancelado' => 'Cancelado'
];
$status_pedido_classes = [
    'aberto' => 'badge-primary',
    'fechado' => 'badge-info',
    'pago' => 'badge-success',
    'cancelado' => 'badge-danger',
];
$forma_pagamento_pedido_map = [
    'prepago' => 'Pré-Pago (Pago no ato)',
    'haver' => 'Lançar na Conta (Haver)',
    'dinheiro' => 'Dinheiro (no fechamento)',
    'cartao' => 'Cartão (no fechamento)',
    'pix' => 'PIX (no fechamento)'
];
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link">Pedidos</a></li>
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
                    <i class="nav-icon fas fa-book-open"></i><p>Reservas <i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reservas_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Reservas</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/reserva_criar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Criar Reserva</p></a></li>
                </ul>
            </li>
             <li class="nav-item">
                <a href="<?php echo APP_URL; ?>/admin/estacionamentos_listar.php" class="nav-link">
                    <i class="nav-icon fas fa-car"></i><p>Estacionamento <i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/estacionamentos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Entradas/Saídas</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/estacionamento_checkin.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Registrar Check-in</p></a></li>
                </ul>
            </li>
            <li class="nav-item menu-open">
                <a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link active">
                    <i class="nav-icon fas fa-concierge-bell"></i><p>Pedidos <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php" class="nav-link"><i class="far fa-circle nav-icon"></i> <p>Listar Pedidos</p></a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>/admin/pedido_gerenciar.php" class="nav-link active"><i class="far fa-circle nav-icon"></i> <p>Novo Pedido</p></a></li>
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
              <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/pedidos_listar.php">Pedidos</a></li>
              <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <?php if ($is_new_pedido): ?>
            <div class="card card-success">
                <div class="card-header"><h3 class="card-title">Iniciar Novo Pedido</h3></div>
                <form action="<?php echo APP_URL; ?>/controllers/PedidoController.php" method="POST">
                    <input type="hidden" name="action" value="create_pedido">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inquilino_id">Inquilino <span class="text-danger">*</span></label>
                            <select class="form-control select2bs4" id="inquilino_id" name="inquilino_id" required style="width: 100%;">
                                <option value="">Selecione um Inquilino</option>
                                <?php foreach ($inquilinos as $inq): ?>
                                    <option value="<?php echo escape_html($inq['id']); ?>"><?php echo escape_html($inq['nome']); ?> (<?php echo escape_html($inq['email'] ?? 'N/A'); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="forma_pagamento_prevista">Forma de Pagamento Prevista</label>
                            <select class="form-control select2bs4" id="forma_pagamento_prevista" name="forma_pagamento_prevista" style="width: 100%;">
                                <option value="">A definir no fechamento</option>
                                <?php foreach($forma_pagamento_pedido_map as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="observacoes_pedido">Observações do Pedido</label>
                            <textarea class="form-control" id="observacoes_pedido" name="observacoes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Criar Pedido e Adicionar Itens</button>
                    </div>
                </form>
            </div>
        <?php else: // Gerenciando um pedido existente ?>
            <div class="row">
                <div class="col-md-7">
                    <div class="card card-primary <?php echo ($pedido['status'] !== 'aberto') ? 'card-outline' : ''; ?>">
                        <div class="card-header">
                            <h3 class="card-title">Itens do Pedido</h3>
                             <?php if ($pedido['status'] === 'aberto'): ?>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addItemModal">
                                    <i class="fas fa-plus"></i> Adicionar Item
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($itens_do_pedido)): ?>
                                <p class="p-3">Nenhum item adicionado a este pedido ainda.</p>
                            <?php else: ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-right">Qtd.</th>
                                            <th class="text-right">Preço Unit.</th>
                                            <th class="text-right">Subtotal</th>
                                            <?php if ($pedido['status'] === 'aberto'): ?>
                                            <th>Ação</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($itens_do_pedido as $item): ?>
                                        <tr>
                                            <td><?php echo escape_html($item['nome_item']); ?></td>
                                            <td class="text-right"><?php echo $item['quantidade']; ?></td>
                                            <td class="text-right">R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                                            <td class="text-right">R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                                            <?php if ($pedido['status'] === 'aberto'): ?>
                                            <td>
                                                <a href="<?php echo APP_URL; ?>/controllers/PedidoController.php?action=delete_item&item_id=<?php echo $item['id']; ?>&pedido_id=<?php echo $pedido_id; ?>"
                                                   class="btn btn-xs btn-danger"
                                                   onclick="return confirm('Tem certeza que deseja remover este item?');">
                                                   <i class="fas fa-trash"></i>
                                                </a>
                                                <!-- Botão Editar Item (abre modal) - TODO -->
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                         <?php if (!empty($itens_do_pedido)): ?>
                        <div class="card-footer text-right">
                            <strong>Total do Pedido: R$ <?php echo number_format($pedido['valor_total_calculado'], 2, ',', '.'); ?></strong>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card card-info  <?php echo ($pedido['status'] !== 'aberto') ? 'card-outline' : ''; ?>">
                        <div class="card-header"><h3 class="card-title">Detalhes do Pedido</h3></div>
                        <div class="card-body">
                            <p><strong>Inquilino:</strong> <?php echo escape_html($pedido['nome_inquilino']); ?></p>
                            <p><strong>Funcionário:</strong> <?php echo escape_html($pedido['nome_funcionario'] ?? 'N/A'); ?></p>
                            <p><strong>Criado em:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['criado_em'])); ?></p>
                            <p><strong>Status do Pedido:</strong> <span class="badge <?php echo $status_pedido_classes[$pedido['status']] ?? 'badge-light'; ?>"><?php echo $status_pedido_map[$pedido['status']] ?? ucfirst($pedido['status']); ?></span></p>
                            <?php if ($pedido['status_pagamento_atual']): ?>
                            <p><strong>Status do Pagamento:</strong> <span class="badge <?php echo $status_pagamento_classes[$pedido['status_pagamento_atual']] ?? 'badge-light'; ?>"><?php echo strtoupper(escape_html($pedido['status_pagamento_atual'])); ?></span></p>
                            <?php endif; ?>
                            <p><strong>Forma de Pagamento Prevista:</strong> <?php echo escape_html($forma_pagamento_pedido_map[$pedido['forma_pagamento_prevista']] ?? ($pedido['forma_pagamento_prevista'] ? ucfirst($pedido['forma_pagamento_prevista']) : 'N/A')); ?></p>
                            <p><strong>Observações:</strong> <?php echo nl2br(escape_html($pedido['observacoes'] ?? 'Nenhuma')); ?></p>

                            <hr>
                            <?php if ($pedido['status'] === 'aberto'): ?>
                                <form action="<?php echo APP_URL; ?>/controllers/PedidoController.php" method="POST" onsubmit="return confirm('Tem certeza que deseja fechar este pedido? Após fechado, não será possível adicionar mais itens e um pagamento será gerado.');">
                                    <input type="hidden" name="action" value="fechar_pedido">
                                    <input type="hidden" name="pedido_id" value="<?php echo $pedido_id; ?>">
                                     <div class="form-group">
                                        <label for="forma_pagamento_final">Forma de Pagamento ao Fechar (se diferente da prevista)</label>
                                        <select class="form-control select2bs4" id="forma_pagamento_final" name="forma_pagamento_final" style="width: 100%;">
                                            <option value="">Manter Prevista ou Definir Agora</option>
                                            <?php foreach($forma_pagamento_pedido_map as $key => $value): ?>
                                                <option value="<?php echo $key; ?>" <?php echo ($pedido['forma_pagamento_prevista'] == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-block"><i class="fas fa-lock"></i> Fechar Pedido e Gerar Pagamento</button>
                                </form>
                            <?php elseif ($pedido['status'] === 'fechado' && $pedido['status_pagamento_atual'] === 'pendente'): ?>
                                <p class="text-info">Este pedido está fechado e aguardando pagamento.</p>
                                <a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php?origem_tipo=pedido&origem_id=<?php echo $pedido_id; ?>" class="btn btn-info btn-block"><i class="fas fa-dollar-sign"></i> Ver/Registrar Pagamento</a>
                            <?php elseif ($pedido['status'] === 'pago'): ?>
                                 <p class="text-success"><i class="fas fa-check-circle"></i> Este pedido foi pago.</p>
                                 <?php if($pedido['pagamento_principal_id']): ?>
                                    <a href="<?php echo APP_URL; ?>/admin/pagamentos_listar.php?id_pag=<?php echo $pedido['pagamento_principal_id']; ?>" class="btn btn-outline-success btn-block"><i class="fas fa-receipt"></i> Ver Detalhes do Pagamento</a>
                                 <?php endif; ?>
                            <?php elseif ($pedido['status'] === 'cancelado'): ?>
                                 <p class="text-danger"><i class="fas fa-times-circle"></i> Este pedido foi cancelado.</p>
                            <?php endif; ?>

                            <?php if ($user_level === 'admin' && $pedido['status'] !== 'aberto'): // Admin pode alterar status de pedidos não abertos ?>
                                <hr><h5>Ações Administrativas</h5>
                                <form action="<?php echo APP_URL; ?>/controllers/PedidoController.php" method="POST" class="mt-2">
                                    <input type="hidden" name="action" value="update_pedido_status">
                                    <input type="hidden" name="pedido_id_status_update" value="<?php echo $pedido_id; ?>">
                                    <div class="form-group">
                                        <label for="novo_status_pedido">Alterar Status do Pedido Para:</label>
                                        <select name="novo_status_pedido" class="form-control">
                                            <?php foreach($status_pedido_map as $key => $value):
                                                if($key === $pedido['status']) continue; // Não mostrar o status atual como opção
                                            ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php if($pedido['status'] !== 'pago' && $pedido['status_pagamento_atual'] !== 'pago'): // Se for marcar como PAGO manualmente ?>
                                    <div class="form-group" id="div_forma_pagamento_confirmada" style="display:none;">
                                        <label for="forma_pagamento_confirmada">Forma de Pagamento Confirmada (ao marcar como PAGO)</label>
                                        <select name="forma_pagamento_confirmada" id="forma_pagamento_confirmada" class="form-control">
                                            <option value="">Selecione...</option>
                                             <?php foreach($forma_pagamento_pedido_map as $key => $value): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja alterar o status deste pedido?');">Atualizar Status</button>
                                </form>
                                <script>
                                    document.querySelector('select[name="novo_status_pedido"]').addEventListener('change', function(){
                                        var divFp = document.getElementById('div_forma_pagamento_confirmada');
                                        if(divFp) { divFp.style.display = (this.value === 'pago') ? 'block' : 'none'; }
                                    });
                                </script>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; // Fim do if $is_new_pedido ?>
      </div>
    </section>
  </div>

  <!-- Modal Adicionar Item -->
  <?php if (!$is_new_pedido && $pedido && $pedido['status'] === 'aberto'): ?>
  <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Adicionar Item ao Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="<?php echo APP_URL; ?>/controllers/PedidoController.php" method="POST">
                <input type="hidden" name="action" value="add_item">
                <input type="hidden" name="pedido_id" value="<?php echo $pedido_id; ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nome_item">Nome do Item <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nome_item" required>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="quantidade">Quantidade <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="quantidade" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="preco_unitario">Preço Unitário (R$) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control" name="preco_unitario" required>
                            </div>
                        </div>
                    </div>
                    <!-- Opcional: Select de produto_id se houver catálogo -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar Item</button>
                </div>
            </form>
        </div>
    </div>
  </div>
  <?php endif; ?>


  <footer class="main-footer"><strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo escape_html(SITE_NAME); ?></a>.</strong> Todos os direitos reservados.</footer>
</div>
<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.2/select2-bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2bs4').select2({
      theme: 'bootstrap4',
      allowClear: true,
      placeholder: "Selecione..."
    });
});
</script>
</body>
</html>
