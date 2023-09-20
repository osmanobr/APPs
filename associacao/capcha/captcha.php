<?php
   session_start();
   header("Content-type: image/jpeg");
    function captcha($largura,$altura,$tamanho_fonte,$quantidade_letras){
        $imagem = imagecreate($largura,$altura);
        $fonte = "arial.ttf";
        $preto  = imagecolorallocate($imagem,20,144,255);
        $branco = imagecolorallocate($imagem,255,255,255);
        $quantidade_letras = 5;    
        $palavra = substr(str_shuffle("ABCDEFGHIJKLMNPQRSTUVWXYZ123456789"),0,($quantidade_letras));        
        $_SESSION["palavra"] = $palavra;
        for($i = 1; $i <= $quantidade_letras; $i++){
            imagettftext($imagem,$tamanho_fonte,rand(-25,25),($tamanho_fonte*$i),
            ($tamanho_fonte + 10),$branco,$fonte,substr($palavra,($i-1),1));
        }
        imagejpeg($imagem); 
        imagedestroy($imagem); 
    }
    $largura = $_GET["l"]; 
    $altura = $_GET["a"]; 
    $tamanho_fonte = $_GET["tf"]; 
    $quantidade_letras = $_GET["ql"]; 
    captcha($largura,$altura,$tamanho_fonte,$quantidade_letras);
?>