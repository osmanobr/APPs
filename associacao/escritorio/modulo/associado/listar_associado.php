<!-- titulo da pagina -->
<section class="content-header">
    <h1>
        Módulo:
        <small>Listando setor</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Início</a></li>
        <li class="active">Listando setor</li>
    </ol>
</section>
<section class="content">
 

<div class="container">

   <table class="table">
      <thead>
         <tr>
            <th scope="col">ID</th>
            <th scope="col">Setor</th>
            <th scope="col">Ação</th>
      </thead>
      <tbody>
          <?php include_once('../conexao.php'); 

                $sql = "SELECT * from tb_setor order by id desc limit 60;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                      if ($rows){   
                              foreach ($rows as $r){
         ?>  
                                     <tr>
                                        <td><?php echo $r['id'];?></td>
                                        <td><?php echo $r['setor'];?></td>
                                        <td><a href="?modulo=modulo/setor/editar_setor&q=<?php echo $r['id'];?>">Editar</a></td>
                                     </tr>
        <?php
                               }
                        }
          else
          { ?>
             <tr>
                <td colspan="4">Nenhum registro encontrado deseja <a href="?modulo=modulo/setor/editar_setor&q=0">criar um novo</a> ?</td>
             </tr>
          <?php
          }
    ?>          
      </tbody>
   </table>
</div>
       <!-- aqui é o corpo da pagina -->
</section>
