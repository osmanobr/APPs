<?php /*
@session_start();
include_once('limpa.php');

if ((isset($_SESSION['usuario']))and(isset($_SESSION['senha'])))
{    $usuario = Limpar($_SESSION['usuario']);
     $senha=Limpar($_SESSION['senha']);
      $sql = $pdo->query("SELECT* FROM tb_site where  ativo = 1 and(usuario = '$usuario' or emails='$usuario' or telefones = '$usuario' or proprietario = '$usuario' or id='$usuario') and (senha='$senha') LIMIT 1");
    $qr = $sql->fetchAll(PDO::FETCH_ASSOC);
    if(!$qr)  
    {//tudo errado não logou
      echo "<meta http-equiv='refresh' content='0, url=../?modulo=logar'>";
        exit;
    }  
}
else
{ echo "<meta http-equiv='refresh' content='0, url=../?modulo=logar'>";
    exit;
} */
?>