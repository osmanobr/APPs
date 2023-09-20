<?php 

(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
            $secao           = '';
    
        $sql = "SELECT * from tb_secao where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $secao           = $r['secao'];
                            
                      }
             }
?>  

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR SEÇÃO</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/secao/listar_secao">Seções</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/secao/editar_secao"><img src="dist/img/novo.png" width="60px"></a></li>    
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
         <div class="container">
            <div class="row">
               <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 
                    <div class="col-sm-12">
                        <label for="tx_secao">Selçao:</label>
                        <input type="text" class="form-control" name="tx_secao" id="tx_secao" placeholder="secao" value="<?php echo $secao;?>">
                    </div>
                <div class="col-sm-3">
                    <label for="Gravar"> </label>
                    <button type="button" onclick="gravar_secao()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button></div>
           
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
 function gravar_secao(){
    $.post( "modulo/secao/gravar_secao.php", { 
        tx_id   :  $("#tx_id").val(),
        tx_secao :  $("#tx_secao").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/secao/listar_secao");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   