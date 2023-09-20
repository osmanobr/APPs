<?php
//include_once('conexao.php');
    $ip1  = @$_SERVER['HTTP_CLIENT_IP'];
    $ip2 = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $ip3  = $_SERVER['REMOTE_ADDR'];
//ip
    if(filter_var($ip1, FILTER_VALIDATE_IP))
    {
        $ip_visita = $ip1;
    }
    elseif(filter_var($ip2, FILTER_VALIDATE_IP))
    {
        $ip_visita = $ip2;
    }
    else
    {
        $ip_visita = $ip3;
    }

    $urlatual= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $agora = date('Y-m-d H:i:s');

    $gravalog = $pdo->query("INSERT INTO tb_log(data_hora, log) VALUES ('$agora', '$ip_visita  $urlatual');" );
?>
