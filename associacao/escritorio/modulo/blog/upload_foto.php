<?php
$rec = '';
if (isset($_FILES["tx_upfoto"])){
    $arquivo = $_FILES["tx_upfoto"];
    $rec = 'foto';
}

    if (!empty($arquivo["name"])) {
        
        $largura = 15000;
        $altura = 180000;
        $tamanho = 1000000;

            $diretorio_imagem = '../../../images/blog/';            
            if (!is_dir($diretorio_imagem)) {
                mkdir($diretorio_imagem, 0755, true);
            } 
        
        $erro = array();
        if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $arquivo["type"])){
            $erro[1] = "Isso não é uma imagem.";
            } 
        $dimensoes = getimagesize($arquivo["tmp_name"]);

        if(($dimensoes[0] > $largura)&&($largura < 10)) {
            $erro[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
        }
        if($dimensoes[1] > $altura) {
            $erro[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
        }

        if($arquivo["size"] > $tamanho) {
                $erro[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
        }

        if (count($erro) == 0) {
            // Pega extensão da imagem
            preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);
            $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
            $caminho_imagem = $diretorio_imagem.$nome_imagem;
            move_uploaded_file($arquivo["tmp_name"], $caminho_imagem);
        
            // atualiza imagem no banco
             require_once "../../Func.php";
             require_once "../../Conexao.class.php";  
             require_once "../../Crud.class.php"; 

             $pdo = Conexao::getInstance();  //instancio a classe 
             $crud = Crud::getInstance($pdo, "tb_blog"); //instancio a tabela


             (int)$id = Limpa(trim($_POST["tx_id"]));
            
             $diretorio_imagem_banco = explode("../../../",	$caminho_imagem);    
             $diretorio_imagem_banco = trim($diretorio_imagem_banco[1]);

             $retorno = 0; 
            if ($id > 0)
            {
            $arrayUser = array(
                    $rec => $diretorio_imagem_banco  
                 ); 
                //print_r($arrayUser);
             $arrayCond = array('id=' => $id);  
             $retorno   = $crud->update($arrayUser, $arrayCond); 
            }   
            echo $rec.' enviado com sucesso!'.$_POST['tx_id'];
        }
    
        // Se houver mensagens de erro, exibe-as
        if (count($erro) != 0) {
            foreach ($erro as $erro) {
                echo $erro;
            }
        }
    }

?>
