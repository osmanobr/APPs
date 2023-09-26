<?php
include_once('conexao.php');

//print_r($_POST);

//batida do ponto
if(isset($_POST['tximporta']))	{

include_once('conexao.php');
include_once('func.php');

$dia = date( 'd', strtotime( date('Y-m-d')));
$mes = date( 'm', strtotime( date('Y-m-d')));
$ano = date( 'Y', strtotime( date('Y-m-d')));

$minutos_atual = date('i');
$minutos_permitido = '20';

$marcacao = '*'; //presença mesmo que esteja fora do horario	
	
$data = $_POST['txdata']; //VALOR VINDO DO POST	
$dia = date( 'd', strtotime($data));
$mes = date( 'm', strtotime( $data));
$ano = date( 'Y', strtotime( $data));
$campo = 'd'.$dia;
$totalBatido = 0;
$importados = "<h3> $dia / $mes / $ano => Importações:</h3>";

$retorno_db = $_POST['tximporta']; //VALOR VINDO DO POST
$retorno_db = nl2br( $retorno_db, false );
$retorno_db = str_replace("<br>","\n",$retorno_db);
$retorno_db = str_replace("</br>","\n",$retorno_db);




//$retorno_db = $_GET['tximporta']; // Aqui coloca o valor do textarea que vai para o banco	
$codaluno_array = array_values(array_filter(explode(PHP_EOL,  $retorno_db)));	

  foreach($codaluno_array as $codaluno)
  {
	  if (trim($codaluno)!='')
	  {
		  $bate = $pdo->query("UPDATE tb_diario SET $campo = '$marcacao' where (cod_aluno = '".(int) trim($codaluno)."') and (ano = '$ano') and (mes = '$mes');");
		  	if($bate){$totalBatido = $totalBatido + 1;}

		  // $importados = $importados . '<p>'.$codaluno . " </p> ";
	  }
  }	
echo $totalBatido;
}
?>
