<?php 
include_once('conexao.php'); 
include_once('Func.php');
(int)$q = 0;
            $titulo	 = '';
            $icone          = '';
            $valor           = '';
    
    

        if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
           {(int)$q         = $_GET['q'];}

    
        $sql = "SELECT * FROM `tb_contador` where id = $q ;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id                      = $r['id'];
                            $titulo            = limpa($r['titulo']);
                            $icone                  = limpa($r['icone']);
                            $valor                   = limpa($r['valor']);
                                                        
                      }
             }
?>   



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR CONTADORES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active">
                        <a href="?modulo=modulo/contador/listar_contador">Contadores</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/contador/editar_contador"><img src="dist/img/novo.png" width="60px"></a></li>    
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
      <form name="frm_contador" id="frm_contador" method="post">

                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 

<div class="row">
<div class="col-sm-9">
               <label for="tx_titulo">Titulo:</label>
               <input type="text" class="form-control" name="tx_titulo" id="tx_titulo" placeholder="Titulo" value="<?php echo $titulo;?>">
          </div>
 
                    <div class="col-sm-2">     <label for="tx_valor">Valor:</label>     <input type="text" class="form-control" name="tx_valor" id="tx_valor" placeholder="Valor" value="<?php echo $valor;?>">    </div>

                    <div class="col-sm-6">     <label for="tx_icone">Icone:</label>     <input type="text" class="form-control" name="tx_icone" id="tx_icone" placeholder="Icone" value="<?php echo $icone;?>">    </div>

                    <div class="col-sm-4">     <label for="Gravar"> </label>
                    <button type="button" onclick="gravar_blog()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>    </div>
          </div>

      </form>
                                        
<div class="row">

                  <div class="col-sm-3  border border-primary border-3 rounded">Icone
                      <form enctype="multipart/form-data" id="formicone" method="post">
                        <img src="<?php echo '../'.$icone ; ?>" onclick="buscaicone()" style="cursor:pointer"  id="img_icone"  width="100%" alt="Icone" title="icone"style="box-shadow: 4px 4px 2px 2px black">
                        <input type="file" id="tx_upicone" onchange="uploadicone()"  name="tx_upicone" style="display:none" />
                      </form>
                  </div> 
</div>

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
    
function buscaicone() {
    $("#tx_upicone").trigger('click');  
}; 

function uploadicone(){
    var data = new FormData();
    data.append('tx_upicone', $('#tx_upicone')[0].files[0]);
    data.append('tx_id', $('#tx_id').val());

    $.ajax({
        url: 'modulo/contador/upload_icone.php',
        data: data,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(data){
            alert(data);
            window.location.reload();
        }	
    });
}
    
 function gravar_blog(){
    $.post( "modulo/contador/gravar_contador.php", { 
        tx_id: $("#tx_id").val(),
        tx_titulo: $("#tx_titulo").val(),
        tx_icone: $("#tx_icone").val(),
        tx_valor: $("#tx_valor").val(),

        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/contador/listar_contador");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   