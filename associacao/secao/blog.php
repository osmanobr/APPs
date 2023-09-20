<section class="blog-section grey-bg">
    <div class="container">
        <div class="sec-title centered">
            <h1>Últimas <span>Novidades  </span>  </h1>
            <span class="flaticon-cash">  </span>  
        </div>
        <div class="row">
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
                        c.categoria,
                        c.id
                         FROM tb_blog as b 
                         inner join tb_categoria as c
                         on(b.id_categoria = c.id)  where b.ativo=true order by b.id desc limit 3;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
               

                if ($rows){
                    
                 if (count($rows)==1 ){$col = 'col-lg-12';}
                   else
                       if (count($rows)==2 ){$col = 'col-lg-6';}
                           else
                               if (count($rows)==3 ){$col = 'col-lg-4';}
                    
                  foreach ($rows as $b){
                    ?>
            <div class="<?php echo $col; ?> news-block-one wow fadeInLeft animated">
                <div class="inner-box">
                    <div class="image">
                        <img src="<?php echo $b['foto'];?>" alt="">  
                        <div class="overlay">  <a class="link-btn" href="index.php?modulo=modulo/blog_detalhe&q=<?php echo $b['id'];?>">  <i class="fa fa-long-arrow-right">  </i>  </a>  </div>
                    </div>
                    <div class="lower-content style-two">
                        <h4>  <a href="index.php?modulo=modulo/blog_detalhe&q=<?php echo $b['id'];?>"><?php echo $b['titulo'];?></a>  </h4>
                        <ul class="post-meta">
                            <li>Por   <strong><?php echo $b['autor'];?></strong>  </li>
                            <li>  <span><?php echo strftime("%A, %d de %B de %Y", strtotime($b['data']));?></span>  </li>
                        </ul>
                        <div class="text"><?php echo(limitarTexto($b['texto'], $limite = 200)); ?> </div>
                        <div class="link-btn">
                            <a href="index.php?modulo=modulo/blog_detalhe&q=<?php echo $b['id'];?>" class="read-more-btn">Ver Mais <span class="fa fa-long-arrow-right"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                  }
                  }
            ?>
            
   <!--
                  <div class="col-lg-4 news-block-one wow fadeInLeft animated">
                     <div class="inner-box">
                        <div class="image">
                           <img src="images/resource/news-3.jpg" alt="">  
                           <div class="overlay">  <a class="link-btn" href="blog-details.html">  <i class="fa fa-long-arrow-right">  </i>  </a>  </div>
                        </div>
                        <div class="lower-content style-two">
                           <h4>  <a href="blog-details.html">Free Education For Children  </a>  </h4>
                           <ul class="post-meta">
                              <li>by   <strong>Robert Rasel  </strong>  </li>
                              <li>  <span>6 Jan, 2019  </span>  </li>
                           </ul>
                           <div class="text">Consectetur adipisicing elit sed do ei usmod tempor incididunt.enim ad minim veniam.  </div>
                           <div class="link-btn">  <a href="blog-details.html" class="read-more-btn">Ver Mais   <span class="fa fa-long-arrow-right">  </span>  </a>  </div>
                        </div>
                     </div>
                  </div>
                
                  <div class="col-lg-4 news-block-one wow fadeInRight animated">
                     <div class="inner-box">
                        <div class="image">
                           <img src="images/resource/news-4.jpg" alt="">  
                           <div class="overlay">  <a class="link-btn" href="blog-details.html">  <i class="fa fa-long-arrow-right">  </i>  </a>  </div>
                        </div>
                        <div class="lower-content style-two">
                           <h4>  <a href="blog-details.html">Free Treatment For Children  </a>  </h4>
                           <ul class="post-meta">
                              <li>by   <strong>Denial Kutub  </strong>  </li>
                              <li>  <span>9 Jan, 2019  </span>  </li>
                           </ul>
                           <div class="text">Consectetur adipisicing elit sed do ei usmod tempor incididunt.enim ad minim veniam.  </div>
                           <div class="link-btn">  <a href="blog-details.html" class="read-more-btn">Ver Mais   <span class="fa fa-long-arrow-right">  </span>  </a>  </div>
                        </div>
                     </div>
                  </div>
                   -->
               </div>
            </div>
</section>