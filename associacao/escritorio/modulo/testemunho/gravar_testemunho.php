<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_testemunho"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
        'id_pessoa'  => Limpa(trim($_POST["tx_id_pessoa"])),
        'testemunho'        => Limpa(trim($_POST["tx_testemunho"])),
        'ativo'         => Limpa(trim($_POST["tx_ativo"])),
             
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
        'id_pessoa'  => Limpa(trim($_POST["tx_id_pessoa"])),
        'testemunho'        => Limpa(trim($_POST["tx_testemunho"])),
        'ativo'         => Limpa(trim($_POST["tx_ativo"])),  
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>