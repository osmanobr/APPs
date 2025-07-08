<?php
$page_title = "Login";
$body_class = "hold-transition login-page"; // Classe do AdminLTE para páginas de login
require_once __DIR__ . '/views/partials/header.php'; // Inclui o header HTML e configs

// Se o usuário já estiver logado, redireciona para o dashboard apropriado
if (is_logged_in()) {
    $user_level = get_user_access_level();
    // Definir dashboards específicos por nível se necessário
    // Ex: if ($user_level === 'admin') redirect(APP_URL . '/admin/dashboard.php');
    redirect(APP_URL . '/admin/dashboard.php'); // Dashboard padrão por enquanto
}
?>

<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo APP_URL; ?>"><b><?php echo escape_html(SITE_NAME); ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Faça login para iniciar sua sessão</p>

            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger text-center message-container">
                    <?php echo escape_html($_SESSION['login_error']); ?>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success text-center message-container">
                    Usuário registrado com sucesso! Faça login para continuar.
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/controllers/AuthController.php?action=login" method="post">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="senha" placeholder="Senha" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                Lembrar-me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- Opções de registrar ou esqueci senha (descomentar e implementar se necessário)
            <p class="mb-1 mt-3">
                <a href="forgot-password.html">Esqueci minha senha</a>
            </p>
            <p class="mb-0">
                <a href="register.php" class="text-center">Registrar um novo membro</a>
            </p>
            -->
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<?php
// Inclui o footer (scripts JS, etc.)
// Não precisamos do footer completo do AdminLTE aqui, apenas os scripts básicos.
?>
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
    $(document).ready(function() {
        // Foco no campo de email ao carregar
        $('input[name="email"]').focus();

        // Remover mensagens de erro após alguns segundos
        setTimeout(function() {
            $('.message-container').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000); // 5 segundos
    });
</script>
</body>
</html>
