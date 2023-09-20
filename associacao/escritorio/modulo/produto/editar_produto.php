<?php //include_once('conexao.php'); 
      //include_once('Func.php');
    $nome      = '';
    $descricao   = '';
    $imagem       = '';
  
(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
    
        $sql = "SELECT * from tb_produto where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id         = limpa($r['id']);
                            $nome      = limpa($r['nome']);
                            $descricao   = limpa($r['descricao']);
                            $imagem       = limpa($r['imagem']);
                                                   
                      }
                }
?>  

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR PRODUTO</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/produto/listar_produto">Produto</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/produto/editar_produto"><img src="dist/img/novo.png" width="60px"></a></li>    
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
                                        
      <form name="frm_produto" id="frm_produto" method="post">
            <div class="row">
                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 

                  <div class="col-sm-4">     <label for="tx_nome">Nome:</label>     <input type="text" class="form-control" name="tx_nome" id="tx_nome" placeholder="nome" value="<?php echo $nome;?>">    </div>


                  <div class="col-sm-4">     <label for="tx_descricao">Descrição:</label>     <input type="text" class="form-control" name="tx_descricao" id="tx_descricao" placeholder="descricao" value="<?php echo $descricao;?>">    </div>


                  <div class="col-sm-2">     <label for="tx_imagem">Imagem:</label>     <input type="text" class="form-control" name="tx_imagem" id="tx_imagem" placeholder="imagem" value="<?php echo $imagem;?>">    </div>


                    <div class="col-sm-4">     <label for="Gravar"> </label>
                      <button type="button" onclick="gravar_produto()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>    </div>
            </div>
      </form>
                                        <div class="row"><h3>Imagens</h3></div>                                      

            <div class="row">

                  <div class="col-sm-3  border border-primary border-3 rounded">Imagem
                      <form enctype="multipart/form-data" id="formlogo" method="post">
                        <img src="<?php echo '../'.$imagem ; ?>" onclick="buscalogo()" style="cursor:pointer"  id="img_logo"  width="100%" alt="Imagem" title="logotipo"style="box-shadow: 4px 4px 2px 2px black">
                        <input type="file" id="tx_uplogo" onchange="uploadlogo()"  name="tx_uplogo" style="display:none" />
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
function buscalogo() {
    $("#tx_uplogo").trigger('click');  
};     
function uploadlogo(){
    var data = new FormData();
    data.append('tx_uplogo', $('#tx_uplogo')[0].files[0]);
    data.append('tx_id', $('#tx_id').val());

    $.ajax({
        url: 'modulo/produto/upload_imagem.php',
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
    

function buscabanner1() {
    $("#tx_upbanner1").trigger('click');  
};     
function uploadbanner1(){
    var data = new FormData();
    data.append('tx_upbanner1', $('#tx_upbanner1')[0].files[0]);
    data.append('tx_id', $('#tx_id').val());
    $.ajax({
        url: 'modulo/produto/upload_imagem.php',
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
    
function buscabanner2() {
    $("#tx_upbanner2").trigger('click');  
};     
function uploadbanner2(){
    var data = new FormData();
    data.append('tx_upbanner2', $('#tx_upbanner2')[0].files[0]);
    data.append('tx_id', $('#tx_id').val());
    $.ajax({
        url: 'modulo/produto/upload_imagem.php',
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

function buscabanner3() {
    $("#tx_upbanner3").trigger('click');  
};     
function uploadbanner3(){
    var data = new FormData();
    data.append('tx_upbanner3', $('#tx_upbanner3')[0].files[0]);
    data.append('tx_id', $('#tx_id').val());
    $.ajax({
        url: 'modulo/produto/upload_imagem.php',
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
    

    
 function gravar_produto(){
    $.post( "modulo/produto/gravar_produto.php", { 
        tx_id: $("#tx_id").val(),
        tx_nome: $("#tx_nome").val(),
        tx_descricao: $("#tx_descricao").val(),
        tx_imagem: $("#tx_imagem").val(),
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/produto/listar_produto");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   