<?php //include_once('conexao.php'); 
      //include_once('Func.php');
    $razao      = '';
    $fantasia   = '';
    $cnpj       = '';
    $cgc        = '';
    $ie         = '';
    $im         = '';
    $logo       = '';
    $banner     = '';
    $banner2    = '';
    $banner3    = '';
    $lema       = '';
    $data_criacao = '';
    $pais       = '';
    $uf         = '';
    $municipio  = '';
    $bairro     = '';
    $endereco   = '';
    $cep        = '';
    $email      = '';
    $sobre      = '';
    $objetivo   = '';
    $como_funciona = '';
    $fundacao   = '';
    $associados = '';

(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
    
        $sql = "SELECT * from tb_empresa where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id         = limpa($r['id']);
                            $razao      = limpa($r['razao']);
                            $fantasia   = limpa($r['fantasia']);
                            $cnpj       = limpa($r['cnpj']);
                            $cgc        = limpa($r['cgc']);
                            $ie         = limpa($r['ie']);
                            $im         = limpa($r['im']);
                            $logo       = limpa($r['logo']);
                            $banner     = limpa($r['banner']);
                            $banner2    = limpa($r['banner2']);
                            $banner3    = limpa($r['banner3']);
                            $lema       = limpa($r['lema']);
                            $data_criacao = limpa($r['data_criacao']);
                            $pais       = limpa($r['pais']);
                            $uf         = limpa($r['uf']);
                            $municipio  = limpa($r['municipio']);
                            $bairro     = limpa($r['bairro']);
                            $endereco   = limpa($r['endereco']);
                            $cep        = limpa($r['cep']);
                            $email      = limpa($r['email']);
                            $sobre      = limpa($r['sobre']);
                            $objetivo   = limpa($r['objetivo']);
                            $como_funciona = limpa($r['como_funciona']);
                            $fundacao   = limpa($r['fundacao']);
                            $associados = limpa($r['associados']);                            
                      }
                }
?>  

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR EMPRESA</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/fornecedor/listar_fornecedor">Empresa</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/empresa/editar_empresa"><img src="dist/img/novo.png" width="60px"></a></li>    
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

                  <div class="col-sm-4">     <label for="tx_razao">Razão social:</label>     <input type="text" class="form-control" name="tx_razao" id="tx_razao" placeholder="razao" value="<?php echo $razao;?>">    </div>


                  <div class="col-sm-4">     <label for="tx_fantasia">Nome fantasia:</label>     <input type="text" class="form-control" name="tx_fantasia" id="tx_fantasia" placeholder="fantasia" value="<?php echo $fantasia;?>">    </div>


                  <div class="col-sm-2">     <label for="tx_cnpj">CNPJ:</label>     <input type="text" class="form-control" name="tx_cnpj" id="tx_cnpj" placeholder="cnpj" value="<?php echo $cnpj;?>">    </div>


                  <div class="col-sm-2">     <label for="tx_cgc">CGC:</label>     <input type="text" class="form-control" name="tx_cgc" id="tx_cgc" placeholder="cgc" value="<?php echo $cgc;?>">    </div>


                  <div class="col-sm-2">     <label for="tx_ie">Inscrição Estadual:</label>     <input type="text" class="form-control" name="tx_ie" id="tx_ie" placeholder="ie" value="<?php echo $ie;?>">    </div>


                  <div class="col-sm-2">     <label for="tx_im">Inscrição Municipal:</label>     <input type="text" class="form-control" name="tx_im" id="tx_im" placeholder="im" value="<?php echo $im;?>">    </div>

 
                  <div class="col-sm-4">     <label for="tx_logo">Logotipo:</label>     <input type="text" class="form-control" name="tx_logo" id="tx_logo" placeholder="logo" value="<?php echo $logo;?>">    </div>


                  <div class="col-sm-4">     <label for="tx_banner">Banner 01:</label>     <input type="text" class="form-control" name="tx_banner" id="tx_banner" placeholder="banner" value="<?php echo $banner;?>">    </div>


                  <div class="col-sm-4">     <label for="tx_banner2">Banner 02:</label>     <input type="text" class="form-control" name="tx_banner2" id="tx_banner2" placeholder="banner2" value="<?php echo $banner2;?>">    </div>


                  <div class="col-sm-4">     <label for="tx_banner3">Banner 03:</label>     <input type="text" class="form-control" name="tx_banner3" id="tx_banner3" placeholder="banner3" value="<?php echo $banner3;?>">    </div>

                  <div class="col-sm-4">     <label for="tx_lema">Lema:</label>     <input type="text" class="form-control" name="tx_lema" id="tx_lema" placeholder="lema" value="<?php echo $lema;?>">    </div>

                  <div class="col-sm-2">     <label for="tx_data_criacao">Data de Instituição:</label>     <input type="date" class="form-control" name="tx_data_criacao" id="tx_data_criacao" placeholder="data_criacao" value="<?php echo $data_criacao;?>">    </div>

                  <div class="col-sm-2">     <label for="tx_pais">País:</label>     <input type="text" class="form-control" name="tx_pais" id="tx_pais" placeholder="pais" value="<?php echo $pais;?>">    </div>

                  <div class="col-sm-1">     <label for="tx_uf">UF:</label>     <input type="text" class="form-control" name="tx_uf" id="tx_uf" placeholder="uf" value="<?php echo $uf;?>">    </div>

                  <div class="col-sm-3">     <label for="tx_municipio">Município:</label>     <input type="text" class="form-control" name="tx_municipio" id="tx_municipio" placeholder="municipio" value="<?php echo $municipio;?>">    </div>

                  <div class="col-sm-4">     <label for="tx_bairro">Bairro:</label>     <input type="text" class="form-control" name="tx_bairro" id="tx_bairro" placeholder="bairro" value="<?php echo $bairro;?>">    </div>

                  <div class="col-sm-4">     <label for="tx_endereco">Endereço:</label>     <input type="text" class="form-control" name="tx_endereco" id="tx_endereco" placeholder="endereco" value="<?php echo $endereco;?>">    </div>

                  <div class="col-sm-4">     <label for="tx_cep">CEP:</label>     <input type="text" class="form-control" name="tx_cep" id="tx_cep" placeholder="cep" value="<?php echo $cep;?>">    </div>

                  <div class="col-sm-4">     <label for="tx_email">e-mail:</label>     <input type="text" class="form-control" name="tx_email" id="tx_email" placeholder="email" value="<?php echo $email;?>">    </div>

                   <div class="col-sm-12">     <label for="tx_sobre">Sobre nós:</label>     <textarea  class="form-control" name="tx_sobre" id="tx_sobre" placeholder="sobre" ><?php echo $sobre;?></textarea>    </div>

                   <div class="col-sm-12">     <label for="tx_objetivo">Objetivo:</label>     <textarea  class="form-control" name="tx_objetivo" id="tx_objetivo" placeholder="objetivo" ><?php echo $objetivo;?></textarea>    </div>

                   <div class="col-sm-12">     <label for="tx_como_funciona">Como funciona:</label>     <textarea  class="form-control" name="tx_como_funciona" id="tx_como_funciona" placeholder="como_funciona"><?php echo $como_funciona;?></textarea>   </div>

                   <div class="col-sm-12">     <label for="tx_fundacao">Dados da fundação:</label>     <textarea class="form-control" name="tx_fundacao" id="tx_fundacao" placeholder="fundacao"><?php $fundacao;?></textarea>    </div>

                   <div class="col-sm-12">     <label for="tx_associados">Sobre o time de associados:</label>     <textarea class="form-control" name="tx_associados" id="tx_associados" placeholder="associados" ><?php echo $associados;?></textarea>    </div>

                  <div class="col-sm-4">     <label for="Gravar"> </label>
                      <button type="button" onclick="gravar_empresa()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>    </div>
            </div>
      </form>
                                        <div class="row"><h3>Imagens</h3></div>                                      
<?php if(isset($_GET['q']) && $_GET['q']!='0' )
{
?>
            <div class="row">

                  <div class="col-sm-3  border border-primary border-3 rounded">Logotipo
                      <form enctype="multipart/form-data" id="formlogo" method="post">
                        <img src="<?php echo '../'.$logo ; ?>" onclick="buscalogo()" style="cursor:pointer"  id="img_logo"  width="100%" alt="Logotipo" title="logotipo"style="box-shadow: 4px 4px 2px 2px black">
                        <input type="file" id="tx_uplogo" onchange="uploadlogo()"  name="tx_uplogo" style="display:none" />
                      </form>
                  </div>             

                  <div class="col-sm-3 border border-primary border-3 rounded">Banner 01
                      <form enctype="multipart/form-data" id="formbanner1" method="post">
                        <img src="<?php echo '../'.$banner ; ?>" onclick="buscabanner1()" style="cursor:pointer"  width="100%" alt="Banner 1" title="Banner 1" style="box-shadow: 4px 4px 2px 2px black">
                          <input type="file" id="tx_upbanner1" onchange="uploadbanner1()"   name="tx_upbanner1" style="display:none" />
                      </form>
                  </div>             
    
                  <div class="col-sm-3 border border-primary border-3 rounded">Banner 02
                      <form enctype="multipart/form-data" id="formbanner2" method="post">
                        <img src="<?php echo '../'.$banner2 ; ?>" onclick="buscabanner2()" style="cursor:pointer" width="100%"  alt="Banner 2" title="Banner 2" style="box-shadow: 4px 4px 2px 2px black">
                          <input type="file" id="tx_upbanner2" onchange="uploadbanner2()"   name="tx_upbanner2" style="display:none" />
                      </form>
                  </div> 
                
                  <div class="col-sm-3 border border-primary border-3 rounded">Banner 03
                      <form enctype="multipart/form-data" id="formbanner3" method="post">
                        <img src="<?php echo '../'.$banner3 ; ?>" onclick="buscabanner3()" style="cursor:pointer" width="100%" alt="Banner 3" title="Banner 3" style="box-shadow: 4px 4px 2px 2px black">
                          <input type="file" id="tx_upbanner3" onchange="uploadbanner3()"   name="tx_upbanner3" style="display:none" />
                      </form>
                  </div>                          
    </div>
<?php }?>
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
        url: 'modulo/empresa/upload_imagem.php',
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
        url: 'modulo/empresa/upload_imagem.php',
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
        url: 'modulo/empresa/upload_imagem.php',
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
        url: 'modulo/empresa/upload_imagem.php',
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
    

    
 function gravar_empresa(){
    $.post( "modulo/empresa/gravar_empresa.php", { 
        tx_id: $("#tx_id").val(),
        tx_razao: $("#tx_razao").val(),
        tx_fantasia: $("#tx_fantasia").val(),
        tx_cnpj: $("#tx_cnpj").val(),
        tx_cgc: $("#tx_cgc").val(),
        tx_ie: $("#tx_ie").val(),
        tx_im: $("#tx_im").val(),
        tx_logo: $("#tx_logo").val(),
        tx_banner: $("#tx_banner").val(),
        tx_banner2: $("#tx_banner2").val(),
        tx_banner3: $("#tx_banner3").val(),
        tx_lema: $("#tx_lema").val(),
        tx_data_criacao: $("#tx_data_criacao").val(),
        tx_pais: $("#tx_pais").val(),
        tx_uf: $("#tx_uf").val(),
        tx_municipio: $("#tx_municipio").val(),
        tx_bairro: $("#tx_bairro").val(),
        tx_endereco: $("#tx_endereco").val(),
        tx_cep: $("#tx_cep").val(),
        tx_email: $("#tx_email").val(),
        tx_sobre: $("#tx_sobre").val(),
        tx_objetivo: $("#tx_objetivo").val(),
        tx_como_funciona: $("#tx_como_funciona").val(),
        tx_fundacao: $("#tx_fundacao").val(),
        tx_associados: $("#tx_associados").val()
        })
          .done(function( data ) {
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/empresa/listar_empresa");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};

    </script>
   