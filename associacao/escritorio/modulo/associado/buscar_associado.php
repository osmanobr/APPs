
<?php //include_once('../../conexao.php'); 
$id = 1;
exit;
if(isset($_POST['q']) && $_POST['q']!=''){ 
    (int)$id = $_POST['q'];
    $sql = "SELECT * from tb_filiado where id= $id;";
    $result = $pdo->query( $sql );
    $rows = $result->fetchAll();
          if ($rows){   
                  foreach ($rows as $r){
                    $id = $r['id'];
                   }
            }
}
echo $id;
    ?>  