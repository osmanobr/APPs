<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_fornecedor"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
        'id_associado'      => Limpa(trim($_POST["tx_id_associado"])), 
        'cnpj_cpf'          => Limpa(trim($_POST["tx_cnpj_cpf"])),   
        'nome'              => Limpa(trim($_POST["tx_nome"])) ,   
        'data'              => date('Y-m-d'), 
        'status'            => Limpa(trim($_POST["tx_status"]))
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
        'id_associado'      => Limpa(trim($_POST["tx_id_associado"])), 
        'cnpj_cpf'          => Limpa(trim($_POST["tx_cnpj_cpf"])),   
        'nome'              => Limpa(trim($_POST["tx_nome"])) ,    
        'status'            => Limpa(trim($_POST["tx_status"]))
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>


