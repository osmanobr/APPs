         <section class="page-title" style="background-image:url(images/background/page-t.jpg)">
            <div class="container">
               <div class="outer-box">
                  <h1>Projetos</h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.html">Início</a></li>
                     <li class="active">Projetos</li>
                  </ul>
               </div>
            </div>
         </section>
         <!-- Our Projects -->
         <section class="project-section">
            <div class="container">
               <div class="mixitup-gallery">
                  <!--Filter-->
                  <div class="filters clearfix center">
                     <ul class="filter-tabs filter-btns">
                        <li class="filter active" data-role="button" data-filter="all"><span>Todos</span></li>
                        <li class="filter" data-role="button" data-filter=".Business"><span>Teinamento gratis</span></li>
                        <li class="filter" data-role="button" data-filter=".Finance"><span>Educação gratis</span></li>
                        <li class="filter" data-role="button" data-filter=".Consumer"><span>Costura gratis</span></li>
                        <li class="filter" data-role="button" data-filter=".Energy"><span>Agua gratis</span></li>
                     </ul>
                  </div>
                  <div class="filter-list row clearfix">
                      
            <?php

                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "SELECT * from tb_projeto  where  ativo = true order by id desc limit 40;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                if ($rows){   
                    
                    
                  foreach ($rows as $p){
                    ?>                               

                      <!-- project block -->
                     <div class="project-block-one mix Consumer al col-lg-4 col-md-6">
                        <div class="inner-box">
                           <div class="image">
                              <img src="<?php echo $p['foto'];?>" alt="">
                              <div class="caption">
                                 <div>
                                    <h4><?php echo $p['titulo'];?></h4>
                                 </div>
                              </div>
                              <div class="overlay-box">
                                 <div class="overlay-inner">
                                    <div class="content">
                                       <h4><a href="?modulo=projeto_detalhe&id=<?php echo $p['id'];?>"><?php echo $p['titulo'];?></a></h4>
                                       <div class="text"><?php echo(limitarTexto($p['texto'], $limite = 50)); ?></div>
                                       <a href="?modulo=projeto_detalhe&id=<?php echo $p['id'];?>" class="read-more">Ver mais</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                      
<?php }
                }
                      ?>                                        
                     <!-- end -->
                      
                  </div>
               </div>
            </div>
         </section>
  