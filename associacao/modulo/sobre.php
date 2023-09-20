<?php

//echo(limitarTexto($titulo_nome, $limite = 40));

$sql = "SELECT * from tb_empresa ";
$result = $pdo->query( $sql );
$rows = $result->fetchAll();
if ($rows){
foreach ($rows as $emp){
?>
<!--Page title-->
         <section class="page-title" style="background-image:url(images/background/page-t.jpg)">
            <div class="container">
               <div class="outer-box">
                  <h1>Sobre nós</h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.php">Início</a></li>
                     <li class="active">Sobre nós</li>
                  </ul>
               </div>
            </div>
         </section>
         <!-- What we do -->
         <section class="our-experience-two">
            <div class="container">
               <div class="row">
                  <div class="col-lg-6 pr-lg-4">
                     <div class="text-block-one mb-30">
                        <h3><span>Bem vindo, </span><?php echo $emp['lema'];?></h3>
                        <div class="text">
                           <p><?php echo(limitarTexto($emp['sobre'], $limite = 200)); ?></p>
                        </div>
                        <div class="link-btn"><a href="?modulo=cadastro" class="theme btn-style-one">Quero associar-me</a></div>
                     </div>
                  </div>
                  <div class="col-lg-6 pl-lg-5">
                     <!-- graph image -->
                     <div class="image"><img src="<?php echo $emp['banner'];?>" alt=""></div>
                  </div>
               </div>
            </div>
         </section>
         <section class="about grey-bg">
            <div class="container">
               <!-- sec tietle -->
               <div class="sec-title centered">
                  <h1><?php echo $emp['fantasia'];?></h1>
                  <h2><?php echo $emp['lema'];?></h2>
                  <span class="flaticon-cash"></span>
               </div>
               <div class="row">
<?php                   
$sql2 = "SELECT * from tb_pagina ";
$result2 = $pdo->query( $sql2 );
$rows2 = $result2->fetchAll();
if ($rows2){
foreach ($rows2 as $r){
?>                   
                  <div class="about-block-one col-lg-4 col-md-6">
                     <div class="inner-box">
                        <div class="icon-box"><span class="flaticon-graphic"></span></div>
                        <h4><a href="?modulo=pagina&q=<?php echo $r['id'];?>"><?php echo $r['titulo'];?></a></h4>
                        <div class="text"><?php echo(limitarTexto($r['subtitulo'], $limite = 150)); ?></div>
                        <div class="link-btn"><a href="?modulo=pagina&q=<?php echo $r['id'];?>" class="theme-btn btn-style-two">Saber Mais</a></div>
                     </div>
                  </div>
<?php } } ?>
<!--
                  <div class="about-block-one col-lg-4 col-md-6">
                     <div class="inner-box style-two">
                        <div class="icon-box"><span class="flaticon-creative-idea"></span></div>
                        <h4><a href="?modulo=pagina&q=2">Fundação </a></h4>
                        <div class="text">fundação</div>
                        <div class="link-btn"><a href="?modulo=pagina&q=2" class="theme-btn btn-style-two">Saber Mais</a></div>
                     </div>
                  </div>

                  <div class="about-block-one col-lg-4 col-md-6">
                     <div class="inner-box">
                        <div class="icon-box"><span class="flaticon-businessman-1"></span></div>
                        <h4><a href="?modulo=pagina&q=2">Associados/Voluntários</a></h4>
                        <div class="text">associados</div>
                        <div class="link-btn"><a href="?modulo=pagina&q=2" class="theme-btn btn-style-two">Saber mais</a></div>
                     </div>
                  </div>
-->

               </div>
            </div>
         </section>

<!--
         <div class="fact-section black-bg-3" style="background-image:url(images/background/3.jpg);">
            <div class="counter-column col-lg-12">
               <div class="fact-counter">
                  <div class="container">
                     <div class="row">

                        <div class="column counter-column col-lg-3 col-sm-6 col-xs-12">
                           <div class="inner">
                              <div class="fact-icon"><i class="flaticon-cash"></i></div>
                              <div class="count-outer count-box"><span class="count-text style-2" data-speed="2500" data-stop="34314">0</span></div>
                              <h4 class="counter-title style-2">DOAÇÕES</h4>
                           </div>
                        </div>

                        <div class="column counter-column col-lg-3 col-sm-6 col-xs-12">
                           <div class="inner">
                              <div class="fact-icon"><i class="flaticon-project"></i></div>
                              <div class="count-outer count-box"><span class="count-text style-2" data-speed="2500" data-stop="1275">0</span></div>
                              <h4 class="counter-title style-2">PROJETOS</h4>
                           </div>
                        </div>

                        <div class="column counter-column col-lg-3 col-sm-6 col-xs-12">
                           <div class="inner">
                              <div class="fact-icon"><i class="flaticon-shirt"></i></div>
                              <div class="count-outer count-box"><span class="count-text style-2" data-speed="2500" data-stop="575">0</span></div>
                              <h4 class="counter-title style-2">VOLUNTÁRIOS</h4>
                           </div>
                        </div>

                        <div class="column counter-column col-lg-3 col-sm-6 col-xs-12">
                           <div class="inner">
                              <div class="fact-icon"><i class="flaticon-trophy"></i></div>
                              <div class="count-outer count-box"><span class="count-text style-2" data-speed="2500" data-stop="380">0</span></div>
                              <h4 class="counter-title style-2">PRÊMIOS</h4>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
-->
<!--
         <section class="our-team sp-two">
            <div class="container">

               <div class="sec-title centered">
                  <h1>Últimos <span>Associados / Voluntários</span></h1>
                  <span class="flaticon-cash"></span>
               </div>
               <div class="row">

                  <div class="team-block-one col-lg-4 col-md-6">
                     <div class="inner-box">
                        <div class="image">
                           <a href="team.html"><img src="images/resource/team-1.jpg" alt=""></a>
                           <div class="overlay-box">
                              <ul class="social-links clearfix">
                                 <li><a href="#"><span class="fa fa-facebook"></span></a></li>
                                 <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                 <li><a href="#"><span class="fa fa-vimeo"></span></a></li>
                                 <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                              </ul>
                           </div>
                        </div>
                        <div class="lower-box">
                           <h4><a href="#">Roberto Frientz</a></h4>
                           <div class="designation">Voluntário</div>
                        </div>
                     </div>
                  </div>

                  <div class="team-block-one col-lg-4 col-md-6">
                     <div class="inner-box">
                        <div class="image">
                           <a href="team.html"><img src="images/resource/team-2.jpg" alt=""></a>
                           <div class="overlay-box">
                              <ul class="social-links clearfix">
                                 <li><a href="#"><span class="fa fa-facebook"></span></a></li>
                                 <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                 <li><a href="#"><span class="fa fa-vimeo"></span></a></li>
                                 <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                              </ul>
                           </div>
                        </div>
                        <div class="lower-box">
                           <h4><a href="#">Denise Aires</a></h4>
                           <div class="designation">Voluntaria</div>
                        </div>
                     </div>
                  </div>
                 
                  <div class="team-block-one col-lg-4 col-md-6">
                     <div class="inner-box">
                        <div class="image">
                           <a href="team.html"><img src="images/resource/team-3.jpg" alt=""></a>
                           <div class="overlay-box">
                              <ul class="social-links clearfix">
                                 <li><a href="#"><span class="fa fa-facebook"></span></a></li>
                                 <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                 <li><a href="#"><span class="fa fa-vimeo"></span></a></li>
                                 <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                              </ul>
                           </div>
                        </div>
                        <div class="lower-box">
                           <h4><a href="#">Neuzirene Brito</a></h4>
                           <div class="designation">Voluntaria</div>
                        </div>
                     </div>
                  </div>
                  
               </div>
            </div>
         </section>



         <section class="client-logo grey-bg">
            <div class="container">
               <div class="client-logo-area">
                  <ul class="five-item-carousel owl-carousel owl-theme owl-theme owl-dot-none owl-nav-none">
                     <li><a href="#"><img src="images/clients/6.png" alt=""></a></li>
                     <li><a href="#"><img src="images/clients/7.png" alt=""></a></li>
                     <li><a href="#"><img src="images/clients/8.png" alt=""></a></li>
                     <li><a href="#"><img src="images/clients/9.png" alt=""></a></li>
                     <li><a href="#"><img src="images/clients/10.png" alt=""></a></li>
                  </ul>
               </div>
            </div>
         </section>
-->
 <?php }}?>