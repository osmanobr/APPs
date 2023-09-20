<section class="causes-section" style="background-image:url(images/background/bg-2.png);">
            <div class="container">
               <div class="sec-title centered">
                  <h1>CAUSAS   <span>RECENTES  </span>  </h1>
                  <span class="flaticon-cash">  </span>  
               </div>
               <!-- causes block -->  
               <div class="row">
<?php
                $sql = "SELECT * from tb_causa  where ativo=true order by id desc limit 4;";
                $result = $pdo->query( $sql );
                $rowsc = $result->fetchAll();
               

                if ($rowsc){
                    
                    $colc = count($rowsc);
                    if (count($rowsc) == 4){$colc = 'col-lg-3';}
                      else
                          if (count($rowsc) == 3){$colc = 'col-lg-4';}
                              else
                                  if (count($rowsc) == 2){$colc = 'col-lg-6';}
                                       else
                                          if (count($rowsc) == 1){$colc = 'col-lg-12';}                   
                   foreach ($rowsc as $c)
                   {
                       ?>                   
                  <div class="<?php echo $colc; ?> col-sm-6">
                     <div class="causes-block wow fadeInLeft animated">
                        <div class="inner-box hvr-float-shadow">
                           <div class="image">  <img src="<?php echo $c['foto'];?>" alt="">  </div>
                           <div class="lower-content">
                              <div class="icon-box">  <span class="flaticon-exam">  </span>  </div>
                              <h4>  <a href="index.php?modulo=modulo/evento&q=<?php echo $e['id'];?>"><?php echo $e['titulo'];?>  </a>  </h4>
                              <div class="text"><?php echo(limitarTexto($c['texto'], $limite = 200)); ?> </div>
                           </div>
                           <div class="donate-box">
                              <div class="donate-bar">
                                 <div class="bar-inner">
                                    <div class="bar progress-line" data-percent="82">  </div>
                                 </div>
                              </div>
                              <div class="donate-info">  <strong>Doações :  </strong><?php echo $e['id'];?> /   <span class="goal"><?php echo $e['id'];?>  </span>  </div>
                              <a href="index.php?modulo=modulo/cadastro&q=<?php echo $e['id'];?>" class="theme-btn btn-style-three">Cadastre-se  </a>  
                           </div>
                        </div>
                     </div>
                  </div>
 <?php }
                }?>                  
                                     
               </div>
            </div>
         </section>