<?php
include_once('../funcoes.php');
include_once('../conexao.php');
echo $_GET['uf'];
if(isset($_GET['nome']))
{

$nome               =  Limpa(trim($_GET['nome']));
$email              =  Limpa(trim($_GET['email']));
$celular            =  Limpa(trim($_GET['celular']));
$cpf                =  Limpa(trim($_GET['cpf']));
$rg                 =  Limpa(trim($_GET['rg']));
$data_nascimento    =  Limpa(trim($_GET['data_nascimento']));
$cidade_uf_origem   =  Limpa(trim($_GET['cidade_uf_origem']));
$profissao          =  Limpa(trim($_GET['profissao']));
$interesse          =  Limpa(trim($_GET['interesse']));
$endereco           =  Limpa(trim($_GET['endereco']));
$bairro             =  Limpa(trim($_GET['bairro']));
$cidade             =  Limpa(trim($_GET['cidade']));
$uf                 =  Limpa(trim($_GET['uf']));
$tipo_filiado       =  Limpa(trim($_GET['tipo_filiado']));
$sobre_mim          = '';// Limpa(trim($_GET['sobre_mim']));
$data_filiacao      =  date('Y-m-d');
$status             =  '1';
   
$sql = "SELECT 'achou' FROM  tb_filiado WHERE (cpf='$cpf' or email='$email' or rg='$rg' or celular='$celular');";
$result = $pdo->query( $sql );

$achou = $result->fetchAll();
if (!$achou){ //não existe vai fazer o cadastro
            $insere = $pdo->query(
            "INSERT INTO tb_filiado (
                    nome,
                    email,
                    celular,
                    cpf,
                    rg,
                    data_nascimento,
                    cidade_uf_origem,
                    profissao,
                    interesse,
                    endereco,
                    bairro,
                    cidade,
                    uf,
                    tipo_filiado,
                    sobre_mim,
                    data_filiacao,
                    status
                )
            VALUES (
                    '$nome', 
                    '$email', 
                    '$celular', 
                    '$cpf', 
                    '$rg', 
                    '$data_nascimento', 
                    '$cidade_uf_origem', 
                    '$profissao', 
                    '$interesse', 
                    '$endereco', 
                    '$bairro', 
                    '$cidade', 
                    '$uf', 
                    '$tipo_filiado', 
                    '$sobre_mim', 
                    '$data_filiacao',
                    '$status'
                );"
            );
    echo 'Cadastro efetuado com sucesso!';
        } else{ echo 'Seu cadastro será analisado, aguarde retorno no email ou celular!';}
    
}
else
{echo 'Houve um erro ao enviar seu cadastro';}
    


?>