<?php
   session_start();
    if ($_POST["palavra"] == $_SESSION["palavra"]){
        echo session_id();
    }else{
        echo '0';
    }
//session_regenerate_id();
?>