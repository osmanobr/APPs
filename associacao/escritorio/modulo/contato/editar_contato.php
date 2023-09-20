<?php 
include_once('conexao.php'); 
include_once('Func.php');
(int)$q = 0;
            $nome	          = '';
            $email            = '';
            $celular          = '';
            $assunto          = '';
            $texto            = '';
            $data_hora        = '';
            $ip               = '';
            $status           = '';

        if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
           {(int)$q         = $_GET['q'];}

    
        $sql = "SELECT * FROM `tb_contato` where id = $q ;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id                      = $r['id'];
                            $nome                    = limpa($r['nome']);
                            $email                   = limpa($r['email']);
                            $celular                 = limpa($r['celular']);
                            $assunto                 = limpa($r['assunto']);
                            $texto                   = limpa($r['texto']);
                            $data_hora               = limpa($r['data_hora']);
                            $ip                      = limpa($r['ip']);
                            $status                  = limpa($r['status']);

                                                        
                      }
             }
?>   



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR CONTATOS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active">
                        <a href="?modulo=modulo/contato/listar_contato">Contatos</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/contato/editar_contato"><img src="dist/img/novo.png" width="60px"></a></li>    
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
      <form name="frm_contato" id="frm_contato" method="post">

                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 

<div class="row">
                    <div class="col-sm-8">
                    <label for="tx_nome">Nome:</label>
                    <input type="text" class="form-control" name="tx_nome" id="tx_nome" placeholder="Nome" value="<?php echo $nome;?>">
                    </div>
 
                    <div class="col-sm-6">     <label for="tx_email">Email:</label>     <input type="text" class="form-control" name="tx_email" id="tx_email" placeholder="Email" value="<?php echo $email;?>">    </div>

                    <div class="col-sm-2">     <label for="tx_celular">Celular:</label>     <input type="text" class="form-control" name="tx_celular" id="tx_celular" placeholder="Celular" value="<?php echo $celular;?>">    </div>

                    <div class="col-sm-8">     <label for="tx_assunto">Assunto:</label>     <input type="text" class="form-control" name="tx_assunto" id="tx_assunto" placeholder="Assunto" value="<?php echo $assunto;?>">    </div>

                    <div class="col-sm-12">     <label for="tx_texto">Texto:</label>     <input type="text" class="form-control" name="tx_texto" id="tx_texto" placeholder="Texto" value="<?php echo $texto;?>">    </div>

                    <div class="col-sm-3">     <label for="tx_data_hora">Data e Hora:</label>     <input type="date" class="form-control" name="tx_data_hora" id="tx_data_hora" placeholder="Data e Hora" value="<?php echo date("d/m/y", strtotime($data_hora));?>">    </div>

                    <div class="col-sm-3">     <label for="tx_ip">IP:</label>     <input type="text" class="form-control" name="tx_ip" id="tx_ip" placeholder="IP" value="<?php echo $ip;?>">    </div>
    
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
    $.post( "modulo/contato/gravar_contato.php", { 
        tx_id: $("#tx_id").val(),
        tx_nome: $("#tx_nome").val(),
        tx_email: $("#tx_email").val(),
        tx_celular: $("#tx_celular").val(),
        tx_assunto: $("#tx_assunto").val(),
        tx_texto: $("#tx_texto").val(),
        tx_data_hora: $("#tx_data_hora").val(),
        tx_ip: $("#tx_ip").val(),
        tx_status : $("#tx_status").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/contato/listar_contato");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   