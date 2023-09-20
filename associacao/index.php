<?php
session_start();

setlocale(LC_TIME, 'portuguese'); 
date_default_timezone_set('America/Araguaina');

include_once('conexao.php');
include_once('funcoes.php');

//echo(limitarTexto($titulo_nome, $limite = 40));
$sql = "SELECT* FROM tb_empresa limit 1";
$result = $pdo->query( $sql );
$linhaempresa = $result->fetchAll();
if ($linhaempresa){
foreach ($linhaempresa as $le){
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title><?php echo $le['fantasia'];?></title>
      <!-- Stylesheets -->  
      <link href="css/bootstrap.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <link href="css/responsive.css" rel="stylesheet">
      <link rel="stylesheet" href="fonts/flaticon.css">
      <!-- Fav Icons -->  
      <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
      <link rel="icon" href="images/favicon.png" type="image/x-icon">
      <!-- Responsive -->  
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
      <!--[if lt IE 9]>  
    <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js">  
    </script>  
    <![endif] -->  
           <!-- [if lt IE 9]>  
    <script src="js/respond.js"> 
    </script>  
    <![endif]-->  
   </head>
   <body>
      <div class="page-wrapper">
         <!-- Preloader -->  
         <div class="preloader">  </div>
         <!-- main header -->  
         <header class="main-header hmx-35">
            <!-- header top -->  
            <div class="header-top grey">
               <div class="container clearfix">
                  <!--Top Right-->  
                  <div class="top-right pull-right">
                     <div class="social-links clearfix">
                         <a href="#">
                             <span class="fa fa-facebook-f"></span>
                         </a>
                         <a href="#">
                             <span class="fa fa-twitter"></span>
                         </a>
                         <a href="#">
                             <span class="fa fa-linkedin"></span>
                         </a>
                         <a href="#">
                             <span class="fa fa-instagram"></span>
                         </a>
                         <a href="#">
                             <span class="fa fa-pinterest-p"></span>
                         </a>
                      </div>
                  </div>
               </div>
            </div>
            <!-- header info -->  
            <div class="header-info">
               <div class="container">
                  <div class="clearfix">
                     <div class="float-left">
                        <div class="main-logo">
                            <a class="navbar-brand pr-50" href="index.php">
                                <img src="<?php echo $le['logo'];?>" class="logo" alt="">
                            </a>
                         </div>
                     </div>
                     <div class="float-right">
                        <div class="header-contact-info">
                           <ul>
                              <li>
                                 <div class="iocn-holder">
                                     <span class="flaticon-contact">  </span>
                                  </div>
                                 <div class="text-holder">
                                    <p><?php echo $le['email'];?></p>
                                 </div>
                              </li>
                              <li>
                                 <div class="iocn-holder">
                                     <span class="flaticon-placeholder">  </span>
                                  </div>
                                 <div class="text-holder">
                                    <p><?php echo $le['pais'];?>/<?php echo $le['uf'];?>/<?php echo $le['municipio'];?></p>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!--Header-Upper-->  
            <div class="header-upper black-bg-3 dark">
               <div class="container clearfix">
                  <div class="black-bg-3 upper-right clearfix">
                     <div class="nav-outer clearfix">
                        <!-- Main Menu -->  
                        <?php include_once('secao/menu.php');?>
                        <!-- Main Menu End-->  
                        <div class="outer-box float-right">
                           <div class="link-btn">
                               <a href="?modulo=sobre" class="theme-btn btn-style-one">Associe-se</a>
                            </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!--End Header Upper-->  <!--Sticky Header-->  
            <div class="sticky-header">
               <div class="container clearfix">
                  <!--Logo-->  
                  <div class="logo float-left">
                      <a href="index.php" class="img-responsive">
                        <img src="<?php echo $le['logo'];?>" alt="" title="">
                      </a>  </div>
                  <!--Right Col-->  
                  <div class="right-col float-right">
                     <!-- Main Menu -->  
                      <?php include_once('secao/menu.php');?>
                  </div>
               </div>
            </div>
            <!--End Sticky Header-->  
         </header>
          <?php
          $modulo = '';
          if ((isset($_GET['modulo']))&&($_GET['modulo']!='')){
              $modulo = $_GET['modulo'];
          }
          if ((isset($_POST['modulo']))&&($_POST['modulo']!='')){
              $modulo = $_POST['modulo'];
          }  
          if(($modulo!='')&&(file_exists('modulo/'.$modulo.'.php'))){
           include_once('modulo/'.$modulo.'.php');   
          }
             else
             { 

                 include_once('secao/slider.php'); 
                 include_once('secao/destaque.php');
                 include_once('secao/organizacao.php');     
                 include_once('secao/causas.php'); 
                 include_once('secao/contagem.php'); 
                 include_once('secao/time.php'); 
                 
                 include_once('secao/testemunho.php'); 
                 include_once('secao/evento.php'); 
                 include_once('secao/blog.php');  
                 include_once('secao/marca.php');
                 
             }
                               
          ?>
          
 
         <!-- Main Footer -->  
         <footer class="main-footer">
            <div class="container">
               <!--Widgets Section-->  
               <div class="widgets-section">
                  <div class="row clearfix">
                     <!--Big Column-->  
                     <div class="big-column col-lg-6">
                        <div class="row clearfix">
                           <!--Footer Column-->  
                           <div class="footer-column col-lg-7 col-md-7">
                              <div class="footer-widget about-widget">
                                 <div class="footer-logo">
                                    <figure>  <a href="index.php">  <img src="<?php echo $le['logo'];?>" alt="">  </a>  </figure>
                                 </div>
                                 <div class="widget-content">
                                    <div class="text"><?php echo $le['lema'];?></div>
                                 </div>
                                 <div class="loc">
                                    <ul>
                                       <li>
                                          <div class="iocn-holder">
                                              <span class="flaticon-contact">  </span>
                                           </div>
                                          <div class="text-holder">
                                             <p><?php echo $le['email'];?></p>
                                          </div>
                                       </li>
                                       <li>
                                          <div class="iocn-holder">  <span class="flaticon-placeholder">  </span>  </div>
                                          <div class="text-holder">
                                             <p><?php echo $le['pais'];?>/<?php echo $le['uf'];?>/<?php echo $le['municipio'];?></p>
                                          </div>
                                       </li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <!--Footer Column-->  
                           <div class="footer-column col-lg-5 col-md-5">
                              <div class="footer-widget services-widget">
                                 <h2 class="widget-title">Menu</h2>
                                 <div class="widget-content">
                                    <ul class="list">
                                         <?php
                                              $sql = "SELECT * FROM tb_sub_menu  where sub_menu <> 'INICIO' and sub_menu <>'SAIR' and ativo = true order by sub_menu limit 6;";
                                              $result = $pdo->query( $sql );
                                              $rows = $result->fetchAll();
                                                if ($rows){
                                                      foreach ($rows as $sm){
                                                ?>
                                                    <li><a href="<?php echo $sm['link'];?>"><?php echo $sm['sub_menu'];?></a></li>
                                                <?php        
                                                }      
                                            }
                                         ?> 
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!--Big Column-->  
                     <div class="big-column col-lg-6">
                        <div class="row clearfix">
                           <!--Footer Column-->  
                           <div class="footer-column col-lg-5 col-md-5">
                              <div class="footer-widget services-widget">
                                 <h2 class="widget-title">Links</h2>
                                 <div class="widget-content">
                                    <ul class="list">
                                        
                                  <?php
                                      $sql = "SELECT * FROM tb_sub_menu where id_menu = '".$m['id']."' and  ativo=true order by rand() limit 6 ;";
                                      $result = $pdo->query( $sql );
                                      $rows = $result->fetchAll();
                                        if ($rows){
                                              foreach ($rows as $sm){
                                        ?>
                                            <li><a href="<?php echo $sm['link'];?>"><?php echo $sm['sub_menu'];?></a></li>
                                        <?php        
                                        }      
                                    }
                                 ?>                    
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <!--Footer Column--> 
                           <div class="footer-column col-lg-7 col-md-7">
                              <div class="footer-widget contact-widget">
                                 <h2 class="widget-title">Contato</h2>
                                 <form action="Eviaremail.php" class="contact-form" id="footer-cf">
                                     <input type="text" name="nome"  placeholder="Nome completo">
                                     <input type="text" name="celular"  placeholder="celular">
                                     <input type="text" name="email" placeholder="Email" >
                                     <textarea name="message" placeholder="sua mensagem">
                                     </textarea>
                                     <button type="submit">Enviar</button>
                                  </form>
                                 <div class="link-btn">  <a href="#" class="theme-btn btn-style-one">Enviar Mensagem</a>  </div>
                              </div>
                           </div> 
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!--Footer Bottom-->  
            <div class="footer-bottom">
               <div class="container">
                  <ul class="social-icon-three">
                     <li>  <a href="#">  <i class="fa fa-facebook">  </i>  </a>  </li>
                     <li>  <a href="#">  <i class="fa fa-twitter">  </i>  </a>  </li>
                     <li>  <a href="#">  <i class="fa fa-vimeo">  </i>  </a>  </li>
                     <li>  <a href="#">  <i class="fa fa-linkedin">  </i>  </a>  </li>
                  </ul>
                  <div class="copyright-text">
                     <p><a href="https://www.interdadosinfo.com.br/" title="interdadosinfo">Interdadosinfo  </a> &copy;  2021 Todos os direitos reservados </p>
                  </div>
               </div>
            </div>
         </footer>
         <!-- End Main Footer -->  
      </div>
      <!--End pagewrapper-->  
       <!-- Scroll Top Button -->  
       <button class="scroll-top scroll-to-target" data-target="html">
           <span class="fa fa-angle-up">
           </span>
       </button>
       <!-- jequery plugins -->
       <script src="js/jquery.js"></script>
       <script src="js/popover.js"></script>
       <script src="js/bootstrap.min.js"></script>
       <script src="js/wow.js"></script>
       <script src="js/owl.js"></script>
       <script src="js/validate.js"></script>
       <script src="js/mixitup.js"></script>
       <script src="js/isotope.js"></script>
       <script src="js/appear.js"></script>
       <script src="js/jquery.fancybox.js"></script>
       <script src="js/bxslider.js"></script>
       <!-- Custom script -->
       <script src="js/script.js"></script>
       <!-- map script -->
       <script src="*http://ditu.google.cn/maps/api/js?key=AIzaSyBZQiiFTOGpm2qHVZmZY1s-aEnmHDhqKgk"></script>
       <script src="js/gmaps.js"></script>
       <script id="map-script" src="js/map-script.js"></script>  
   </body>
</html>
<?php }}?>