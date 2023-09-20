<section class="main-slider2">
            <div class="container-fluid">
               <ul class="main-slider-carousel owl-carousel owl-theme slide-nav">
                   
                   
 <?php
                  
                //echo(limitarTexto($titulo_nome, $limite = 40));
                $sql = "SELECT 
                        b.id,
                        b.titulo,
                        b.texto,
                        b.foto,
                        b.data,
                        b.autor,
                        b.view,
                        c.id, 
                        c.categoria
                         FROM tb_blog as b 
                         inner join tb_categoria as c
                         on(b.id_categoria = c.id)  where b.ativo=true order by b.id desc limit 1;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
               

                if ($rows){
                    
                   foreach ($rows as $s)
                   {
                       ?>
                       
                   <li class="slider-wrapper">
                     <div class="image">  <img src="<?php echo $s['foto'];?>" alt="">  </div>
                     <div class="slider-caption">
                        <div class="container">
                           <h2><?php echo $s['titulo'];?></h2>
                           <h1><?php echo $s['categoria'];?></h1>
                           <div class="text"><?php echo(limitarTexto($s['texto'], $limite = 200)); ?> </div>
                           <div class="link-btn">  <a href="index.php?modulo=blog_detalhe&q=<?php echo $s['id'];?>" class="theme-btn btn-style-one">Fale Conosco  </a>  </div>
                        </div>
                     </div>
                     <div class="slide-overlay">  </div>
                  </li>                    
                   
                   <?php
                   }
                } 
                   
 //projetos
                //echo(limitarTexto($titulo_nome, $limite = 40));
                $sql = "SELECT * from tb_projeto  where ativo=true order by id desc limit 1;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
               

                if ($rows){
                    
                   foreach ($rows as $p)
                   {
                       ?>
                       
                   <li class="slider-wrapper">
                     <div class="image">  <img src="<?php echo $p['foto'];?>" alt="">  </div>
                     <div class="slider-caption">
                        <div class="container">
                           <h2><?php echo $p['titulo'];?></h2>
                           <h1>PROJETO</h1>
                           <div class="text"><?php echo(limitarTexto($p['texto'], $limite = 200)); ?> </div>
                           <div class="link-btn">  <a href="index.php?modulo=projeto&q=<?php echo $p['id'];?>" class="theme-btn btn-style-one">Saber mais  </a>  </div>
                        </div>
                     </div>
                     <div class="slide-overlay">  </div>
                  </li>                    
                   
                   <?php
                   }
                }                     
  
                   
 //eventos
                //echo(limitarTexto($titulo_nome, $limite = 40));
                $sql = "SELECT * from tb_evento  where ativo=true order by id desc limit 1;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
               

                if ($rows){
                    
                   foreach ($rows as $e)
                   {
                       ?>
                       
                   <li class="slider-wrapper">
                     <div class="image">  <img src="<?php echo $e['foto'];?>" alt="">  </div>
                     <div class="slider-caption">
                        <div class="container">
                           <h2><?php echo $e['titulo'];?></h2>
                           <h1>EVENTO</h1>
                           <div class="text"><?php echo(limitarTexto($e['texto'], $limite = 200)); ?> </div>
                           <div class="link-btn">  <a href="index.php?modulo=evento&q=<?php echo $e['id'];?>" class="theme-btn btn-style-one">Saber mais  </a>  </div>
                        </div>
                     </div>
                     <div class="slide-overlay">  </div>
                  </li>                    
                   
                   <?php
                   }
                }                     
 
 //causa
                //echo(limitarTexto($titulo_nome, $limite = 40));
                $sql = "SELECT 	* from tb_causa  where ativo=true order by id desc limit 1;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
               

                if ($rows){
                    
                   foreach ($rows as $c)
                   {
                       ?>
                       
                   <li class="slider-wrapper">
                     <div class="image">  <img src="<?php echo $c['foto'];?>" alt="">  </div>
                     <div class="slider-caption">
                        <div class="container">
                           <h2><?php echo $c['titulo'];?></h2>
                           <h1>CAUSA</h1>
                           <div class="text"><?php echo(limitarTexto($c['texto'], $limite = 200)); ?> </div>
                           <div class="link-btn">  <a href="index.php?modulo=causa&q=<?php echo $c['id'];?>" class="theme-btn btn-style-one">Saber mais  </a>  </div>
                        </div>
                     </div>
                     <div class="slide-overlay">  </div>
                  </li>                    
                   
                   <?php
                   }
                }                      
?>
                   

               </ul>
            </div>
         </section>