<?php 
(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
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


<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR SETOR</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/setor/listar_setor">Setores</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/setor/editar_setor"><img src="dist/img/novo.png" width="60px"></a></li>    
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
                                                    <label for="tx_setor">Setor:</label>
                                                    <input type="text" class="form-control" name="tx_setor" id="tx_setor" placeholder="Setor" value="<?php echo $setor;?>">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="Gravar"> </label>
                                                    <button type="button" onclick="gravar_setor()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>   
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
   