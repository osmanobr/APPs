         <section class="page-title" style="background-image:url(images/background/page-t.jpg)">
            <div class="container">
               <div class="outer-box">
                  <h1>Eventos</h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.php">Início</a></li>
                     <li class="active">Eventos</li>
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

                $sql = "SELECT * from tb_evento  where  ativo = true order by id desc limit 40;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                if ($rows){   
                    
                    
foreach ($rows as $ev){
?>                               

                      <!-- project block -->
                     <div class="project-block-one mix Consumer al col-lg-4 col-md-6">
                        <div class="inner-box">
                           <div class="image">
                              <img src="<?php echo $ev['foto'];?>" alt="">
                              <div class="caption">
                                 <div>
                                    <h4><?php echo $ev['titulo'];?></h4>
                                 </div>
                              </div>
                              <div class="overlay-box">
                                 <div class="overlay-inner">
                                    <div class="content">
                                       <h4><a href="?modulo=projeto_detalhe&id=<?php echo $ev['id'];?>"><?php echo $ev['titulo'];?></a></h4>
                                       <div class="text"><?php echo(limitarTexto($ev['texto'], $limite = 50)); ?></div>
                                       <a href="?modulo=evento_detalhe&id=<?php echo $ev['id'];?>" class="read-more">Ver mais</a>
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
  