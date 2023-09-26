<?php 
print_r($_POST);
exit;
$json_str = $_POST;

$obj = json_decode($json_str);

//imprime o conteúdo do objeto
echo "nome: $obj->nome<br>";
echo "celular: $obj->celular<br>";
echo "email: $obj->email<br>"; 
?>