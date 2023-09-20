<?php 
include_once('conexao.php'); 
include_once('Func.php');
(int)$q = 0;
            $id_categoria	 = '';
            $titulo          = '';
            $texto           = '';
            $foto            = '';
            $data            = '';
            $previsao        = '';
            $criador         = '';
            $localizacao     = '';
            $views           = '';
            $ativo           = '';
            
    

        if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
           {(int)$q         = $_GET['q'];}

    
        $sql = "SELECT * FROM `tb_causa` where id = $q ;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id                     = $r['id'];
                            $id_categoria           = limpa($r['id_categoria']);
                            $titulo                   = limpa($r['titulo']);
                            $texto                     = limpa($r['texto']);
                            $foto                 = limpa($r['foto']);
                            $data                 = limpa($r['data']);
                            $previsao               = limpa($r['previsao']);
                            $criador                 = limpa($r['criador']);
                            $localizacao                  = limpa($r['localizacao']);
                            $views                   = limpa($r['views']);
                            $ativo                   = limpa($r['ativo']);
                                                        
                      }
             }
?>   



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR CAUSA</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active">
                        <a href="?modulo=modulo/causa/listar_causa">Causas</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/causa/editar_causa"><img src="dist/img/novo.png" width="60px"></a></li>    
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
      <form name="frm_causa" id="frm_causa" method="post">

                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 

<div class="row">
<div class="col-sm-1">
               <label for="tx_id_categoria">Categoria:</label>
               <input type="text" class="form-control" name="tx_id_categoria" id="tx_id_categoria" placeholder="Id Categoria" value="<?php echo $id_categoria;?>">
          </div>
 
                    <div class="col-sm-11">     <label for="tx_titulo">Titulo:</label>     <input type="text" class="form-control" name="tx_titulo" id="tx_titulo" placeholder="titulo" value="<?php echo $titulo;?>">    </div>

                    <div class="col-sm-12">     <label for="tx_texto">Texto:</label>     <input type="text" class="form-control" name="tx_texto" id="tx_texto" placeholder="texto" value="<?php echo $texto;?>">    </div>

                    <div class="col-sm-2">     <label for="tx_data">Data:</label>     <input type="date" class="form-control" name="tx_data" id="tx_data" placeholder="data" value="<?php echo date("d/m/y", strtotime($data));?>">    </div>

                    <div class="col-sm-2">     <label for="tx_previsao">Previsao:</label>     <input type="date" class="form-control" name="tx_previsao" id="tx_previsao" placeholder="previsao" value="<?php echo date("d/m/y", strtotime($previsao));?>">    </div>

                    <div class="col-sm-5">     <label for="tx_criador">Criador:</label>     <input type="text" class="form-control" name="tx_criador" id="tx_criador" placeholder="criador" value="<?php echo $criador;?>">    </div>

                    <div class="col-sm-10">     <label for="tx_localizacao">Localizacao:</label>     <input type="text" class="form-control" name="tx_localizacao" id="tx_localizacao" placeholder="localizacao" value="<?php echo $localizacao;?>">    </div>

                    <div class="col-sm-6">     <label for="tx_foto">Foto:</label>     <input type="text" class="form-control" name="tx_foto" id="tx_foto" placeholder="foto" value="<?php echo $foto;?>">    </div>
    
                    <div class="col-sm-1">     <label for="tx_views">Visualizações:</label>     <input type="text" class="form-control" name="tx_views" id="tx_views" placeholder="views" value="<?php echo $views;?>">    </div>
    
                    <div class="col-sm-1">     <label for="tx_ativo">Ativo:</label>     <input type="text" class="form-control" name="tx_ativo" id="tx_ativo" placeholder="ativo" value="<?php echo $ativo;?>">    </div>    
                    
                    <div class="col-sm-4">     <label for="Gravar"> </label>
                      <button type="button" onclick="gravar_blog()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>    </div>
          </div>

      </form>
                                        
<div class="row">

                  <div class="col-sm-3  border border-primary border-3 rounded">Foto
                      <form enctype="multipart/form-data" id="formfoto" method="post">
                        <img src="<?php echo '../'.$foto ; ?>" onclick="buscafoto()" style="cursor:pointer"  id="img_foto"  width="100%" alt="Foto" title="foto"style="box-shadow: 4px 4px 2px 2px black">
                        <input type="file" id="tx_upfoto" onchange="uploadfoto()"  name="tx_upfoto" style="display:none" />
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
    
function buscafoto() {
    $("#tx_upfoto").trigger('click');  
}; 

function uploadfoto(){
    var data = new FormData();
    data.append('tx_upfoto', $('#tx_upfoto')[0].files[0]);
    data.append('tx_id', $('#tx_id').val());

    $.ajax({
        url: 'modulo/causa/upload_foto.php',
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
    $.post( "modulo/causa/gravar_causa.php", { 
        tx_id: $("#tx_id").val(),
        tx_id_categoria: $("#tx_id_categoria").val(),
        tx_titulo: $("#tx_titulo").val(),
        tx_texto: $("#tx_texto").val(),
        tx_data: $("#tx_data").val(),
        tx_previsao: $("#tx_previsao").val(),
        tx_criador: $("#tx_criador").val(),
        tx_localizacao: $("#tx_localizacao").val(),
        tx_foto : $("#tx_foto").val(),
        tx_views : $("#tx_views").val(),
        tx_ativo : $("#tx_ativo").val(),
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/causa/listar_causa");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   