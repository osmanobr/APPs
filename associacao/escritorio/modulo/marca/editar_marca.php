<?php 

(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
            $marca           = '';
            $logo           = '';
            $status           = '';
    
        $sql = "SELECT * from tb_marca where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $marca           = $r['marca'];
                            $logo             = $r['logo'];
                            $status           = $r['status'];
                            
                      }
             }
?>  

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR MARCA</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/marca/listar_marca">Marcas</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/marca/editar_marca"><img src="dist/img/novo.png" width="60px"></a></li>    
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
                                        
      <form name="frm_marca" id="frm_marca" method="post">
         <div class="container">
            <div class="row">
               <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 
                    <div class="col-sm-12">
                        <label for="tx_marca">Marca:</label>
                        <input type="text" class="form-control" name="tx_marca" id="tx_marca" placeholder="marca" value="<?php echo $marca;?>">
                    </div>
                    <div class="col-sm-8">
                        <label for="tx_logo">Logo:</label>
                        <input type="text" class="form-control" name="tx_logo" id="tx_logo" placeholder="Logo" value="<?php echo $logo;?>">
                    </div>
                    <div class="col-sm-4">
                        <label for="tx_status">Status:</label>
                        <input type="text" class="form-control" name="tx_status" id="tx_status" placeholder="status" value="<?php echo $status;?>">
                    </div>
                <div class="col-sm-3">
                    <label for="Gravar"> </label>
                    <button type="button" onclick="gravar_marca()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button></div>
           
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
 function gravar_marca(){
    $.post( "modulo/marca/gravar_marca.php", { 
        tx_id   :  $("#tx_id").val(),
        tx_marca :  $("#tx_marca").val(),
        tx_logo :  $("#tx_logo").val(),
        tx_status:  $("#tx_status").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/marca/listar_marca");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   