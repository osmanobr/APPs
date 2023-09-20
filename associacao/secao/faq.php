         <section class="faq-section">
            <div class="container">
               <div class="row">
                  <div class="col-lg-7">
                     <div class="section-title three">
                        <h1>FAQ <span> Respostas</span></h1>
                     </div>
                     <div class="text mb-30">Centralizamos aqui os questionamentos mais recorrentes feitos pelos nossos associados, caso tenha alguma pergunta que não conste nesta lista use o formulario tire dúvidas para enviar sua pergunta.</div>
                     <ul class="accordion-box">
<?php
$sql = "SELECT * FROM tb_faq limit 30;";
$result = $pdo->query( $sql );
$rows = $result->fetchAll();
if ($rows){
foreach ($rows as $f){
?>
                        <li class="accordion block">
                           <div class="acc-btn">
                              <div class="icon-outer"><?php echo $f['id'];?></div>
                              <?php echo $f['faq'];?>
                           </div>
                           <div class="acc-content">
                              <div class="content">
                                 <div class="text"><?php echo $f['texto'];?></div>
                              </div>
                           </div>
                        </li>                        
    <?php        
        }      
        }
    ?>                        

                     </ul>
                     <!-- end -->
                  </div>
                  <div class="col-lg-5">
                     <div class="consultation-two mb-40">
                        <h4>Tire Dúvidas</h4>
                        <!-- default form -->
                        <div class="default-form-area">
                           <form id="contact-form" name="contact_form" class="contact-form" action="sendmail.php" method="post" novalidate="novalidate">
                              <div class="row clearfix">
                                 <div class="col-lg-12 column">
                                    <div class="form-group"><input type="text" name="form_name" class="form-control" value="" placeholder="Nome" required="" aria-required="true"></div>
                                 </div>
                                 <div class="col-lg-12 column">
                                    <div class="form-group"><input type="email" name="form_email" class="form-control required email" value="" placeholder="Email" required="" aria-required="true"></div>
                                 </div>
                                 <div class="col-lg-12 column">
                                    <div class="form-group"><input type="text" name="form_phone" class="form-control" value="" placeholder="celular" required="" aria-required="true"></div>
                                 </div>
                                 <div class="col-lg-12 column">
                                    <div class="form-group"><textarea name="form_message" class="form-control textarea required" placeholder="Sua dúvida" aria-required="true"></textarea></div>
                                 </div>
                              </div>
                              <div class="contact-section-btn">
                                 <div class="form-group"><input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden" value=""><button class="theme-btn btn-style-one" type="submit" data-loading-text="Aguarde...">Enviar agora</button></div>
                              </div>
                           </form>
                        </div>
                        <ul class="social-icon-three">
                           <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                           <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                           <li><a href="#"><i class="fa fa-vimeo"></i></a></li>
                           <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                        <!-- end default form -->
                     </div>
                  </div>
               </div>
            </div>
         </section>
         