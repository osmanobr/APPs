<?php
@session_start();
$pagina = 'importar_ponto';
$nivel_requerido = '4';
$_SESSION['nivel'] = 1;
if(!isset($_SESSION['nivel']) or ($_SESSION['nivel']>$nivel_requerido))
{echo "<script>alert('Sem permissão para esse módulo')</script>"; 
echo'<meta http-equiv="refresh" content=0;url="index.php?pag=login">';
exit;}

//if(!isset($index)){echo'<meta http-equiv="refresh" content=0;url="index.php?pag='.$pagina.'">';exit;}

	?>

<div class="input-group">
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Importar Ponto</h1>
        </div>
	</div>
</div>	

<div class="row">
	<div class="col-lg-12">
		<div class="row">    
			<p><b>Os pontos batidos neste módulo entram como '*' (presença) independente de atraso ou dia da batida.</b></p>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<form  id="form" name="form" method="post">
	<div class="row">
		<div class="col-lg-6">
			<div class="row">    
				  <label>Data para importação </label><br>
				  <input type="date" name="txdata" id="txdata" class="form-control" autofocus required autocomplete="off" value="<?php echo date('Y-m-d');?>" ><br>
				  <h4>RESULTADOS DA IMPORTAÇÃO</h4>
					<div class="col-lg-12" id="txpontosimportados">Nenhum ponto importado ainda</div>
           
			</div>
		</div>

		<div class="col-lg-6">	
				  <label>IDs a serem importados </label><br>
				  <textarea cols="20" rows="15" wrap="hard"  name="tximporta" id="tximporta" class="form-control"  required autocomplete="off"></textarea><br>
				  <input type="submit" name="btnimportar" id="btnimportar"  value="Importar" class="form-control btn btn-primary" ><br>
		</div>
	</div>
	<input type="hidden" name="pag" value="importar_ponto">
</form>

    <script>
    	/*
    function remove_linebreaks( str ) {
        var retorno = str.replace( /[^0-9]+/gm, "" );
		let aux = '';  	
		let x = 0;
		for (var i = 0; i < retorno.length; i++) {
       		 if(x==6){
   				  x=0;
           	   aux+="|";
             }
             aux +=(retorno.charAt(i));
             x++;
		}
		return aux;
 }
      
    function removeNewLines() {
        var sample_str =
        document.getElementById('tximporta').value;          
        console.time();
        document.getElementById('tximporta').value = remove_linebreaks( sample_str);
        console.timeEnd();
        
    }

   function Enviar(){

   	document.getElementById('form').submit();
   }
*/
jQuery(document).ready(function(){
jQuery("#form").submit(function(){
var dados = jQuery( this ).serialize();
jQuery.ajax({
type: "POST",
url: "api.importar.ponto.php",
data: dados,
success: function( data )
	{ 
		if(data > 0){alert("Importado "+data+" registros com sucesso!");}
		$("#txpontosimportados").text("Importado "+data+" registros com sucesso!");
	}
});
	return false;
});
});
</script>