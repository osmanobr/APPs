           <?php

                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "
                SELECT * from tb_filiado
           limit 1;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                if ($rows){   
                    
                    
                  foreach ($rows as $p){
                    ?> 
<section class="page-title" style="background-image:url(<?php echo $le['banner'];?>)">
            <div class="container">
               <div class="outer-box">
                  <h1><?php echo $p['nome']?></h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.php">Início</a></li>
                      <li><a href="?modulo=projeto">Início</a></li>
                     <li class="active"><?php echo $p['nome']?></li>
                  </ul>
               </div>
            </div>
         </section>
         <!-- Project details -->

         
<section class="project-details">
            <div class="container">

                
 <div class="row">
<div class="col-lg-8">
    <h2>Financeiro</h2>
<!--
                <div class="image large mb-30">
                  <img src="<?php echo $p['foto'];?>" alt="">
                  <div class="donate-bar">
                     <div class="progress-title">
                        <h4><?php echo $p['titulo'];?></h4>
                     </div>
                     <div class="bar-inner">
                        <div class="bar progress-line" data-percent="85">
                           <div class="count-box"><span class="count-text" data-speed="2000" data-stop="85">0</span>%</div>
                        </div>
                     </div>
                  </div>
               </div>    
-->
</div>
     <div class="col-lg-4">
                        <div class="project-info mb-30 mt-991-2">
                           <h4>Perfil</h4>
                           <ul class="project-info-list">
                              <li><span class="icon flaticon-group"></span><strong>Nome</strong><br>Osmano Torres de Brito</li>
                              <li><span class="icon flaticon-book"></span><strong>Endereeço</strong><br>Julho 30, 2021<strong>Previsão</strong><br>Julho 30, 2021</li>
                              <li><span class="icon flaticon-project"></span><strong>Data Cadastro</strong><br>Curso Gratis</li>
                             <!--  <li><span class="icon flaticon-placeholder"></span><strong>Localização</strong><br>Tocantins, São Paulo, Goiás</li> -->
                           </ul>
                        </div>
<!--
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
-->
     </div>
</div>
<div class="row">                
                
               <div class="content">
                  <div class="row">
                     <div class="col-lg-12">
                        <h1>Sobre o associado</h1>
                        <div class="text mb-70">
                           <p><?php // echo $p['texto'];?></p>
                        </div>
                     </div>
                        
                     </div>

                  </div>
               </div>
    </div>
         </section>
<?php }}?>