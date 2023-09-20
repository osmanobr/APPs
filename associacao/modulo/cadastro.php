         <!-- Contact form -->
         <section class="contact-form-section grey-bg">
            <div class="container">
               <div class="sec-title centered">
                  <h1>Cadastre-<span>se</span></h1>
                  <span class="flaticon-book"></span>
                <h4 id="mensagem"></h4>    
               </div>
                
 
<div class="row">
    <div class="default-form-area">                
         <form  name="formcapcha" id="formcapcha" class="formcapcha" method="post">
             <h3>Nós também não gostamos de spans</h3>
           <p>Digite o que você vê na imagem</p>
            <img src="captcha.php?l=150&a=50&tf=20&ql=5"><br>
           <input type="text" id="palavra" name="palavra"/>
           <input type="button" onclick="Capcha()"  value="Validar" /><br>
        </form>
        <label id="recapcha"></label>   
    </div>
</div>
                
               <div class="row">
                  <div class="default-form-area">
                     <form id="frmcadastro" name="frmcadastro" class="contact-form2222" action="index.php" method="post">
                        <div class="row clearfix">
                            
                           <div class="col-md-6 column"><label for="nome">Nome</label>
                              <div class="form-group"><input type="text" name="nome" id="nome" class="form-control" value="" placeholder="Nome" required=""></div>
                           </div>
                            
                           <div class="col-md-6 column"><label for="email">Email</label>
                              <div class="form-group"><input type="email" name="email" id="email" class="form-control required email" value="" placeholder="Email" required=""></div>
                           </div>
                            
                           <div class="col-md-6 column"><label for="celular">Celular</label>
                              <div class="form-group"><input type="text" name="celular" id="celular" class="form-control required" value="" placeholder="Celular" required=""></div>
                           </div>  
                            
                           <div class="col-md-6 column"><label for="cpf">CPF</label>
                              <div class="form-group"><input type="text" name="cpf" id="cpf" class="form-control" value="" placeholder="CPF"></div>
                           </div>
                            
                           <div class="col-md-6 column"><label for="nome">Nome</label>
                              <div class="form-group"><input type="text" name="rg" id="rg" class="form-control" value="" placeholder="RG" required=""></div>
                           </div>
                            
                            <div class="col-md-6 column"><label for="data_nascimento">Data de nascimento</label>
                              <div class="form-group"><input type="date" name="data_nascimento" id="data_nascimento" class="form-control" value="" placeholder="Data de Nascimento" required=""></div>
                           </div>                            
                            
                           <div class="col-md-6 column"><label for="cidade_uf_origem">Cidade e estado onde nasceu</label>
                              <div class="form-group"><input type="text" id="cidade_uf_origem" name="cidade_uf_origem" class="form-control" value="" placeholder="Cidade/UF de Origem" required=""></div>
                           </div>                            
                            
                           <div class="col-md-6 column"><label for="profissao">Profissão</label>
                              <div class="form-group"><input type="text" name="profissao" id="profissao" class="form-control" value="" placeholder="Profissão" required=""></div>
                           </div>                              
 
                           <div class="col-md-6 column"><label for="interesse">Interesse</label>
                              <div class="form-group">
                                  <select name="interesse" class="form-control"  required="" id="interesse">
                                        <option value="Receber benefícios">Receber Beneficios</option>
                                        <option value="Ser membro efetivo">Ser membro efetivo</option>
                                        <option selected value="Ser um mantenedor">Ser um mantenedor</option>
                                        <option value="Ser honorário">Ser honorário</option>
                                  </select>
                               </div>
                           </div>   
                            
                            
                            <div class="col-md-6 column">
                                 <label for="endereco">Endereco</label>
                                 <div class="form-group"><input type="text" name="endereco" id="endereco" class="form-control" value="" placeholder="Endereco" required=""></div>
                            </div> 

                            <div class="col-md-6 column">
                                <label for="bairro">Bairro</label>
                                <div class="form-group"><input type="text" name="bairro" id="bairro" class="form-control" value="" placeholder="Bairro" required=""></div>
                            </div> 

                            <div class="col-md-6 column">
                                <label for="cidade">Cidade</label>
                                <div class="form-group"><input type="text" name="cidade" id="cidade" class="form-control" value="" placeholder="Cidade" required=""></div>
                            </div> 

                            <div class="col-md-6 column">
                                <label for="uf">Estado</label>
                                <div class="form-group">
                                 <select name="uf" class="form-control" id="uf" required="">
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>   

                                </div>
                            </div> 

                            <div class="col-md-6 column">
                                <label for="tipo_filiado">Tipo de Filiação</label>
                                <div class="form-group2">
                                    <select  name="tipo_filiado" class="form-control" id="tipo_filiado" required="">
                                        <option value="Beneficiado">Beneficiado</option>
                                        <option value="Efetivo">Efetivo</option>
                                        <option value="Mantenedor">Mantenedo</option>
                                        <option value="Honorário">Honorário</option>
                                    </select>
                                </div>
                            </div> 
                            
                           <div class="col-md-12 column"><label for="sobre_mim">Sobre minha pessoa</label>
                              <div class="form-group"><textarea id="sobre_mim" name="sobre_mim" class="form-control textarea required" placeholder="Sobre minha pessoa"></textarea></div>
                           </div>
                            
                        </div>
                         <input type="hidden" name="modulo" id="modulo" value="cadastro">
                        <div class="contact-section-btn text-center">
                           <div class="form-group style-two">
                               <input id="ip" name="ip" class="form-control" type="hidden" value="">
                               <button class="theme-btn btn-style-one" type="button" onclick="Enviar()" data-loading-text="Aguarde...">Enviar</button>
                            </div>
                        </div>
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
                
    <img src="http://maps.googleapis.com/maps/api/staticmap?center=48.33521044478572,10.18168992423273&zoom=11&size=250x250">            
                
                
            </div>
         </section>

<script>
function Enviar() {
      var    xmlhttp=new XMLHttpRequest();
    alert($('#uf :selected').text());
    const    nome=document.getElementById("nome").value;
    const    email=document.getElementById("email").value;
    const    celular=document.getElementById("celular").value;
    const    cpf=document.getElementById("cpf").value;
    const    rg=document.getElementById("rg").value;
    const    data_nascimento=document.getElementById("data_nascimento").value;
    const    cidade_uf_origem=document.getElementById("cidade_uf_origem").value;
    const    profissao=document.getElementById("profissao").value;
    var    interesse=$('#interesse :selected').text();
    const    endereco=document.getElementById("endereco").value;
    const    bairro=document.getElementById("bairro").value;
    const    cidade=document.getElementById("cidade").value;
    var    uf=$('#uf :selected').text();
    var    tipo_filiado=$('#tipo_filiado :selected').text();
    const    sobre_mim=document.getElementById("sobre_mim").value;

    

  xmlhttp.onreadystatechange=function() {

      document.getElementById("mensagem").innerHTML=this.responseText;
      var form   = document.getElementById("frmcadastro");
      document.getElementById("nome").value=this.responseText;
      
     // form.reset();
  
  }
   // xmlhttp.open("GET","modulo/cadastro_enviar.php?nome=aq",true);
    xmlhttp.open("GET","modulo/cadastro_enviar.php?nome="+nome+
                 "&email="+email+
                 "&celular="+celular+
                 "&cpf="+cpf+
                 "&rg"+rg+
                 "&data_nascimento"+data_nascimento+
                 "&cidade_uf_origem"+cidade_uf_origem+
                 "&profissao"+profissao+
                 "&interesse"+interesse+
                 "&endereco"+endereco+
                 "&bairro"+bairro+
                 "&cidade"+cidade+
                 "&uf"+uf+
                 "&tipo_filiado"+tipo_filiado+
                 "&sobre_mim"+sobre_mim,
                 true);
  xmlhttp.send();
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
 function Capcha(){
    $.post( "capcha/validar.php", { 
        palavra: $("#palavra").val()
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
