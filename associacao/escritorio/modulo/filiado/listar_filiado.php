<!-- titulo da pagina -->
<section class="content-header">
    <h1>
        Módulo:
        <small>Listando Filiado</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Início</a></li>
        <li class="active">Listando filiado</li>
        <li class="active"><a href="?modulo=modulo/filiado/editar_filiado&q=0"><img src="img/novo.png" width="60px"></a></li>
    </ol>
</section>
<section class="content">
 

<div class="container">

   <table class="table">
      <thead>
         <tr>
            <th scope="col">ID</th>
            <th scope="col">Nome</th>
            <th scope="col">CPF</th>
            <th scope="col">RG</th>
            <th scope="col">Endereço</th>
            <th scope="col">Bairro</th>
            <th scope="col">Cidade</th>
            <th scope="col">UF</th>
            <th scope="col">Ação</th>
      </thead>
      <tbody>
          <?php include_once('../conexao.php'); 

                $sql = "SELECT * from tb_filiado order by id asc limit 100;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                      if ($rows){   
                              foreach ($rows as $r){
         ?>  
                                     <tr>
                                        <td><?php echo $r['id'];?></td>
                                        <td><?php echo $r['nome'];?></td>
                                         <td><?php echo $r['cpf'];?></td>
                                         <td><?php echo $r['rg'];?></td>
                                         <td><?php echo $r['endereco'];?></td>
                                         <td><?php echo $r['bairro'];?></td>
                                         <td><?php echo $r['cidade'];?></td>
                                         <td><?php echo $r['uf'];?></td>
                                        <td><a href="?modulo=modulo/filiado/editar_filiado&q=<?php echo $r['id'];?>">Editar</a></td>
                                     </tr>
        <?php
                               }
                        }
          else
          { ?>
             <tr>
                <td colspan="4">Nenhum registro encontrado deseja <a href="?modulo=modulo/filiado/editar_filiado&q=0">criar um novo</a> ?</td>
             </tr>
          <?php
          }
    ?>          
      </tbody>
   </table>
</div>
       <!-- aqui é o corpo da pagina -->
</section>
