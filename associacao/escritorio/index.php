<?php
 include_once('conexao.php'); 
 include_once('Func.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>fantasia</title>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="dist/css/adminlte.min.css">
<!--
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">



  <link rel="stylesheet" href="plugins/codemirror/codemirror.css">
  <link rel="stylesheet" href="plugins/codemirror/theme/monokai.css">

  <link rel="stylesheet" href="plugins/simplemde/simplemde.min.css">
  -->
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-closed sidebar-collapse">
    
    
<script src="plugins/jquery/jquery.min.js"></script>

<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="dist/js/adminlte.min.js"></script>
<!-- 
<script src="plugins/summernote/summernote-bs4.min.js"></script>

<script src="plugins/codemirror/codemirror.js"></script>
<script src="plugins/codemirror/mode/css/css.js"></script>
<script src="plugins/codemirror/mode/xml/xml.js"></script>
<script src="plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>   
    -->
    
    
    
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li><!--
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contato</a>
      </li> -->
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Buscar">
        <div class="input-group-append">
          <button class="btn btn-navbar" name="btnbuscar" id="btnbuscar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

<!--
      Mensagem
      Notificação 
-->
        

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../images/logo.png" alt="Administração" class="brand-image img-circle1 elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Escritório</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
<!--
        <div class="image">
          <img src="dist/img/avatar5.png" class="img-circle elevation-2" alt="Usuário Logado">
        </div>
-->
        <div class="info">
          <a href="#" class="d-block">Bem vindo</a>
        </div>
      </div>


      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Buscar">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>



      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                VENDA
                <i class="right fas fa-angle-left"></i>
                <!-- <span class="badge badge-info right">6</span> --> 
              </p>
            </a>
            <ul class="nav nav-treeview">
                
              <li class="nav-item">
                <a href="?modulo=modulo/departamento/listar_departamento" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>DEPARTAMENTOS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?modulo=modulo/setor/listar_setor" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>SETORES</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?modulo=modulo/secao/listar_secao" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>SEÇÕES</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?modulo=modulo/lote/listar_lote" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>LOTES</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?modulo=modulo/produto/listar_produto" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>PRODUTOS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?modulo=modulo/estoque/listar_estoque" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>PRODUTOS ESTOQUE</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?modulo=modulo/pedido/listar_pedido" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>PEDIDOS</p>
                </a>
              </li>                
                
              <li class="nav-item">
                <a href="?modulo=modulo/nfe/listar_nfe" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>NFE</p>
                </a>
              </li>
                                
            </ul>
          </li>
            
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                FINANCEIRO
                <i class="right fas fa-angle-left"></i>
                <!-- <span class="badge badge-info right">6</span> --> 
              </p>
            </a>
            <ul class="nav nav-treeview">
                
              <li class="nav-item">
                <a href="?modulo=modulo/doacao/listar_doacao" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>DOAÇÕES</p>
                </a>
              </li>             
                
              <li class="nav-item">
                <a href="?modulo=modulo/duplicata/listar_duplicata" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>DUPLICATAS</p>
                </a>
              </li>
                
              <li class="nav-item">
                <a href="?modulo=modulo/banco/listar_banco" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>BANCOS</p>
                </a>
              </li>           
                               
            </ul>
          </li>             
            




            
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                ASSOCIADOS
                <i class="right fas fa-angle-left"></i>
                <!-- <span class="badge badge-info right">6</span> --> 
              </p>
            </a>
            <ul class="nav nav-treeview">
                
              <li class="nav-item">
                <a href="?modulo=modulo/filiado/listar_filiado" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>ASSOCIADOS</p>
                </a>
              </li>             
                
              <li class="nav-item">
                <a href="?modulo=modulo/fornecedor/listar_fornecedor" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>FORNECEDORES</p>
                </a>
              </li>
           
                               
            </ul>
          </li> 
            
 
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                SITE
                <i class="right fas fa-angle-left"></i>
                <span class="badge badge-info right"></span> 
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                <a href="?modulo=modulo/empresa/listar_empresa"class="nav-link">EMPRESA</a>
                </li>                 
                <li class="nav-item">
                <a href="?modulo=modulo/pagina/listar_pagina"class="nav-link">PAGINAS</a>
                </li>
                <li class="nav-item">
                <a href="?modulo=modulo/categoria/listar_categoria"class="nav-link">CATEGORIAS</a>
                </li>               
                 <li class="nav-item">
                <a href="?modulo=modulo/marca/listar_marca"class="nav-link">MARCAS</a>
                </li>
                 <li class="nav-item">
                <a href="?modulo=modulo/blog/listar_blog"class="nav-link">BLOG</a>
                </li>   
                
                 <li class="nav-item">
                <a href="?modulo=modulo/evento/listar_evento"class="nav-link">EVENTOS</a>
                </li>
                 <li class="nav-item">
                <a href="?modulo=modulo/causa/listar_causa"class="nav-link">CAUSAS</a>
                </li>
                 <li class="nav-item">
                <a href="?modulo=modulo/projeto/listar_projeto"class="nav-link">PROJETOS</a>
                </li>
                 <li class="nav-item">
                <a href="?modulo=modulo/contador/listar_contador"class="nav-link">CONTADORES</a>
                </li>
                 <li class="nav-item">
                <a href="?modulo=modulo/testemunho/listar_testemunho"class="nav-link">TESTEMUNHOS</a>
                </li>
                 <li class="nav-item">
                <a href="?modulo=modulo/contato/listar_contato"class="nav-link">CONTATO</a>
                </li>
                 <li class="nav-item">
                <a href="?modulo=modulo/comentario/listar_comentario"class="nav-link">COMENTÁRIOS</a>
                </li>            
                 <li class="nav-item">
                <a href="?modulo=modulo/faq/listar_faq"class="nav-link">FAQ</a>
                </li> 
                 <li class="nav-item">
                <a href="?modulo=modulo/log/listar_log"class="nav-link">LOG</a>
                </li> 
              </ul>
            </li>
           <li class="nav-item">
                <a href="?modulo=modulo/logout/logout"class="nav-link">SAIR</a>
           </li>      
            
            
            
            
        </ul> 
      </nav>
        
        
        
        

    </div>
  </aside>

                 <?php 
            
                if(isset($_GET['modulo']) && $_GET['modulo']!=''){$modulo = $_GET['modulo'];}
                else {$modulo = 'Início';}?>
                
                     <?php
                       if(file_exists($modulo.'.php'))
                       {include_once($modulo.'.php');}
                       else
                       {
                        echo 'O módulo buscado não existe...';
                       }
                    ?> 
    
    
<!-- modultos-->
    

    
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Versão</b> 2.2.0 Estável
    </div>
    <strong>Copyright &copy; <?php echo date('d/m/Y');?> <?php echo 'ATIDS';?> | By <a href="https://www.interdadosinfo.com.br">InterdadosInfo</a> </strong> Todos os direitos reservados.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->





</body>
</html>
