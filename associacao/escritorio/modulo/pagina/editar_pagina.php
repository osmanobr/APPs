<?php 
(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
            $titulo         = '';
            $subtitulo      = '';
            $texto          = '';
            $foto           = '';
            $link           = '';
            $ativo          = '';
    
        $sql = "SELECT * from tb_pagina where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $titulo         = $r['titulo'];
                            $subtitulo      = $r['subtitulo'];
                            $texto          = $r['texto'];
                            $foto           = $r['foto'];
                            $link           = $r['link'];
                            $ativo          = $r['ativo'];                            
                      }
             }
?>  

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR PÁGINA</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/pagina/listar_pagina">Página</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/pagina/editar_pagina"><img src="dist/img/novo.png" width="60px"></a></li>    
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
                      <label for="tx_titulo">Titulo:</label>
                      <input type="text" class="form-control" name="tx_titulo" id="tx_titulo" placeholder="Titulo" value="<?php echo $titulo;?>">   </div>

                <div class="col-sm-12">
                    <label for="tx_subtitulo">Sub Titulo:</label>
                    <input type="text" class="form-control" name="tx_subtitulo" id="tx_subtitulo" placeholder="sub Titulo" value="<?php echo $subtitulo;?>">
                </div>
                  <div class="col-sm-12">
                      <label for="tx_texto">Texto:</label>
                      <textarea  class="form-control" name="tx_texto" id="tx_texto" placeholder="texto"><?php echo $texto;?></textarea>
                   </div>

                  <div class="col-sm-4">
                      <label for="tx_foto">Foto:</label>
                      <input type="text" class="form-control" name="tx_foto" id="tx_foto" placeholder="foto" value="<?php echo $foto;?>">
                </div>

                  <div class="col-sm-4">
                      <label for="tx_link">Link:</label>
                      <input type="text" class="form-control" name="tx_link" id="tx_link" placeholder="link" value="<?php echo $link;?>">
                </div>

                  <div class="col-sm-1">
                      <label for="tx_ativo">ativo:</label>
                      <input type="text" class="form-control" name="tx_ativo" id="tx_ativo" placeholder="ativo" value="<?php echo $ativo;?>">
                </div>
  
                  <div class="col-sm-3">
                      <label for="Gravar"> 
                      </label>
                      <button type="button" onclick="gravar_pagina()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>
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
 function gravar_pagina(){
    $.post( "modulo/pagina/gravar_pagina.php", { 
        tx_id:      $("#tx_id").val(),
        tx_titulo:  $("#tx_titulo").val(),
        tx_subtitulo:  $("#tx_subtitulo").val(),
        tx_texto:   $("#tx_texto").val(),
        tx_foto:    $("#tx_foto").val(),
        tx_link:    $("#tx_link").val(),
        tx_ativo :  $("#tx_ativo").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/pagina/listar_pagina");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   