<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_blog"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
'id_categoria'  => Limpa(trim($_POST["tx_id_categoria"])),
'titulo'        => Limpa(trim($_POST["tx_titulo"])),
'texto'         => Limpa(trim($_POST["tx_texto"])),
'foto'          => Limpa(trim($_POST["tx_foto"])),
'data'          => Limpa(trim($_POST["tx_data"])),
'autor'         => Limpa(trim($_POST["tx_autor"])),
'view'          => Limpa(trim($_POST["tx_view"])),
'ativo'         => Limpa(trim($_POST["tx_ativo"]))
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
    'id_categoria'  => Limpa(trim($_POST["tx_id_categoria"])),
    'titulo'        => Limpa(trim($_POST["tx_titulo"])),
    'texto'         => Limpa(trim($_POST["tx_texto"])),
    'foto'          => Limpa(trim($_POST["tx_foto"])),
    'data'          => Limpa(trim($_POST["tx_data"])),
    'autor'         => Limpa(trim($_POST["tx_autor"])),
    'view'          => Limpa(trim($_POST["tx_view"])),
    'ativo'         => Limpa(trim($_POST["tx_ativo"]))  
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>