<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_contato"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
        'nome' => $_POST["tx_nome"],
        'email' => $_POST["tx_email"],
        'celular' => $_POST["tx_celular"],
        'assunto' => $_POST["tx_assunto"],
        'texto' => $_POST["tx_texto"],
        'data_hora' => $_POST["tx_data_hora"],
        'ip' => $_POST["tx_ip"],
        'status' => $_POST["tx_status"],
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
        'nome' => $_POST["tx_nome"],
        'email' => $_POST["tx_email"],
        'celular' => $_POST["tx_celular"],
        'assunto' => $_POST["tx_assunto"],
        'texto' => $_POST["tx_texto"],
        'data_hora' => $_POST["tx_data_hora"],
        'ip' => $_POST["tx_ip"],
        'status' => $_POST["tx_status"],
        
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>