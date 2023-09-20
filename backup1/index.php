<?php
require_once('../vendor/autoload.php');

//slug
use Cocur\Slugify\Slugify;

$slugify = new Slugify();
echo $slugify->slugify("Hello World! OONFG?;.,"); // hello-world

//Classes internas//
use www\ClassTeste;
$teste = new ClassTeste();
?>