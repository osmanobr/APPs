<?php
 require_once "../../Func.php";
 require_once "../../Conexao.class.php";  
 require_once "../../Crud.class.php"; 
 
 $pdo = Conexao::getInstance();  //instancio a classe 
 $crud = Crud::getInstance($pdo, "tb_empresa"); //instancio a tabela


 (int)$id = Limpa(trim($_POST["tx_id"]));
     
 $retorno = 0; 
if ($id < 1)
{
    $arrayInsere = array(
        'razao' => $_POST["tx_razao"],
        'fantasia' => $_POST["tx_fantasia"],
        'cnpj' => $_POST["tx_cnpj"],
        'cgc' => $_POST["tx_cgc"],
        'ie' => $_POST["tx_ie"],
        'im' => $_POST["tx_im"],
        'logo' => $_POST["tx_logo"],
        'banner' => $_POST["tx_banner"],
        'banner2' => $_POST["tx_banner2"],
        'banner3' => $_POST["tx_banner3"],
        'lema' => $_POST["tx_lema"],
        'data_criacao' => $_POST["tx_data_criacao"],
        'pais' => $_POST["tx_pais"],
        'uf' => $_POST["tx_uf"],
        'municipio' => $_POST["tx_municipio"],
        'bairro' => $_POST["tx_bairro"],
        'endereco' => $_POST["tx_endereco"],
        'cep' => $_POST["tx_cep"],
        'email' => $_POST["tx_email"],
        'sobre' => $_POST["tx_sobre"],
        'objetivo' => $_POST["tx_objetivo"],
        'como_funciona' => $_POST["tx_como_funciona"],
        'fundacao' => $_POST["tx_fundacao"],
        'associados' => Limpa($_POST["tx_associados"])
     );
    //print_r($arrayInsere);
	$retorno = $crud->insert($arrayInsere); 
}
else
{
    $arrayUser = array(
        'razao' => $_POST["tx_razao"],
        'fantasia' => $_POST["tx_fantasia"],
        'cnpj' => $_POST["tx_cnpj"],
        'cgc' => $_POST["tx_cgc"],
        'ie' => $_POST["tx_ie"],
        'im' => $_POST["tx_im"],
        'logo' => $_POST["tx_logo"],
        'banner' => $_POST["tx_banner"],
        'banner2' => $_POST["tx_banner2"],
        'banner3' => $_POST["tx_banner3"],
        'lema' => $_POST["tx_lema"],
        'data_criacao' => $_POST["tx_data_criacao"],
        'pais' => $_POST["tx_pais"],
        'uf' => $_POST["tx_uf"],
        'municipio' => $_POST["tx_municipio"],
        'bairro' => $_POST["tx_bairro"],
        'endereco' => $_POST["tx_endereco"],
        'cep' => $_POST["tx_cep"],
        'email' => $_POST["tx_email"],
        'sobre' => $_POST["tx_sobre"],
        'objetivo' => $_POST["tx_objetivo"],
        'como_funciona' => $_POST["tx_como_funciona"],
        'fundacao' => $_POST["tx_fundacao"],
        'associados' => Limpa($_POST["tx_associados"])  
     ); 
    //print_r($arrayUser);
 $arrayCond = array('id=' => $id);  
 $retorno   = $crud->update($arrayUser, $arrayCond);     

}
echo $retorno;
	?>