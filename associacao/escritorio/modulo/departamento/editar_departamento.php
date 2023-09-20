<?php 
(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
            $departamento           = '';
    
        $sql = "SELECT * from tb_departamento where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $departamento           = $r['departamento'];
                            
                      }
             }
?>  



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR DEPARTAMENTO</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/departamento/listar_departamento">Departamentos</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/departamento/editar_departamento"><img src="dist/img/novo.png" width="60px"></a></li>    
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
      <form name="frm_empresa" id="frm_empresa" method="post" >
                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 
                  <div class="col-sm-12">
                      <label for="tx_departamento">Departamento:</label>
                      <input type="text" class="form-control" name="tx_departamento" id="tx_departamento" placeholder="departamento" value="<?php echo $departamento;?>">
          </div>
                   <div class="col-sm-3">
                       <label for="Gravar">
                       </label>
                      <button type="button" onclick="gravar_departamento()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>
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
 function gravar_departamento(){
    $.post( "modulo/departamento/gravar_departamento.php", { 
        tx_id   :  $("#tx_id").val(),
        tx_departamento :  $("#tx_departamento").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/departamento/listar_departamento");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   