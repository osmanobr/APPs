<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_comentario"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
        'id_pessoa' => $_POST["tx_id_pessoa"],
        'data_hora' => $_POST["tx_data_hora"],
        'comentario' => $_POST["tx_comentario"],
        'local' => $_POST["tx_local"],
        'status' => $_POST["tx_status"],
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
        'id_pessoa' => $_POST["tx_id_pessoa"],
        'data_hora' => $_POST["tx_data_hora"],
        'comentario' => $_POST["tx_comentario"],
        'local' => $_POST["tx_local"],
        'status' => $_POST["tx_status"],
        
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>