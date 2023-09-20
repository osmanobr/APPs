<html>
    	<body>
	<form method='post' enctype='multipart/form-data'><br>
		<input type='file' name='foto' value='Cadastrar foto'>
		<input type='submit' name='submit'>
	</form>
	<?php
		include "upload_imagem.class.php";

$diretorio_imagem = '../../images/clientes/';            
if (!is_dir($diretorio_imagem)) {
    //Criamos um diretório
    mkdir($diretorio_imagem, 0755, true);
}            
if ((isset($_POST["submit"])) && (! empty($_FILES['foto']))){
    $upload = new Upload($_FILES['foto'], 75, 100, $diretorio_imagem);
    $diretorio_imagem_banco = $upload->salvar();
    $diretorio_imagem_banco = explode("../../",	$diretorio_imagem_banco);    
    echo $diretorio_imagem_banco = trim($diretorio_imagem_banco[1]);  
}


		?>
	</body>
</html>