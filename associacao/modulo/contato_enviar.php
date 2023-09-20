<?php
include_once('../funcoes.php');
include_once('../conexao.php');
if(isset($_GET['email']))
{

$nome               =  Limpa(trim($_GET['nome']));
$email              =  Limpa(trim($_GET['email']));
$celular            =  Limpa(trim($_GET['celular']));
$assunto            =  Limpa(trim($_GET['assunto']));
$texto              =  Limpa(trim($_GET['texto']));
$data_hora          =  date('Y-m-d H:i:s');
$ip                 =  Limpa(trim($_GET['ip']));
$status             = '1';
   
$sql = "SELECT 'achou' FROM  tb_contato WHERE ( email='$email' or nome='$nome' or celular='$celular') and status = 1; ";
$result = $pdo->query( $sql );

$achou = $result->fetchAll();
if (!$achou){ //não existe vai fazer o cadastro
            $insere = $pdo->query(
"
INSERT INTO `tb_contato` ( `nome`, `email`, `celular`, `assunto`, `texto`, `data_hora`, `ip`, `status`) VALUES ('$nome', '$email', '$celular', '$assunto', '$texto', '$data_hora', '$ip', '$status');                
"               
            );
    echo 'Mensagem enviada com sucesso!';
        }
    else{echo 'Sua mensagem já está sendo analisada, aguarde retorno no email ou celular.';}
    
}
else
{echo 'Houve um erro ao enviar sua mensagem...';}
?>



