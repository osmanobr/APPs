<?php require('lib/WideImage.php'); // Chama o arquivo com a classe WideImage
$image = WideImage::load('sample.jpg'); // Carrega a imagem a ser manipulada//$image = $image->crop(10, 20, 110, 120); // Corta a imagem (Argumentos: X1, Y1, X2, Y2)// Faz um quadrado da posição [X1;Y1] até [X2;Y2]
$image = $image->crop(0, 0, 100, 150); // Corta a imagem (Argumentos: X1, Y1, X2, Y2)// Faz um quadrado da posição [X1;Y1] até [X2;Y2]
$image->saveToFile('nova_foto.jpg'); // Salva a imagem em um arquivo (novo ou não)?>