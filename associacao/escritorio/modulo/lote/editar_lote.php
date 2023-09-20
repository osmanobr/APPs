<?php 
(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
            $lote           = '';
    
        $sql = "SELECT * from tb_lote where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $lote           = $r['lote'];
                            
                      }
             }
?>  


<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR LOTE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/lote/listar_Lote">Lote</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/lote/editar_lote"><img src="dist/img/novo.png" width="60px"></a></li>    
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body p-0">
      <form name="frm_empresa" id="frm_empresa" method="post">

            <div class="row">
               <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 
                <div class="col-sm-12">
                      <label for="tx_lote">Lote:</label>
                      <input type="text" class="form-control" name="tx_lote" id="tx_lote" placeholder="Lote" value="<?php echo $lote;?>">
                </div>
             
                  <div class="col-sm-3">
                      <label for="Gravar"> </label>
                      <button type="button" onclick="gravar_lote()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>
                </div>
               
            </div>

      </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
                                        

<script>
 function gravar_lote(){
    $.post( "modulo/lote/gravar_lote.php", { 
        tx_id   :  $("#tx_id").val(),
        tx_lote :  $("#tx_lote").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/lote/listar_lote");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   