  <?php
        $result = $pdo->query('SELECT p.nome, p.foto_perfil, p.cidade, p.uf, t.testemunho FROM tb_filiado p 
inner join tb_testemunho t on(t.id_pessoa = p.id) where t.ativo = true limit 40' );
                $testemunho = $result->fetchAll();
               

                if ($testemunho){
                    ?>
<section class="testimonial-two" style="background-image:url(<?php echo $le['banner3']?>);">
            <div class="container">
               <div class="sec-title centered style-two">
                  <h1>O Que falam de   <span>Nós  </span>  </h1>
                  <span class="flaticon-book">  </span>  
               </div>
               <div class="single-item-carousel owl-carousel owl-theme owl-dot-none owl-nav-style-four">
                   
<?php
                    foreach($testemunho as $lt){
                        ?>

                    <div class="testimonial-block-two">
                     <div class="inner-box">
                        <div class="text"><?php echo $lt['testemunho'];?></div>
                        <div class="info-box">
                           <div class="image2">
                               <img  src="<?php if ($lt['foto_perfil'] == '' ){echo 'images/resource/sem.jpg';}else{ echo $lt['foto_perfil'];}?>" alt=""> 

                            </div>
                           <h5 class="name"><?php $nome1=str_word_count($lt['nome'], 1); echo $nome1[0];?></h5>
                           <span class="designation"><?php echo $lt['cidade'];?>/<?php echo $lt['uf'];?>  </span>  
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