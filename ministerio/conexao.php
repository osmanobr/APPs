<?php
$hostname = 'localhost';
$username = 'adm';
$password = 'adm';
$database = 'ministerio';
 
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}
catch(PDOException $e){
    echo $e->getMessage();
}					
?>