<?php 
include_once('conexao.php'); 
include_once('Func.php');
(int)$q = 0;
            $id_pessoa	          = '';
            $data_hora            = '';
            $comentario          = '';
            $local          = '';
            $status            = '';
            
        if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
           {(int)$q         = $_GET['q'];}

    
        $sql = "SELECT * FROM `tb_comentario` where id = $q ;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id                      = $r['id'];
                            $id_pessoa               = limpa($r['id_pessoa']);
                            $data_hora               = limpa($r['data_hora']);
                            $comentario              = limpa($r['comentario']);
                            $local                   = limpa($r['local']);
                            $status                  = limpa($r['status']);

                                                        
                      }
             }
?>   



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR COMENTARIOS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active">
                        <a href="?modulo=modulo/comentario/listar_comentario">Comentarios</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/comentario/editar_comentario"><img src="dist/img/novo.png" width="60px"></a></li>    
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
      <form name="frm_comentario" id="frm_comentario" method="post">

                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 

<div class="row">
                    <div class="col-sm-3">
                    <label for="tx_id_pessoa">ID Pessoa:</label>
                    <input type="text" class="form-control" name="tx_id_pessoa" id="tx_id_pessoa" placeholder="ID Pessoa" value="<?php echo $id_pessoa;?>">
                    </div>
    
                    <div class="col-sm-3">     <label for="tx_data_hora">Data e Hora:</label>     <input type="date" class="form-control" name="tx_data_hora" id="tx_data_hora" placeholder="Data e Hora" value="<?php echo date("d/m/y", strtotime($data_hora));?>"> </div>

                    <div class="col-sm-12">     <label for="tx_comentario">Comentario:</label>     <input type="text" class="form-control" name="tx_comentario" id="tx_comentario" placeholder="Comentario" value="<?php echo $comentario;?>">    </div>
 
                    <div class="col-sm-6">     <label for="tx_local">Local:</label>     <input type="text" class="form-control" name="tx_local" id="tx_local" placeholder="Local" value="<?php echo $local;?>">    </div>
    
                    <div class="col-sm-1">     <label for="tx_status">Status:</label>     <input type="text" class="form-control" name="tx_status" id="tx_status" placeholder="Status" value="<?php echo $status;?>">    </div>

                    <div class="col-sm-4">     <label for="Gravar"> </label>
                      <button type="button" onclick="gravar_blog()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>    </div>
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

    
 function gravar_blog(){
    $.post( "modulo/comentario/gravar_comentario.php", { 
        tx_id: $("#tx_id").val(),
        tx_id_pessoa: $("#tx_id_pessoa").val(),
        tx_data_hora: $("#tx_data_hora").val(),
        tx_comentario: $("#tx_comentario").val(),
        tx_local: $("#tx_local").val(),
        tx_status : $("#tx_status").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/comentario/listar_comentario");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   