<?php
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

?>
<form  name="formcapcha" id="formcapcha" class="formcapcha" method="post">
   <p>Digite o que você vê na imagem</p>
    <img src="captcha.php?l=150&a=50&tf=20&ql=5"><br>
   <input type="text" id="palavra" name="palavra"/>
    <input type="hidden" id="ip" value="<?php echo  getUserIP(); ?>" name="ip"/>
   <input type="button" onclick="Capcha()"  value="Validar" /><br>
</form>
<label id="recapcha"></label>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
 function Capcha(){
    $.post( "validar.php", { 
        palavra: $("#palavra").val(),
        ip: $("#ip").val()
        })
          .done(function( data ) {
        if(data != 0){ alert(data);
                const recapcha = data;
                $("#recapcha").html(recapcha); 
                const form = document.querySelector("#formcapcha");    
                let formIsVisible = false;
                form.style.display = (formIsVisible ? "block" : "none");
                } 
             });
	};
</script>