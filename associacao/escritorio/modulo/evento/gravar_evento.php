<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_evento"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
        'id_categoria'  => Limpa(trim($_POST["tx_id_categoria"])),
        'pais'        => Limpa(trim($_POST["tx_pais"])),
        'uf'         => Limpa(trim($_POST["tx_uf"])),
        'cidade'          => Limpa(trim($_POST["tx_cidade"])),
        'bairro'          => Limpa(trim($_POST["tx_bairro"])),
        'endereco'         => Limpa(trim($_POST["tx_endereco"])),
        'titulo'          => Limpa(trim($_POST["tx_titulo"])),
        'texto'         => Limpa(trim($_POST["tx_texto"])),
        'foto'         => Limpa(trim($_POST["tx_foto"])),
        'data'         => Limpa(trim($_POST["tx_data"])),
        'views'         => Limpa(trim($_POST["tx_views"]))        
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
        'id_categoria'  => Limpa(trim($_POST["tx_id_categoria"])),
        'pais'        => Limpa(trim($_POST["tx_pais"])),
        'uf'         => Limpa(trim($_POST["tx_uf"])),
        'cidade'          => Limpa(trim($_POST["tx_cidade"])),
        'bairro'          => Limpa(trim($_POST["tx_bairro"])),
        'endereco'         => Limpa(trim($_POST["tx_endereco"])),
        'titulo'          => Limpa(trim($_POST["tx_titulo"])),
        'texto'         => Limpa(trim($_POST["tx_texto"])),
        'foto'         => Limpa(trim($_POST["tx_foto"])),
        'data'         => Limpa(trim($_POST["tx_data"])),
        'views'         => Limpa(trim($_POST["tx_views"]))  
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>