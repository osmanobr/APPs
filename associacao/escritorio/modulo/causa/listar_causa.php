
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>LISTA DE CAUSAS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Início</a></li>
                        <li class="breadcrumb-item active">Causas</li>
                        <li class="breadcrumb-item active">
                        <a href="?modulo=modulo/causa/editar_causa&q=0"><img src="dist/img/novo.png" width="60px"></a></li>    
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

   <table class="table">
      <thead>
         <tr>
            <th scope="col">ID</th>
            <th scope="col">Titulo</th>
             <th scope="col">Data</th>
             <th scope="col">Criador</th>
             <th scope="col">Ativo</th>
      </thead>
      <tbody>
          <?php  

                $sql = "SELECT * from tb_causa order by id desc limit 60;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                      if ($rows){   
                              foreach ($rows as $r){
         ?>  
                                     <tr>
                                        <td><?php echo $r['id_categoria'];?></td>
                                        <td><?php echo $r['titulo'];?></td>
                                        <td><?php echo $r['data'];?></td>
                                        <td><?php echo $r['criador'];?></td>
                                        <td><?php echo $r['ativo'];?></td>
                                        <td><a href="?modulo=modulo/causa/editar_causa&q=<?php echo $r['id'];?>">Editar</a></td>
                                     </tr>
        <?php
                               }
                        }
          else
          { ?>
             <tr>
                <td colspan="4">Nenhum registro encontrado</td>
             </tr>
          <?php
          }
    ?>          
      </tbody>
   </table>

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
