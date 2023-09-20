<?php 
include_once('conexao.php'); 
include_once('Func.php');
(int)$q = 0;
            $id_pessoa	     = '';
            $testemunho      = '';
            $ativo           = '';
    
    

        if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
           {(int)$q         = $_GET['q'];}

    
        $sql = "SELECT * FROM `tb_testemunho` where id = $q ;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id                   = $r['id'];
                            $id_pessoa            = limpa($r['id_pessoa']);
                            $testemunho           = limpa($r['testemunho']);
                            $ativo                = limpa($r['ativo']);
                                                        
                      }
             }
?>   



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR TESTEMUNHOS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active">
                        <a href="?modulo=modulo/testemunho/listar_testemunho">Testemunhos</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/testemunho/editar_testemunho"><img src="dist/img/novo.png" width="60px"></a></li>    
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
      <form name="frm_testemunho" id="frm_testemunho" method="post">

                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 

<div class="row">
<div class="col-sm-3">
               <label for="tx_id_pessoa">ID Pessoa:</label>
               <input type="text" class="form-control" name="tx_id_pessoa" id="tx_id_pessoa" placeholder="Pessoa" value="<?php echo $id_pessoa;?>">
          </div>
 
                    <div class="col-sm-12">     <label for="tx_testemunho">Testemunho:</label>     <input type="text" class="form-control" name="tx_testemunho" id="tx_testemunho" placeholder="Testemunho" value="<?php echo $testemunho;?>">    </div>

                    <div class="col-sm-1">     <label for="tx_ativo">Ativo:</label>     <input type="text" class="form-control" name="tx_ativo" id="tx_ativo" placeholder="Ativo" value="<?php echo $ativo;?>">    </div>

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
 function gravar_testemunho(){
    $.post( "modulo/testemunho/gravar_testemunho.php", { 
        tx_id: $("#tx_id").val(),
        tx_id_pessoa: $("#tx_id_pessoa").val(),
        tx_testemunho: $("#tx_testemunho").val(),
        tx_ativo: $("#tx_ativo").val(),

        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/testemunho/listar_testemunho");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   