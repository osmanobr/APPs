     <!--Page title-->
         <section class="page-title" style="background-image:url(images/background/page-t.jpg)">
            <div class="container">
               <div class="outer-box">
                  <h1>Causas</h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.php">Início</a></li>
                     <li class="active">Causas</li>
                  </ul>
               </div>
            </div>
         </section>
         <!-- Causes Section -->
         <section class="causes-section sp-two">
            <div class="container">
               <div class="sec-title centered">
                  <h1>Causas <span>Recentes</span></h1>
                  <span class="flaticon-cash"></span>
               </div>
               <!-- causes block -->
               <div class="row">
            <?php

                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "SELECT * from tb_causa  where  ativo = true order by id desc limit 40;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                if ($rows){   
                    
                    
                  foreach ($rows as $c){
                    ?>                               
                   
                  <div class="col-lg-4">
                     <div class="causes-block wow fadeInLeft animated">
                        <div class="inner-box hvr-float-shadow">
                           <div class="image"><a href="?modulo=causa_detalhe&q=<?php echo $c['id']; ?>"><?php echo $c['titulo']; ?><img src="<?php echo $c['foto']; ?>" alt=""></a></div>
                           <div class="lower-content">
                              <div class="icon-box"><span class="flaticon-exam"></span></div>
                              <h4><a href="?modulo=causa_detalhe&q=<?php echo $c['id']; ?>"><?php echo $c['titulo']; ?></a></h4>
                              <div class="text"><?php echo(limitarTexto($c['texto'], $limite = 50)); ?></div>
                           </div>
                           <div class="donate-box">
                              <div class="donate-bar">
                                 <div class="bar-inner">
                                    <div class="bar progress-line" data-percent="19"></div>
                                 </div>
                              </div>
                              <div class="donate-info"><strong>Doações :</strong>R$5.600,00 / <span class="goal">R$30.000,00</span></div>
                              <a href="#" class="theme-btn btn-style-three">Ajudar</a>
                           </div>
                        </div>
                     </div>
                  </div>
<?php 
                  }
                }
                   ?>                  

                   
               </div>
            </div>
         </section>