           <?php

                //echo(limitarTexto($titulo_nome, $limite = 40));
if (isset($_GET['q'])){(int)$q=$_GET['q'];}
else
    $q=1;
$sql = "SELECT * from tb_pagina where id=$q ;";
$result = $pdo->query( $sql );
$rows = $result->fetchAll();
if ($rows){   
foreach ($rows as $p){
?>   
<section class="page-title" style="background-image:url(images/background/page-t.jpg)">
            <div class="container">
               <div class="outer-box">
                  <h1><?php echo $p['titulo'];?></h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.php">Início</a></li>
                     <li><a href="?modulo=sobre">Sobre</a></li>
                     <li class="active"><?php echo $p['titulo'];?></li>
                  </ul>
               </div>
            </div>
         </section>
         <!-- Project details -->
       
<section class="project-details">
            <div class="container">
               <div class="image large mb-30">
                  <img src="images/resource/ps.jpg" alt=""><!--
                  <div class="donate-bar">
                     <div class="progress-title">
                        <h4><?php // echo $p['titulo'];?></h4>
                     </div>
                     <div class="bar-inner">
                        <div class="bar progress-line" data-percent="85">
                           <div class="count-box"><span class="count-text" data-speed="2000" data-stop="85">0</span>%</div> 
                        </div>
                     </div>
                  </div>-->
               </div>
               <div class="content">
                  <div class="row">
                     <div class="col-lg-8">
                        <h1><?php echo $p['titulo'];?></h1>

                        <h3><?php echo $p['subtitulo'];?></h3>
                        <div class="text mb-30">
                           <p><?php echo $p['texto'];?></p>
                        </div>
                        <!--<div class="image"><img src="images/resource/graph-4.jpg" alt=""></div>-->
                     </div>
                     <div class="col-lg-4">
                        <div class="project-info mb-30 mt-991-2">
                           <h4>Informe-se</h4>
                           <ul class="project-info-list">
                            <?php                               
                            $sql2 = "SELECT * from tb_pagina where id<>$q ;";
                            $result2 = $pdo->query( $sql2 );
                            $rows2 = $result2->fetchAll();
                            if ($rows2){   
                            foreach ($rows2 as $p2){
                            ?>                                 

                            <li><span class="icon flaticon-book"></span><a href="?modulo=pagina&q=<?php echo $p2['id'];?>" style="color: #99ccff"><?php echo $p2['titulo'];?></a></li>

                            <?php
                            }
                            }?>                               
                           </ul>
                        </div>
                        <div class="pro-right">
                           <h6>Compartilhe</h6>
                           <ul class="social">
                              <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                              <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                              <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                              <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                              <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
<?php }}?>