<section class="upcoming-events events-style-one" style="background-image:url(images/background/bg-2.png);">
            <div class="container">
               <div class="sec-title centered">
                  <h1>Próximos   <span>Eventos  </span>  </h1>
                  <span class="flaticon-cash">  </span>  
               </div>
               <div class="bxslider ">
                   
              <?php

                //echo(limitarTexto($titulo_nome, $limite = 40));

                $sql = "SELECT 
                        e.id,
                        e.id_categoria,
                        e.pais,
                        e.uf,
                        e.cidade,
                        e.bairro,
                        e.endereco,
                        e.titulo,
                        e.texto,
                        e.foto,
                        e.data,
                        e.views,                       
                        c.categoria
                        FROM tb_evento as e 
                         left join tb_categoria as c
                         on(e.id_categoria = c.id)  where e.ativo=true order by e.id desc limit 6;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();
               

                if ($rows){
                  foreach ($rows as $ev){                  
                  ?>
                  <div class="slider-content">
                     <div class="row clearfix">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                           <div class="events-content">
                              <div class="events-info">
                                 <h2>  <a href="?modulo=evento_detalhe&q=<?php echo $ev['id'];?>"><?php echo $ev['titulo'];?>  </a>  </h2>
                              </div>
                              <div class="text">
                                  <p><a href="?modulo=evento_detalhe&q=<?php echo $ev['id'];?>"><?php echo(limitarTexto($ev['texto'], $limite = 300)); // echo $ev['texto']; ?></a>  </p>
                              </div>
                              <ul class="contact-info-list">
                                 <li>
                                    <span class="flaticon-placeholder">  </span>  
                                    <p><?php echo $ev['endereco'];?><br><?php echo $ev['cidade'];?>-<?php echo $ev['uf'];?>/<?php echo $ev['pais'];?></p>
                                 </li>
                              </ul>
                              <div class="date-info">
                                 <h3>Data / Hora :   <span><?php echo strftime("%A, %d de %B de %Y", strtotime($ev['data']));?>  -  <?php echo strftime("%H:%m", strtotime($ev['data']));?></span>  </h3>
                              </div> <!--
                              <div class="slider-pager">
                                 <ul class="thumb-box">
                                    <li>
                                       <a class="active" data-slide-index="0" href="#">
                                          <figure>  <img src="images/resource/sevent-1.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="1" href="#">
                                          <figure>  <img src="images/resource/sevent-2.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="2" href="#">
                                          <figure>  <img src="images/resource/sevent-3.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="3" href="#">
                                          <figure>  <img src="images/resource/sevent-4.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                 </ul>
                              </div> -->
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                           <div class="img-content clearfix">
                              <figure class="img-box">  <a href="events-details.html">  <img src="images/resource/Event-1.jpg" alt="">  </a>  </figure>
                           </div>
                        </div>
                     </div>
                  </div>
 <?php }}?>                  
                  <!-- 
                  <div class="slider-content">
                     <div class="row clearfix">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                           <div class="events-content">
                              <div class="events-info">
                                 <h2>  <a href="#">Uganda  </a>  </h2>
                              </div>
                              <div class="text">
                                 <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est.  </p>
                              </div>
                              <ul class="contact-info-list">
                                 <li>
                                    <span class="icon flaticon-placeholder">  </span>  
                                    <p>PO Box 11251 Quneitra Street   <br>East Talkalakh Uganda   </p>
                                 </li>
                              </ul>
                              <div class="date-info">
                                 <h3>Event Date :   <span>10 April,2019  </span>  </h3>
                              </div>
                              <div class="slider-pager">
                                 <ul class="thumb-box">
                                    <li>
                                       <a class="active" data-slide-index="0" href="#">
                                          <figure>  <img src="images/resource/sevent-1.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="1" href="#">
                                          <figure>  <img src="images/resource/sevent-2.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="2" href="#">
                                          <figure>  <img src="images/resource/sevent-3.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="3" href="#">
                                          <figure>  <img src="images/resource/sevent-4.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                           <div class="img-content clearfix">
                              <figure class="img-box">  <a href="events-details.html">  <img src="images/resource/Event-2.jpg" alt="">  </a>  </figure>
                           </div>
                        </div>
                     </div>
                  </div>
                   
                   
                   
                  <div class="slider-content">
                     <div class="row clearfix">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                           <div class="events-content">
                              <div class="events-info">
                                 <h2>  <a href="#">Palestine  </a>  </h2>
                              </div>
                              <div class="text">
                                 <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est.  </p>
                              </div>
                              <ul class="contact-info-list">
                                 <li>
                                    <span class="icon flaticon-placeholder">  </span>  
                                    <p>PO Box 41213 Tartus   <br>North Damascus Palestine  </p>
                                 </li>
                              </ul>
                              <div class="date-info">
                                 <h3>Event Date :   <span>18 June,2019  </span>  </h3>
                              </div>
                              <div class="slider-pager">
                                 <ul class="thumb-box">
                                    <li>
                                       <a class="active" data-slide-index="0" href="#">
                                          <figure>  <img src="images/resource/sevent-1.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="1" href="#">
                                          <figure>  <img src="images/resource/sevent-2.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="2" href="#">
                                          <figure>  <img src="images/resource/sevent-3.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="3" href="#">
                                          <figure>  <img src="images/resource/sevent-4.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                           <div class="img-content clearfix">
                              <figure class="img-box">  <a href="events-details.html">  <img src="images/resource/Event-3.jpg" alt="">  </a>  </figure>
                           </div>
                        </div>
                     </div>
                  </div>
                   
                  <div class="slider-content">
                     <div class="row clearfix">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                           <div class="events-content">
                              <div class="events-info">
                                 <h2>  <a href="#">Bangladesh  </a>  </h2>
                              </div>
                              <div class="text">
                                 <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est.  </p>
                              </div>
                              <ul class="contact-info-list">
                                 <li>
                                    <span class="icon flaticon-placeholder">  </span>  
                                    <p>PO Box 12341 Qamishili   <br>West Kobani Bangladesh  </p>
                                 </li>
                              </ul>
                              <div class="date-info">
                                 <h3>Event Date :   <span>29 August,2019  </span>  </h3>
                              </div>
                              <div class="slider-pager">
                                 <ul class="thumb-box">
                                    <li>
                                       <a class="active" data-slide-index="0" href="#">
                                          <figure>  <img src="images/resource/sevent-1.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="1" href="#">
                                          <figure>  <img src="images/resource/sevent-2.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="2" href="#">
                                          <figure>  <img src="images/resource/sevent-3.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                    <li>
                                       <a data-slide-index="3" href="#">
                                          <figure>  <img src="images/resource/sevent-4.jpg" alt="">  </figure>
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                           <div class="img-content clearfix">
                              <figure class="img-box">  <a href="events-details.html">  <img src="images/resource/Event-4.jpg" alt="">  </a>  </figure>
                           </div>
                        </div>
                     </div>
                  </div>
                   -->
                   
               </div>
            </div>
         </section>