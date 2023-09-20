
         <section class="contact-info-page">
            <div class="container">
               <div class="row">
                  <div class="col-lg-4 col-sm-6">
                     <div class="main-touch">
                        <div class="inner-contact">
                           <div class="icon"><i class="flaticon-email"></i></div>
                           <div class="form-touch">
                              <p>atendimento@astids.com.br</p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-sm-6">
                     <div class="main-touch">
                        <div class="inner-contact">
                           <div class="icon"><i class="flaticon-smartphone"></i></div>
                           <div class="form-touch">
                              <p>(+55) 63 9 9289 3682</p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-sm-6">
                     <div class="main-touch">
                        <div class="inner-contact">
                           <div class="icon"><i class="flaticon-placeholder"></i></div>
                           <div class="form-touch">
                              <p>Brasil Tocantins - Palmas</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- Contact form -->
         <section class="contact-form-section grey-bg">
            <div class="container">
               <div class="sec-title centered">
                  <h1>Contate-<span>nos</span></h1>
                  <span class="flaticon-book"></span>
                  <h4 id="mensagem"></h4> 
               </div>
               <div class="row">
                  <div class="default-form-area">
                     <form id="frmcontato" name="frmcontato" class="contact-form" method="post">
                        <div class="row clearfix">
                           <div class="col-md-6 column">
                              <div class="form-group"><input type="text" name="nome" id="nome" class="form-control" value="" placeholder="Nome" required=""></div>
                           </div>
                           <div class="col-md-6 column">
                              <div class="form-group"><input type="email" name="email" id="email" class="form-control required email" value="" placeholder="Email" required=""></div>
                           </div>
                           <div class="col-md-6 column">
                              <div class="form-group"><input type="text" name="celular" id="celular" class="form-control" value="" placeholder="Celular"></div>
                           </div>
                           <div class="col-md-6 column">
                              <div class="form-group"><input type="text" name="assunto" id="assunto" class="form-control" value="" placeholder="Assunto" required=""></div>
                           </div>
                           <div class="col-md-12 column">
                              <div class="form-group"><textarea name="texto" id="texto" class="form-control textarea required" placeholder="Sua mensagem"></textarea></div>
                           </div>
                        </div>
                        <div class="contact-section-btn text-center">
                           <div class="form-group style-two"><input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden" value=""><button class="theme-btn btn-style-one" type="button" onclick="Enviar()" data-loading-text="Aguarde...">Enviar Agora</button></div>
                        </div>
                         <input type="hidden" name="ip" id="ip" value="<?php echo $_SERVER["REMOTE_ADDR"]; ?>">
                     </form>
                  </div>
               </div>
            </div>
         </section>
         <!-- map section -->
         <section class="map-section">
            <div class="home-google-map">
               <div class="google-map" id="contact-google-map" data-map-lat="55.954041" data-map-lng="-3.189173" data-icon-path="images/icons/map-marker.png" data-map-title="Palmas" data-map-zoom="11" >
                </div>
            </div>
         </section>
<script>
function Enviar() {

      var    xmlhttp=new XMLHttpRequest();
    const    nome=document.getElementById("nome").value;
    const    email=document.getElementById("email").value;
    const    celular=document.getElementById("celular").value;
    const    assunto=document.getElementById("assunto").value;
    const    texto=document.getElementById("texto").value;
    const    ip=document.getElementById("ip").value; 

  xmlhttp.onreadystatechange=function() {

      document.getElementById("mensagem").innerHTML=this.responseText;
      var form   = document.getElementById("frmcontato");
     // document.getElementById("nome").value=this.responseText;
      
      form.reset();
  
  }
  //  xmlhttp.open("GET","modulo/contato_enviar.php?nome=aq",true);
    xmlhttp.open("GET","modulo/contato_enviar.php?nome="+nome+"&email="+email+"&celular="+celular+"&assunto="+assunto+"&texto="+texto+"&ip="+ip,true);
  xmlhttp.send();
}
</script>