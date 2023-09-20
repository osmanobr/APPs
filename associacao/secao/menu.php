<nav class="main-menu navbar-expand-lg">
    <div class="navbar-header">
        <!-- Toggle Button -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse clearfix">
        <ul class="navigation clearfix">
         
            <?php
                $sql = "SELECT * FROM tb_menu  where ativo=true;";
                $result = $pdo->query( $sql );
                $rows = $result->fetchAll();

                if ($rows){
                  foreach ($rows as $m){
                    ?>
                          <li class="current dropdown">
                              <a href="<?php echo $m['link'];?>"><?php echo $m['menu'];?></a>
                              <ul>     
                                  <?php
                                      $sql = "SELECT * FROM tb_sub_menu where id_menu = '".$m['id']."' and  ativo=true ;";
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
                                </li>            
                    <?php        
                    }      
                }
            ?>            
        </ul>
    </div>
</nav>
