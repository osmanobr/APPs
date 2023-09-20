 <?php
//limita texto em palavras
                function limitarTexto($texto, $limite){
                    $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . ' [...]';
                    return $texto;
                }
	function Limpa($texto)
	{
	$texto = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|s\\\\_-=.,}~^)/i", '', $texto);
	$texto = strip_tags($texto);
	$texto = addslashes($texto);//Adiciona barras invertidas a uma string
	return $texto;
	}

?>