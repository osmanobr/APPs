<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_contador"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
        'titulo'  => Limpa(trim($_POST["tx_titulo"])),
        'icone'        => Limpa(trim($_POST["tx_icone"])),
        'valor'         => Limpa(trim($_POST["tx_valor"])),
             
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
        'titulo'  => Limpa(trim($_POST["tx_titulo"])),
        'icone'        => Limpa(trim($_POST["tx_icone"])),
        'valor'         => Limpa(trim($_POST["tx_valor"])),  
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>