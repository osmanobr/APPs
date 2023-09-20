<?php 
include_once('conexao.php'); 
include_once('Func.php');
(int)$q = 0;
            $data_hora	  = '';
            $log          = '';
            
        if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
           {(int)$q         = $_GET['q'];}

    
        $sql = "SELECT * FROM `tb_log` where id = $q ;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id                    = $r['id'];
                            $data_hora             = limpa($r['data_hora']);
                            $log                   = limpa($r['log']);

                                                        
                      }
             }
?>   



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR LOGS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active">
                        <a href="?modulo=modulo/log/listar_log">logs</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/log/editar_log"><img src="dist/img/novo.png" width="60px"></a></li>    
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
      <form name="frm_log" id="frm_log" method="post">

                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 

<div class="row">
                    <div class="col-sm-12">
                    <label for="tx_log">log:</label>
                    <input type="text" class="form-control" name="tx_log" id="tx_log" placeholder="log" value="<?php echo $log;?>">
                    </div>
    
                    <div class="col-sm-12">     <label for="tx_data_hora">Data e Hora:</label>     <input type="date" class="form-control" name="tx_data_hora" id="tx_data_hora" placeholder="Texto" value="<?php echo date("d/m/y", strtotime($data_hora));?>"> </div>
    
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
    $.post( "modulo/log/gravar_log.php", { 
        tx_id: $("#tx_id").val(),
        tx_log: $("#tx_log").val(),
        tx_data_hora: $("#tx_data_hora").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/log/listar_log");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   