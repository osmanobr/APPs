<?php include_once('conexao.php'); 
      include_once('Func.php');
(int)$q = 0;
        if((isset( ($_GET['q']))) && ($_GET['q']!=''))
           {(int)$q         = $_GET['q'];}
            $setor           = '';
    
        $sql = "SELECT * from tb_setor where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $setor           = $r['setor'];
                            
                      }
             }
?>  



<section class="content-header">
    <h1>
        Módulo:
        <small>Editar Setor</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Início</a></li>
        <li class="active">Editar Setor</li>
    </ol>
</section>
<section class="content">
      <form name="frm_empresa" id="frm_empresa" method="post">
         <div class="container">
            <div class="row">
               <div class="form-group">
                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 
               </div>
               <div class="form-group">
                  <div class="col-sm-12">     <label for="tx_setor">Setor:</label>     <input type="text" class="form-control" name="tx_setor" id="tx_setor" placeholder="Setor" value="<?php echo $setor;?>">    </div>
               </div>
               
               <div class="form-group">
                  <div class="col-sm-3">     <label for="Gravar"> </label>
                      <button type="button" onclick="gravar_setor()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>    </div>
               </div>
            </div>
         </div>
      </form>
                                                
</section>

<script>
 function gravar_setor(){
    $.post( "modulo/setor/gravar_setor.php", { 
        tx_id   :  $("#tx_id").val(),
        tx_setor :  $("#tx_setor").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/setor/listar_setor");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   