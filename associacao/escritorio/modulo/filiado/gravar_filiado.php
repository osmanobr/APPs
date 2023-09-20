<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_filiado"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
            'nome'           => Limpa(trim($_POST["tx_nome"])),
            'email'          => Limpa(trim($_POST["tx_email"])),
            'celular'        => Limpa(trim($_POST["tx_celular"])),
            'cpf'            => Limpa(trim($_POST["tx_cpf"])),
            'rg'             => Limpa(trim($_POST["tx_rg"])),
            'data_nascimento'    => Limpa(trim($_POST["tx_data_nascimento"])),
            'cidade_uf_origem'   => Limpa(trim($_POST["tx_cidade_uf_origem"])),
            'profissao'      => Limpa(trim($_POST["tx_profissao"])),
            'interesse'      => Limpa(trim($_POST["tx_interesse"])),
            'foto_perfil'    => Limpa(trim($_POST["tx_foto_perfil"])),
            'sobre_mim'      => Limpa(trim($_POST["tx_sobre_mim"])),
            'endereco'       => Limpa(trim($_POST["tx_endereco"])),
            'bairro'         => Limpa(trim($_POST["tx_bairro"])),
            'cidade'         => Limpa(trim($_POST["tx_cidade"])),
            'uf'             => Limpa(trim($_POST["tx_uf"])),
            'data_filiacao'  => date('Y-m-d'),
            'tipo_filiado'   => Limpa(trim($_POST["tx_tipo_filiado"])),
            'senha'          => Limpa(trim($_POST["tx_cpf"])),
            'exibir_time'    => Limpa(trim($_POST["tx_exibir_time"])),
            'status'         => '1'
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
            'nome'           => Limpa(trim($_POST["tx_nome"])),
            'email'          => Limpa(trim($_POST["tx_email"])),
            'celular'        => Limpa(trim($_POST["tx_celular"])),
            'cpf'            => Limpa(trim($_POST["tx_cpf"])),
            'rg'             => Limpa(trim($_POST["tx_rg"])),
            'data_nascimento'    => Limpa(trim($_POST["tx_data_nascimento"])),
            'cidade_uf_origem'   => Limpa(trim($_POST["tx_cidade_uf_origem"])),
            'profissao'      => Limpa(trim($_POST["tx_profissao"])),
            'interesse'      => Limpa(trim($_POST["tx_interesse"])),
            'foto_perfil'    => Limpa(trim($_POST["tx_foto_perfil"])),
            'sobre_mim'      => Limpa(trim($_POST["tx_sobre_mim"])),
            'endereco'       => Limpa(trim($_POST["tx_endereco"])),
            'bairro'         => Limpa(trim($_POST["tx_bairro"])),
            'cidade'         => Limpa(trim($_POST["tx_cidade"])),
            'uf'             => Limpa(trim($_POST["tx_uf"])),
           // 'data_filiacao'  => Limpa(trim($_POST["tx_data_filiacao"])),
            'tipo_filiado'   => Limpa(trim($_POST["tx_tipo_filiado"])),
            //a senha somente o usuario troca
            'exibir_time'    => Limpa(trim($_POST["tx_exibir_time"]))
         //   'status'         => Limpa(trim($_POST["tx_status"])),
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
?>

