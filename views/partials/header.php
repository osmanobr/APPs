<?php
// Este arquivo deve ser incluído no topo de todas as páginas visíveis ao usuário.
// Ele inicia configurações, sessões e pode incluir o head HTML comum.
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../core/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape_html(SITE_NAME); ?><?php echo isset($page_title) ? ' - ' . escape_html($page_title) : ''; ?></title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Custom CSS (se necessário) -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/custom.css">
    <style>
        /* Estilos rápidos para a página de login */
        .login-page {
            background-color: #f4f6f9; /* Cor de fundo padrão do AdminLTE */
        }
        .login-box {
            width: 360px;
        }
        /* Para mensagens de erro/sucesso */
        .message-container {
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="<?php echo isset($body_class) ? $body_class : ''; ?>">
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show global-message" role="alert" style="position: fixed; top: 10px; right: 10px; z-index: 1050;">
            <?php echo escape_html($_SESSION['error_message']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show global-message" role="alert" style="position: fixed; top: 10px; right: 10px; z-index: 1050;">
            <?php echo escape_html($_SESSION['success_message']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- O conteúdo da página virá aqui -->
