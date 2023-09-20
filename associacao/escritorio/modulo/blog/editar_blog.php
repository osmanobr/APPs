<?php 
(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
            $id_categoria   = '';
            $titulo         = '';
            $texto          = '';
            $foto           = '';
            $data           = '';
            $autor          = '';
            $view           = '';
            $ativo          = '';
    
        $sql = "SELECT * from tb_blog where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $id_categoria   = $r['id_categoria'];
                            $titulo         = $r['titulo'];
                            $texto          = $r['texto'];
                            $foto           = $r['foto'];
                            $data           = $r['data'];
                            $autor          = $r['autor'];
                            $view           = $r['view'];
                            $ativo          = $r['ativo'];                            
                      }
             }
?>  



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR BLOG</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/blog/listar_blog">Blogs</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/blog/editar_blog"><img src="dist/img/novo.png" width="60px"></a></li>    
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

                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 

<div class="row">
<div class="col-sm-2">
               <label for="tx_id_categoria">ID Categoria:</label>
               <input type="text" class="form-control" name="tx_id_categoria" id="tx_id_categoria" placeholder="Id Categoria" value="<?php echo $id_categoria;?>">
          </div>
 
                  <div class="col-sm-10">     <label for="tx_titulo">Titulo:</label>     <input type="text" class="form-control" name="tx_titulo" id="tx_titulo" placeholder="titulo" value="<?php echo $titulo;?>">    </div>

                  <div class="col-sm-12">     <label for="tx_texto">Texto:</label>
                      <textarea  class="form-control" name="tx_texto" id="tx_texto" placeholder="texto"><?php echo $texto;?></textarea>
                   </div>

                  <div class="col-sm-6">     <label for="tx_foto">Foto:</label>     <input type="text" class="form-control" name="tx_foto" id="tx_foto" placeholder="foto" value="<?php echo $foto;?>">    </div>

                  <div class="col-sm-3">     <label for="tx_data">Data:</label>     <input type="date" class="form-control" name="tx_data" id="tx_data" placeholder="data" value="<?php echo date("d/m/Y", strtotime($data));?>">    </div>
 
                  <div class="col-sm-3">     <label for="tx_autor">Autor:</label>     <input type="text" class="form-control" name="tx_autor" id="tx_autor" placeholder="autor" value="<?php echo $autor;?>">    </div>

                  <div class="col-sm-4">     <label for="tx_view">Visualizações:</label>     <input type="text" class="form-control" name="tx_view" id="tx_view" placeholder="view" value="<?php echo $view;?>">    </div>

                  <div class="col-sm-4">     <label for="tx_ativo">ativo:</label>     <input type="text" class="form-control" name="tx_ativo" id="tx_ativo" placeholder="ativo" value="<?php echo $ativo;?>">    </div>

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
    $.post( "modulo/blog/gravar_blog.php", { 
        tx_id: $("#tx_id").val(),
        tx_id_categoria: $("#tx_id_categoria").val(),
        tx_titulo: $("#tx_titulo").val(),
        tx_texto: $("#tx_texto").val(),
        tx_foto: $("#tx_foto").val(),
        tx_data: $("#tx_data").val(),
        tx_autor: $("#tx_autor").val(),
        tx_view: $("#tx_view").val(),
        tx_ativo : $("#tx_ativo").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/blog/listar_blog");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   