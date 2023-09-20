<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_causa"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
        'id_categoria'  => Limpa(trim($_POST["tx_id_categoria"])),
        'titulo'        => Limpa(trim($_POST["tx_titulo"])),
        'texto'         => Limpa(trim($_POST["tx_texto"])),
        'data'          => Limpa(trim($_POST["tx_data"])),
        'previsao'          => Limpa(trim($_POST["tx_previsao"])),
        'criador'         => Limpa(trim($_POST["tx_criador"])),
        'localizacao'          => Limpa(trim($_POST["tx_localizacao"])),
        'foto'         => Limpa(trim($_POST["tx_foto"])),
        'views'         => Limpa(trim($_POST["tx_views"])),
        'ativo'         => Limpa(trim($_POST["tx_ativo"])),
        'views'         => Limpa(trim($_POST["tx_views"]))        
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
        'data'          => Limpa(trim($_POST["tx_data"])),
        'previsao'          => Limpa(trim($_POST["tx_previsao"])),
        'criador'         => Limpa(trim($_POST["tx_criador"])),
        'localizacao'          => Limpa(trim($_POST["tx_localizacao"])),
        'foto'         => Limpa(trim($_POST["tx_foto"])),
        'views'         => Limpa(trim($_POST["tx_views"])),
        'ativo'         => Limpa(trim($_POST["tx_ativo"])),
        'views'         => Limpa(trim($_POST["tx_views"]))   
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>