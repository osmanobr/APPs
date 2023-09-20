<?php 
(int)$q = 0;
if(isset($_GET['q']) && $_GET['q']!=''){(int)$q         = $_GET['q'];}
            $id_associado   = '';
            $cnpj_cpf       = '';
            $nome           = '';
            $data           = '';
            $status         = ''; 
    
        $sql = "SELECT * from tb_fornecedor where id = $q limit 1;";
        $result = $pdo->query( $sql );
        $rows = $result->fetchAll();
              if ($rows){   
                      foreach ($rows as $r){
                            $id             = $r['id'];
                            $id_associado   = $r['id_associado'];
                            $cnpj_cpf       = $r['cnpj_cpf'];
                            $nome           = $r['nome'];
                            $data           = $r['data'];
                            $status         = $r['status'];                          
                      }
             }
?>  

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDITAR FORNECEDOR</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/fornecedor/listar_fornecedor">Fornecedores</a></li>
                        <li class="breadcrumb-item active"><a href="?modulo=modulo/fornecedor/editar_fornecedor"><img src="dist/img/novo.png" width="60px"></a></li>    
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
                                                   <div class="mb-3">
                                                         <input type= "hidden" class="form-control" name="tx_id" id="tx_id" value="<?php if(!isset($id)){echo $q;} else {echo $id;}?>"> 
                                                   </div>
                                                     <div class="row">
                                                          <div class="col-sm-2">
                                                              <label for="tx_id_associado">Associado:</label>
                                                              <input type="text" class="form-control" name="tx_id_associado" id="tx_id_associado" placeholder="Associado" value="<?php echo $id_associado;?>" onkeyup="buscar()">
                                                          </div>

                                                          <div class="col-sm-2">
                                                              <label for="tx_cnpj_cpf">CNPJ/CPF:</label>
                                                              <input type="text" class="form-control" name="tx_cnpj_cpf" id="tx_cnpj_cpf" placeholder="cnpj/cpf" value="<?php echo $cnpj_cpf;?>">
                                                          </div>

                                                          <div class="col-sm-6">
                                                              <label for="tx_nome">Nome:</label>
                                                              <input type="text" class="form-control" name="tx_nome" id="tx_nome" placeholder="Nome" value="<?php echo $nome;?>">
                                                          </div>

                                                          <div class="col-sm-1">
                                                              <label for="tx_status">Ativo:</label>
                                                              <input type="text" class="form-control" name="tx_status" id="tx_status" placeholder="Ativo" value="<?php echo $status;?>">
                                                          </div>

                                                          <div class="col-sm-3">     <label for="Gravar"> </label>
                                                              <button type="button" onclick="gravar_fornecedor()"  class="btn btn-primary btn-block" id="Gravar" name="Gravar">Gravar</button>   
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
 function gravar_fornecedor(){ 
    $.post( "modulo/fornecedor/gravar_fornecedor.php", { 
        tx_id:  $("#tx_id").val(),
        tx_id_associado:  $("#tx_id_associado").val(),  
        tx_cnpj_cpf:  $("#tx_cnpj_cpf").val(),    
        tx_nome:  $("#tx_nome").val(),     
        tx_status:  $("#tx_status").val()
        })
          .done(function( data ) { alert(data);
            if (data = 1) {
            alert( 'Gravado com sucesso!' );
            window.location.replace("?modulo=modulo/fornecedor/listar_fornecedor");    
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
          //  window.location.replace("?modulo=modulo/fornecedor/listar_fornecedor"); 
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
   