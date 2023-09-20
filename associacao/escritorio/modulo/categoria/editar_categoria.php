<?php 
(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
            $categoria           = '';
    
        $sql = "SELECT * from tb_categoria where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $categoria           = $r['categoria'];
                            
                      }
             }
?>  



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR CATEGORIA</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/categoria/listar_categoria">Categorias</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/categoria/editar_categoria"><img src="dist/img/novo.png" width="60px"></a></li>    
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
               <div class="form-group">
                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 
               </div>
               <div class="form-group">
                  <div class="col-sm-12">     <label for="tx_categoria">categoria:</label>     <input type="text" class="form-control" name="tx_categoria" id="tx_categoria" placeholder="categoria" value="<?php echo $categoria;?>">    </div>
               </div>
               
               <div class="form-group">
                  <div class="col-sm-3">     <label for="Gravar"> </label>
                      <button type="button" onclick="gravar_categoria()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>    </div>
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
 function gravar_categoria(){
    $.post( "modulo/categoria/gravar_categoria.php", { 
        tx_id   :  $("#tx_id").val(),
        tx_categoria :  $("#tx_categoria").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/categoria/listar_categoria");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   