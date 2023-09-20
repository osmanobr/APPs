<?php
$pdo = new PDO("pgsql:host=192.168.10.210;port=5432;dbname=01086;user=ViaERP;password=Via7216");
          $result = $pdo->query("select * from produtos order by id");
          $res = $result->fetchAll();
          foreach($res as $r){  
            echo $r['id']." - ". $r['nome'].'<br>'; 
        }
?>