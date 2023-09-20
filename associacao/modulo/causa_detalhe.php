            <?php
if(isset($_GET['q'])){
 (int)$q=$_GET['q'];   
}
else {$q=1;}
                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "SELECT
c.categoria,
ca.id,
ca.id_categoria,
ca.titulo,
ca.texto,
ca.foto,
ca.data,
ca.previsao,
ca.criador,
ca.localizacao,
ca.views
FROM
	tb_causa ca
	LEFT JOIN  tb_categoria c ON ( ca.id_categoria = c.ID ) 
WHERE
	ca.ativo = TRUE 
    and ca.id=$q
ORDER BY
	ca.ID DESC 
	LIMIT 1;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                if ($rows){   
                    
                    
                  foreach ($rows as $c){
                    ?> 
<!--Page title-->
         <section class="page-title" style="background-image:url(<?php echo $le['banner'];?>)">
            <div class="container">
               <div class="outer-box">
                  <h1><?php echo $c['titulo'];?></h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.php">Início</a></li>
                     <li class="active"><?php echo $c['titulo'];?></li>
                  </ul>
               </div>
            </div>
         </section>
         <!-- Case details -->
         <section class="cause-details">
            <div class="container">
                
               <div class="row">
                  <div class="col-lg-8">
                     <div class="image large mb-30"><img src="<?php echo $c['foto'];?>" alt=""></div>
                     <div class="donate-bar">
                        <div class="progress-title">
                           <h4><?php echo $c['titulo'];?></h4>
                        </div>
                        <div class="bar-inner">
                           <div class="bar progress-line" data-percent="85">
                              <div class="count-box"><span class="count-text" data-speed="2000" data-stop="85">0</span>%</div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="project-info mb-30">
                        <h4>AÇÃO</h4>
                        <ul class="project-info-list">
                           <li><span class="icon flaticon-group"></span><strong>Criador</strong><br><?php echo $c['criador'];?></li>
                           <li><span class="icon flaticon-book"></span><strong>Data</strong><br>Início: <?php echo $c['data'];?> <br> Previsão:  <?php echo $c['previsao'];?> </li>
                           <li><span class="icon flaticon-project"></span><strong>Categoria</strong><br> <?php echo $c['categoria'];?></li>
                           <li><span class="icon flaticon-placeholder"></span><strong>Localização</strong><br> <?php echo $c['localizacao'];?></li>
                        </ul>
                     </div>
                  </div>
               </div>
                 
               <!-- feature case two -->
               <div class="cause-case-two">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="inner-box mt-991">
                           <h4><a href="<?php echo $c['titulo'];?>"><?php echo $c['titulo'];?></a></h4>
                           <div class="cause-text">
                              <p><?php echo $c['texto']; ?></p>
                           </div>
                           <div class="donate-box">
                              <div class="donate-info"><strong>Doações: </strong>R$5.600,00 / <span class="goal">R$7.000,00</span></div>
                           </div>
                           <div class="link-btn"><a href="?modulo=contato" class="theme-btn btn-style-five">Contato</a></div>
                        </div>
                     </div>
                  </div>
               </div>
                

                
               <!-- end -->
            </div>
         </section>
<?php
                  }
                }?>