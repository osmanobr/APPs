<?php 
include_once('conexao.php'); 
include_once('Func.php');
(int)$q = 0;
            $id_filiado     = '';
            $nome           = '';
            $email          = '';
            $celular        = '';
            $cpf            = '';
            $rg             = '';
            $data_nascimento    = '';
            $cidade_uf_origem   = '';
            $profissao      = '';
            $interesse      = '';
            $foto_perfil    = '';
            $sobre_mim      = '';
            $endereco       = '';
            $bairro         = '';
            $cidade         = '';
            $uf             = '';
            $data_filiacao  = '';
            $tipo_filiado   = '';
            $senha          = '';
            $exibir_time    = '';
            $status         = '';

        if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
           {(int)$q         = $_GET['q'];}

    
        $sql = "SELECT * FROM `tb_filiado` where id = $q ;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $nome           = limpa($r['nome']);
                            $email          = limpa($r['email']);
                            $celular        = limpa($r['celular']);
                            $cpf            = limpa($r['cpf']);
                            $rg             = limpa($r['rg']);
                            $data_nascimento    = limpa($r['data_nascimento']);
                            $cidade_uf_origem   = limpa($r['cidade_uf_origem']);
                            $profissao      = limpa($r['profissao']);
                            $interesse      = limpa($r['interesse']);
                            $foto_perfil    = limpa($r['foto_perfil']);
                            $sobre_mim      = limpa($r['sobre_mim']);
                            $endereco       = limpa($r['endereco']);
                            $bairro         = limpa($r['bairro']);
                            $cidade         = limpa($r['cidade']);
                            $uf             = limpa($r['uf']);
                            $data_filiacao  = limpa($r['data_filiacao']);
                            $tipo_filiado   = limpa($r['tipo_filiado']);
                            $senha          = limpa($r['senha']);
                            $exibir_time    = limpa($r['exibir_time']);
                            $status         = limpa($r['status']);
                      }
             }
?>  



<section class="content-header">
    <h1>
        Módulo:
        <small>Editar filiado</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Início</a></li>
        <li class="active">Editar Filiados</li>
    </ol>
</section>
<section class="content">
      <form name="frm_filiado" id="frm_filiado" method="post">
         <div class="container">
            <div class="row">
               <div class="form-group">
                     <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 
               </div>
            
               <div class="form-group">
                  <div class="col-sm-6">
                      <label for="tx_nome">Nome:</label>
                      <input type="text" class="form-control" name="tx_nome" id="tx_nome" placeholder="nome" value="<?php echo $nome;?>">
                  </div>
                 </div>
                
                 <div class="form-group">
                  <div class="col-sm-2">
                      <label for="tx_data_nascimento">Data de Nascimento:</label>
                      <input type="date" class="form-control" name="tx_data_nascimento" id="tx_data_nascimento" placeholder="Rg" value="<?php echo $data_nascimento;?>">
                  </div>
                </div>
                
                 <div class="form-group">
                  <div class="col-sm-4">
                      <label for="tx_cidade_uf_origem">Cidade de Origem:</label>
                      <input type="text" class="form-control" name="tx_cidade_uf_origem" id="tx_cidade_uf_origem" placeholder="Cidade_origem" value="<?php echo $cidade_uf_origem;?>">
                  </div>
               </div> 
               
                <div class="form-group">
                  <div class="col-sm-3">
                      <label for="tx_cpf">CPF:</label>
                      <input type="text" class="form-control" name="tx_cpf" id="tx_cpf" placeholder="Cpf" value="<?php echo $cpf;?>">
                  </div>
               </div>
                
                <div class="form-group">
                  <div class="col-sm-3">
                      <label for="tx_rg">Rg:</label>
                      <input type="text" class="form-control" name="tx_rg" id="tx_rg" placeholder="Rg" value="<?php echo $rg;?>">
                  </div>
               </div>
                
                <div class="form-group">
                  <div class="col-sm-3">
                      <label for="tx_celular">Celular:</label>
                      <input type="text" class="form-control" name="tx_celular" id="tx_celular" placeholder="Celular" value="<?php echo $celular;?>">
                  </div>
               </div>
                                
                <div class="form-group">
                  <div class="col-sm-6">
                      <label for="tx_endereco">Endereço:</label>
                      <input type="text" class="form-control" name="tx_endereco" id="tx_endereco" placeholder="Endereço" value="<?php echo $endereco;?>">
                  </div>
               </div>
                
                 <div class="form-group">
                  <div class="col-sm-6">
                      <label for="tx_bairro">Bairro:</label>
                      <input type="text" class="form-control" name="tx_bairro" id="tx_bairro" placeholder="Bairro" value="<?php echo $bairro;?>">
                  </div>
               </div> 
                
                 <div class="form-group">
                  <div class="col-sm-4">
                      <label for="tx_cidade">Cidade:</label>
                      <input type="text" class="form-control" name="tx_cidade" id="tx_cidade" placeholder="Cidade" value="<?php echo $cidade;?>">
                  </div>
               </div>               

                <div class="form-group">
                  <div class="col-sm-1">
                      <label for="tx_uf">Estado:</label>
                      <input type="text" class="form-control" name="tx_uf" id="tx_uf" placeholder="Uf" value="<?php echo $uf;?>">
                  </div>
               </div>                 
                
                <div class="form-group">
                  <div class="col-sm-4">
                      <label for="tx_profissao">Profissão:</label>
                      <input type="text" class="form-control" name="tx_profissao" id="tx_profissao" placeholder="Profissao" value="<?php echo $profissao;?>">
                  </div>
               </div> 
                
                <div class="form-group">
                  <div class="col-sm-6">
                      <label for="tx_interesse">Interesse:</label>
                      <input type="text" class="form-control" name="tx_interesse" id="tx_interesse" placeholder="Interesse" value="<?php echo $interesse;?>">
                  </div>
               </div> 
                
                <div class="form-group">
                  <div class="col-sm-4">
                      <label for="tx_foto_perfil">Foto de Perfil:</label>
                      <input type="text" class="form-control" name="tx_foto_perfil" id="tx_foto_perfil" placeholder="Foto_de_perfil" value="<?php echo $foto_perfil;?>">
                  </div>
               </div> 
                
                <div class="form-group">
                  <div class="col-sm-12">
                      <label for="tx_sobre_mim">Sobre Mim:</label>
                      <input type="text" class="form-control" name="tx_sobre_mim" id="tx_sobre_mim" placeholder="Sobre_mim" value="<?php echo $sobre_mim;?>">
                  </div>
               </div>          
            

                
                <div class="form-group">
                  <div class="col-sm-2">
                      <label for="tx_cpf">Tipo de Filiado:</label>
                      <input type="text" class="form-control" name="tx_tipo_filiado" id="tx_tipo_filiado" placeholder="tipo_filiado" value="<?php echo $tipo_filiado;?>">
                  </div>
               </div> 
                
                <div class="form-group">
                  <div class="col-sm-1">
                      <label for="tx_exibir_time">Time:</label>
                      <input type="text" class="form-control" name="tx_exibir_time" id="tx_exibir_time" placeholder="exibir_time" value="<?php echo $exibir_time;?>">
                  </div>
               </div> 
                

                <div class="form-group">
                  <div class="col-sm-4">
                      <label for="tx_email">email:</label>
                      <input type="text" class="form-control" name="tx_email" id="tx_email" placeholder="email" value="<?php echo $email;?>">
                  </div>
               </div>                 
                <div class="form-group">
                  <div class="col-sm-2">
                      <label for="tx_senha">Senha:</label>
                      <input type="text" class="form-control" name="tx_senha" id="tx_senha" placeholder="Senha" value="<?php echo $senha;?>">
                  </div>
               </div>    
                
               <div class="form-group">
                  <div class="col-sm-3">     <label for="Gravar"> </label>
                      <button type="button" onclick="gravar_filiado()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>    </div>
               </div>
            </div>
         </div>
      </form>
                                                
</section>

<script>
 function gravar_filiado(){ 
    $.post( "modulo/filiado/gravar_filiado.php", { 
        tx_id:  $("#tx_id").val(),
        tx_nome:  $("#tx_nome").val(),
        tx_email:  $("#tx_email").val(),
        tx_celular:  $("#tx_celular").val(),
        tx_cpf:  $("#tx_cpf").val(),
        tx_rg:  $("#tx_rg").val(),
        tx_data_nascimento:  $("#tx_data_nascimento").val(),
        tx_cidade_uf_origem:  $("#tx_cidade_uf_origem").val(),
        tx_profissao:  $("#tx_profissao").val(),
        tx_interesse:  $("#tx_interesse").val(),
        tx_foto_perfil:  $("#tx_foto_perfil").val(),
        tx_sobre_mim:  $("#tx_sobre_mim").val(),
        tx_endereco:  $("#tx_endereco").val(),
        tx_bairro:  $("#tx_bairro").val(),
        tx_cidade:  $("#tx_cidade").val(),
        tx_uf:  $("#tx_uf").val(),
        tx_tipo_filiado:  $("#tx_tipo_filiado").val(),
        tx_exibir_time:  $("#tx_exibir_time").val()
        })
          .done(function( data ) { 
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/filiado/listar_filiado");    
            }
        else {alert('Houve uma falha na gravação');}
              });
	};
    
  

function buscar(){
    var keycode = (event.keyCode ? event.keyCode : event.which);
        q = $("#tx_id_associado").val();

        if(q.length >= 1)
        {  
            if (keycode == 13){

                
    $.post( "modulo/associado/buscar_associado.php", {q})
          .done(function( data ) {

            if (data == $("#tx_id_associado").val()) {
          //  window.location.replace("?modulo=modulo/filiado/listar_filiado"); 
                $("#tx_cnpj_cpf").focus();
            } else {
                alert('O código do cliente não foi encontrado...');
                $("#tx_id_associado").focus();
            }

              });                
                
            }
        }else
        {
            alert('Impossível buscar...');
        }
}
    
    </script>
   