<!-- titulo da pagina -->
<section class="content-header">
    <h1>
        Módulo:
        <small>Listando departamentos</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Início</a></li>
        <li class="active">Listando departamentos</li>
    </ol>
</section>
<section class="content">
 

<div class="container">

   <table class="table">
      <thead>
         <tr>
            <th scope="col">ID</th>
            <th scope="col">departamento</th>
            <th scope="col">Ação</th>
      </thead>
      <tbody>
          <?php

                $sql = "SELECT * from tb_departamento order by id desc limit 60;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                      if ($rows){   
                              foreach ($rows as $r){
         ?>  
                                     <tr>
                                        <td><?php echo $r['id'];?></td>
                                        <td><?php echo $r['departamento'];?></td>
                                        <td><a href="?modulo=modulo/departamento/editar_departamento&q=<?php echo $r['id'];?>">Editar</a></td>
                                     </tr>
        <?php
                               }
                        }
          else
          { ?>
             <tr>
                <td colspan="4">Nenhum registro encontrado deseja <a href="?modulo=modulo/departamento/editar_departamento&q=0">criar um novo</a> ?</td>
             </tr>
          <?php
          }
    ?>          
      </tbody>
   </table>
</div>
       <!-- aqui é o corpo da pagina -->
</section>
