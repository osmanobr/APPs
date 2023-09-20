<section class="our-team" style="background-image:url(<?php echo $le['banner2'];?>);">
            <div class="container">
               <!-- Sec title -->  
               <div class="sec-title centered">
                  <h1>Últimos   <span>Associados  </span>  </h1>
                  <span class="flaticon-cash">  </span>  
               </div>
               <div class="row">
 <?php                 $result = $pdo->query('select * from tb_filiado order by id desc limit 3' );
                $rows = $result->fetchAll();
               

                if ($rows){
                  foreach ($rows as $as){                  
?>                  
                  <!-- Team member -->  
                  <div class="team-block-one col-lg-4 col-md-6">
                     <div class="inner-box">
                        <div class="image">
                           <a href="?modulo=time">  <img src="<?php if ($as['foto_perfil'] == '' ){echo 'images/resource/sem.jpg';}else{ echo $as['foto_perfil'];}?>" alt="">  </a>  
                           <div class="overlay-box"> <!--
                              <ul class="social-links clearfix">
                                 <li>  <a href="#">  <span class="fa fa-facebook">  </span>  </a>  </li>
                                 <li>  <a href="#">  <span class="fa fa-twitter">  </span>  </a>  </li>
                                 <li>  <a href="#">  <span class="fa fa-vimeo">  </span>  </a>  </li>
                                 <li>  <a href="#">  <span class="fa fa-linkedin">  </span>  </a>  </li>
                              </ul> -->
                           </div>
                        </div>
                        <div class="lower-box">
                           <h4>  <a href="#"><?php echo strtok( $as['nome'], " "); ?></a>  </h4>
                           <div class="designation"><?php echo $as['tipo_filiado'];?></div>
                        </div>
                     </div>
                  </div>
  <?php
                                        }}
                   ?>                 
                   
                  <!-- end -->  
               </div>
            </div>
         </section>