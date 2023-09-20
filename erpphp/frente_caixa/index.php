<!DOCTYPE html>
<html>
  
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, 
                   initial-scale=1,
                   shrink-to-fit=no">
  
    <link rel="stylesheet" 
          href=
"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
  

  
    <style>

        div[class^="col-"] {

    box-sizing: border-box;
    border: 0px solid;
}       

    </style>
</head>
  
<body>
  
    <div class="container-fluid  px-2 py-2   bg-light">
        <div class="col-sm">
            <div class="row">
                <div class="col-4  py-2 px-md-3  bg-light">
                    <img src="../loja/imagens/modelo.png" width="100%">
                </div>
                <div class="col-8  py-2 px-md-3  bg-light text-center">
                    <H1>CAIXA LIVRE</H1>
                </div>                
            </div>     
            <div class="row" style="margin-top:0.5em"></div>
            
            <div class="row">

                <div class="col-4  py-2 px-md-3  bg-light">
                    <div class="row">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" id="inputGroup-sizing-lg">Quantidade</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg"  style="text-align:right; font-size:3.3em; padding:0px" value="0.00">
                        </div>
                    </div>    
                </div>


                <div class="col-4  py-2 px-md-3  bg-light">
                    <div class="row">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" id="inputGroup-sizing-lg">Unitário</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg"  style="text-align:right; font-size:3.3em; padding:0px" value="0.00">
                        </div>
                    </div>    
                </div>


                <div class="col-4  py-2 px-md-3  bg-light">
                    <div class="row">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" id="inputGroup-sizing-lg">Total</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg"  style="text-align:right; font-size:3.3em; padding:0px" value="0.00">
                        </div>
                    </div>    
                </div>

            </div>  
            
            <div class="row" style="margin-top:0.5em"></div>
            
            <div class="row">
                <div class="col-4  py-2 px-md-3  bg-light">
                    <div class="col-12">
                        <div class="row">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" id="inputGroup-sizing-lg">Código</span>
                                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg"  style="text-align:right; font-size:3.3em; padding:0px" value="498781654215">
                            </div>                        
                        </div>
                    </div>
                    <div class="col-12"  style="height: 300px; overflow-y: scroll;">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" id="tx_produto_1" value="ARROZ PARBOLIZADO NAMORADO" readonly class="form-control">
                                <span class="input-group-text" id="tx_produto_valor_1"      style="width: 5.5em;">22.30</span>
                            </div> 
                            <div class="input-group">
                                <input type="text" id="tx_produto_2" value="ARROZ PARBOLIZADO BUTUI" readonly class="form-control">
                                <span class="input-group-text" id="tx_produto_valor_2"      style="width: 5.5em;">19.99</span>
                            </div>                         
                            <div class="input-group">
                                <input type="text" id="tx_produto_3" value="ARROZ PARBOLIZADO BOM DE MESA" readonly class="form-control">
                                <span class="input-group-text" id="tx_produto_valor_3"      style="width: 5.5em;">20.49</span>
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="col-3  py-2 px-md-3  bg-light" style="height: 400px;">
                    <div class="row">
                        <div class="col-12 text-center">
                            <img class="img-fluid border border-warning rounded" src="../produtos/imagens/1.jfif">
                        </div>
                    </div>
                </div>    

                <div class="col-5  py-2 px-md-3  bg-light" style="height: 400px; overflow-y: scroll;">
                    <div class="row">
                        <div class="input-group">
                            <input type="text" id="tx_venda_item_1" value="FEIJAO BRASILIA" readonly class="form-control">
                            <span class="input-group-text" id="tx_venda_item_quantidade_1" style="width: 4.0em;">1.00</span>
                            <span class="input-group-text" id="tx_venda_item_unitario_1"   style="width: 4.5em;">6.46</span>
                            <span class="input-group-text" id="tx_venda_item_total_1"      style="width: 5.5em;">6.49</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group">
                            <input type="text" id="tx_venda_item_2" value="ARROZ PARBOLIZADO NAMORADO" readonly class="form-control">
                            <span class="input-group-text" id="tx_venda_item_quantidade_2" style="width: 4.0em;">2.00</span>
                            <span class="input-group-text" id="tx_venda_item_unitario_2"   style="width: 4.5em;">22.33</span>
                            <span class="input-group-text" id="tx_venda_item_total_2"      style="width: 5.5em;">44.66</span>
                        </div>
                    </div>
                </div>                             
            </div>


            <div class="row">

                <div class="col-8  py-2 px-md-3  bg-light">
                    
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-secondary btn-lg btn-block">Nova</button>
                        </div>     
                        <div class="col">
                            <button type="button" class="btn btn-secondary btn-lg btn-block">Cliente</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-secondary btn-lg btn-block">Gravar</button>
                        </div>   
                        <div class="col">
                            <button type="button" class="btn btn-secondary btn-lg btn-block">Localizar</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-secondary btn-lg btn-block">Finalizar</button>
                        </div>    
                        <div class="col">
                            <button type="button" class="btn btn-secondary btn-lg btn-block">Mesas/Fichas</button>
                        </div> 
                        
                    </div>    
                </div>


                <div class="col-4  py-2 px-md-3  bg-light">
                    <div class="row">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" id="inputGroup-sizing-lg">Soma Total</span>
                            <input type="text" class="form-control fw-bold text-danger" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg"  style="text-align:right; font-size:3.3em; padding:0px" value="51.15">
                        </div>
                    </div>    
                </div>

            </div>  

        </div>
    </div>
  
    <script src=
"https://code.jquery.com/jquery-3.2.1.slim.min.js">
  </script>
    <script src=
"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js">
  </script>
</body>
  
</html>