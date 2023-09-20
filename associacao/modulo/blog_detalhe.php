             <?php
if(
    (isset($_GET['q']) ) && ($_GET['q']!='')
  ){$q = (int)$_GET['q'];}
                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "SELECT 
                        b.id,
                        b.titulo,
                        b.texto,
                        b.foto,
                        b.data,
                        b.autor,
                        b.view,
                        c.categoria,
                        c.id
                         FROM tb_blog as b 
                         inner join tb_categoria as c
                         on(b.id_categoria = c.id)  where b.id=$q and b.ativo=true order by b.id desc limit 1;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
               

                if ($rows){                
                  foreach ($rows as $b){
                    ?>

      <!--Page title--> 
      <section class="page-title" style="background-image:url(images/background/page-t.jpg)">
         <div class="container">
            <div class="outer-box">
               <h1><?php echo $b['categoria'];?>
                </h1>
               <ul class="bread-crumb clearfix">
                  <li><a href="index.php">Início</a></li>
                  <li class="active"><?php echo $b['titulo'];?></li>
               </ul>
            </div>
         </div>
      </section> 



      <!-- Blog details -->
      <div class="blog single-post sp-blog">
         <div class="container">
            <div class="row">
               <div class="col-md-8">
                  <div class="news-block-three">
                     <div class="inner-box">
                        <div class="image"><img src="<?php echo $b['foto'];?>" alt=""></div>
                        <div class="lower-content">
                           <h1><?php echo $b['titulo'];?></h1>
                           <ul class="post-meta">
                              <li>Por <strong><?php echo $b['autor'];?></strong></li>
                              <li><span><?php echo strftime("%A, %d de %B de %Y", strtotime($b['data']));?></span></li>
                           </ul>
                           <div class="text">
                              <p><?php echo $b['texto'];?></p>
                           </div>
                            <!--
                           <blockquote class="block-quote">
                              <div class="text">Tempor incididunt.enim ad minim veniam, quis nostrud exer citation ullamco laboris nisi ut aliquip ex ea commodo con sequat duis aute irure.</div>
                           </blockquote>
                           <div class="text">Adipisicing elit sed do ei usmod tempor incididunt.enim ad minim veniam, quis nostrud exer citation ullamco laboris nisi ut aliquip ex ea commodo con sequat duis aute irure dolor. Adipisicing elit sed do ei usmod tempor incididunt.enim ad minim veniam, quis nostrud exer citation ullamco laboris nisi ut aliquip ex ea commodo con sequat duis aute irure.</div> -->
                        </div>
                     </div>
                  </div>
                   
                  <!-- Comments 
                  <div class="comments-area">
                     <div class="group-title">
                        <h2>02 Comentários</h2>
                     </div>
                      

                     <div class="comment-box">
                        <div class="comment">
                           <div class="author-thumb"><img src="images/resource/author-7.jpg" alt=""></div>
                           <div class="comment-inner">
                              <div class="comment-info clearfix">
                                 <strong>Juthi Akon</strong>
                                 <div class="comment-time">26 Apr, 2019</div>
                              </div>
                              <div class="text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form</div>
                              <a class="comment-reply" href="#"><span class="icon fa fa-mail-reply"></span>Reply</a>
                           </div>
                        </div>
                     </div>
                     

                     <div class="comment-box reply-comment">
                        <div class="comment">
                           <div class="author-thumb"><img src="images/resource/author-8.jpg" alt=""></div>
                           <div class="comment-inner">
                              <div class="comment-info clearfix">
                                 <strong>Denial Firoz</strong>
                                 <div class="comment-time">27 May, 2019</div>
                              </div>
                              <div class="text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form</div>
                              <a class="comment-reply" href="#"><span class="icon fa fa-mail-reply"></span>Reply</a>
                           </div>
                        </div>
                     </div>
                  </div>
-->

                  <div class="contact-form">
                     <div class="group-title">
                        <h2>Você pode comentar a postagem</h2>
                     </div>
 
                     <form method="post" action="sendemail.php" id="contact-form">
                        <div class="row clearfix">
                           <div class="col-md-6 form-group"><input type="text" name="username" placeholder="Nome" required></div>
                           <div class="col-md-6 form-group"><input type="email" name="email" placeholder="Email" required></div>
                           <div class="col-md-6 form-group"><input type="text" name="Subject" placeholder="Assunto" required></div>
                           <div class="col-md-6 form-group"><input type="text" name="phone" placeholder="Celular" required></div>
                           <div class="col-lg-12 col-md-12 form-group"><textarea name="message" placeholder="Mensagem"></textarea></div>
                           <div class="col-lg-12 col-md-12 form-group"><button class="theme-btn btn-style-one" type="submit" name="submit-form">Enviar</button></div>
                        </div>
                     </form>
                  </div>

               </div>
               <div class="col-lg-4">
                  <aside class="sidebar mt-991">
                     <div class="sidebar-widget search-widget">
                        <div class="sidebar-title">
                           <form method="post" action="contact.html">
                              <div class="form-group"><input type="search" name="search-field" value="" placeholder="Search Here.." required><button type="submit"><span class="icon fa fa-search"></span></button></div>
                           </form>
                        </div>
                        <div class="sidebar-widget category-widget">
                           <div class="sidebar-title">
                              <h4>ÚLTIMAS POSTAGENS</h4>
                           </div>
                           <ul>
             <?php

                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "SELECT 
                       id, titulo from tb_blog  where  ativo = true order by id desc limit 10;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
                if ($rows){   
                    
                    
                  foreach ($rows as $b){
                     if ($_GET['q'] == $b['id']){$ativo = 'active'; }
                      else {$ativo = '';}
                    ?>                               
                              <li  class="<?php echo $ativo; ?>"><a href="<?php echo '?modulo=blog_detalhe&q='.$b['id'];?>"><?php echo $b['titulo'];?></a></li>
                               <?php 
                      
                  }
                }
                               ?>
                               
                           </ul>
                        </div>
                         <!--
                        <div class="sidebar-widget news-widget">
                           <div class="sidebar-title">
                              <h4>Recentes</h4>
                           </div>
                           <div class="post">
                              <div class="text"><a href="blog-details.html">Postagem 01</a></div>
                              <div class="post-info">by <strong>admin</strong>/ on <span>12 Mar, 2019</span></div>
                           </div>
                           <div class="post">
                              <div class="text"><a href="blog-details.html">Postagem 02</a></div>
                              <div class="post-info">Por <strong>admin</strong>/ on <span>14 Jun, 2019</span></div>
                           </div>
                           <div class="post">
                              <div class="text"><a href="blog-details.html">Postagem 03</a></div>
                              <div class="post-info">Por <strong>admin</strong>/ on <span>26 Aug, 2019</span></div>
                           </div>
                        </div> -->
                         
                        <div class="sidebar-widget tag-widget">
                           <div class="sidebar-title">
                              <h4>Tags</h4>
                           </div>
                          
                            <a href="#">Educação</a>
                            <a href="#">Água</a>
                            <a href="#">Costura</a>
                            <a href="#">Comida</a>
                            <a href="#">Início</a> 
                            
                            
                           
                            
                        </div>

                   </aside>
                   </div>
               </div>
            </div>
         </div>
          <?php }
                }
          ?>
   