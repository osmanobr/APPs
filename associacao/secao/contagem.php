<?php
$sql = "SELECT * FROM `tb_contador` order by id desc limit 4";
$result = $pdo->query( $sql );
$rowscount = $result->fetchAll();
if ($rowscount){
    ?>
<div class="fact-section" style="background-image:url(<?php echo $le['banner']?>);">
            <div class="counter-column col-lg-12">
               <div class="fact-counter">
                  <div class="container">
                     <div class="row">
                        <!--Column-->  
<?php
foreach ($rowscount as $rcount){
?>        

<div class="column counter-column col-lg-3 col-sm-6 col-xs-12">
   <div class="inner">
      <div class="fact-icon">  <i class="flaticon-book">  </i>  </div>
      <div class="count-outer count-box">  <span class="count-text style-2" data-speed="5000" data-stop="<?php echo $rcount['valor'];?>  "></span>  </div>
      <h4 class="counter-title style-2"><?php echo $rcount['titulo'];?></h4>
   </div>
</div>

<?php 
}

?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
<?php } ?>