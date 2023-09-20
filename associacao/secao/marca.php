  <?php
        $result = $pdo->query('SELECT * from tb_marca where status = 1 limit 40' );
                $marca = $result->fetchAll();
               

                if ($marca){
                    ?>
<section class="testimonial-two1" style="background-image:url(<?php echo $le['banner']?>);">
            <div class="container">
               <div class="sec-title centered style-two">
                  <h1>Nossos   <span>Clientes  </span>  </h1>
                  <span class="flaticon-book">  </span>  
               </div>
               <div class="single-item-carousel owl-carousel owl-theme owl-dot-none owl-nav-style-two">
                   
<?php
                    foreach($marca as $lm){
                        ?>

                    <div class="testimonial-block-two">
                     <div class="inner-box">
                        <div class="info-box">
                           <div class="image2">
                               <img   src="<?php if ($lm['logo'] == '' ){echo 'images/resource/sem.jpg';}else{ echo $lm['logo'];}?>" alt=""> 

                            </div>
                        </div>
                     </div>
                  </div>
                     
                        <?php
                }
                   
                       ?>
               </div>
            </div>
         </section>
<?php } ?>