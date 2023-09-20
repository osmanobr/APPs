           <?php

                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "
                SELECT
                c.categoria,
                p.id,
                p.titulo,
                p.texto,
                p.foto,
                p.data,
                p.valor,
                p.valor_arrecadado,
                p.view
                from tb_projeto p
                inner join tb_categoria c on(c.id=p.id_categoria) limit 1;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                if ($rows){   
                    
                    
                  foreach ($rows as $p){
                    ?> 
<section class="page-title" style="background-image:url(images/background/page-t.jpg)">
            <div class="container">
               <div class="outer-box">
                  <h1><?php echo $p['titulo']?></h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.php">Início</a></li>
                      <li><a href="?modulo=projeto">Início</a></li>
                     <li class="active"><?php echo $p['titulo']?></li>
                  </ul>
               </div>
            </div>
         </section>
         <!-- Project details -->

         
<section class="project-details">
            <div class="container">

                
 <div class="row">
<div class="col-lg-8">
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
     </div>
     <div class="col-lg-4">
                        <div class="project-info mb-30 mt-991-2">
                           <h4>Projeto</h4>
                           <ul class="project-info-list">
                              <li><span class="icon flaticon-group"></span><strong>Criador</strong><br>Osto</li>
                              <li><span class="icon flaticon-book"></span><strong>Início</strong><br>Julho 30, 2021<strong>Previsão</strong><br>Julho 30, 2021</li>
                              <li><span class="icon flaticon-project"></span><strong>Categoria</strong><br>Curso Gratis</li>
                              <li><span class="icon flaticon-placeholder"></span><strong>Localização</strong><br>Tocantins, São Paulo, Goiás</li>
                           </ul>
                        </div>
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
     </div>
</div>
<div class="row">                
                
               <div class="content">
                  <div class="row">
                     <div class="col-lg-12">
                        <h1><?php echo $p['titulo'];?></h1>
                        <div class="text mb-70">
                           <p><?php echo $p['texto'];?></p>
                        </div>
                     </div>
                     <div class="col-lg-12"> 
                        <h2>Você pode gostar</h2> 
                    </div> 
                      <div class="col-lg-12"> 
                        <div class="image"><img src="images/resource/graph-4.jpg" alt=""></div>
                        <h4>Projeto 02</h4>
                      </div>
                     <div class="col-lg-12">     
                        <div class="text mb-30">
                           <p>esses são os dados do 2 projeto</p>
                        </div>  
                    </div>
                      
                        <h4>Projeto 03</h4>
                        <div class="text mb-30">
                           <p>esses são os dados do 3 projeto</p>
                        </div> 
                        <h4>Projeto 04</h4>
                        <div class="text mb-30">
                           <p>esses são os dados do 4 projeto</p>
                        </div>                         
                     </div>

                  </div>
               </div>
    </div>
         </section>
<?php }}?>