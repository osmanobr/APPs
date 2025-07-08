<?php
// Este arquivo deve ser incluído no final de todas as páginas visíveis ao usuário.
// Ele pode incluir scripts JS comuns e fechar tags HTML.
?>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Custom JS (se necessário) -->
    <!-- <script src="<?php echo APP_URL; ?>/assets/js/custom.js"></script> -->

    <script>
        // Script para fechar alertas automaticamente após alguns segundos
        $(document).ready(function() {
            setTimeout(function() {
                $(".global-message").alert('close');
            }, 5000); // Fecha após 5 segundos
        });
    </script>
</body>
</html>
