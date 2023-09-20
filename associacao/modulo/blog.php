         <!--Page title-->
         <section class="page-title" style="background-image:url(images/background/page-t.jpg)">
            <div class="container">
               <div class="outer-box">
                  <h1>BLOG</h1>
                  <ul class="bread-crumb clearfix">
                     <li><a href="index.php">Home</a></li>
                     <li class="active">Blog</li>
                  </ul>
               </div>
            </div>
         </section>
         <!-- News Blog -->
         <section class="blog-section sp-blog">
            <div class="container">
               <div class="row">

           <?php

                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "SELECT * from tb_blog  where  ativo = true order by id desc limit 40;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                if ($rows){   
                    
                    
                  foreach ($rows as $b){
                    ?>                               

                              
                   
                  <!-- news block -->
                  <div class="col-lg-4 news-block-one wow fadeInLeft animated">
                     <div class="inner-box">
                        <div class="image">
                           <img src="<?php echo $b['foto'];?>" alt="">
                           <div class="overlay"><a class="link-btn" href="?modulo=blog_detalhe&q=<?php echo $b['id'];?>"><i class="fa fa-long-arrow-right"></i></a></div>
                        </div>
                        <div class="lower-content style-two">
                           <h4><a href="?modulo=blog_detalhe&q=<?php echo $b['id'];?>"><?php echo $b['titulo'];?></a></h4>
                           <ul class="post-meta">
                              <li>Por <strong><?php echo $b['autor'];?></strong></li>
                              <li><span><?php echo date(' H:i - d/m', strtotime($b['data'])); ?></span></li>
                           </ul>
                           <div class="text"><?php echo(limitarTexto($b['texto'], $limite = 200)); ?></div>
                           <div class="link-btn"><a href="?modulo=blog_detalhe&q=<?php echo $b['id'];?>" class="read-more-btn">Ler mais <span class="fa fa-long-arrow-right"></span></a></div>
                        </div>
                     </div>
                  </div>
                  
  <?php 
                      
                  }
                }
                               ?>                  

                  <!-- end -->
                   
               </div>
            </div>
         </section>
 