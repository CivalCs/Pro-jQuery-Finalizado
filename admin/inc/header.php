<?php
if(!function_exists(myAut)): header('Location: dashboard.php'); die; endif;
//if(function_exists(myAut)): myAut('1'); else: header('Location: ../dashboard.php'); die; endif;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Painel de Controle | Curso Pro jQuery - Criando Interfaces</title>
    <meta name="robots" content="noindex, nofollow" />
    <link href='http://fonts.googleapis.com/css?family=Dosis:200;400,600,800' rel='stylesheet' type='text/css'>

    <link rel="icon" type="image/png" href="img/upico.png"/>

    <link rel="stylesheet" type="text/css" href="css/painel.css" />
    <link rel="stylesheet" type="text/css" href="css/pages.css" />
    <link rel="stylesheet" type="text/css" href="../jsc/shadowbox/shadowbox.css" />


    <script type="text/javascript" src="../jsc/jquery.js"></script>
    <script type="text/javascript" src="../jsc/shadowbox/shadowbox.js"></script>
    <script type="text/javascript" src="../jsc/jmask.js"></script>

    <script type="text/javascript" src="jsc/jquery.form.js"></script>
    <script type="text/javascript" src="jsc/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="jsc/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
    <script type="text/javascript" src="jsc/plugins.js"></script>
    <script type="text/javascript" src="jsc/controller.js.php"></script>
</head>
<body>

<?php require_once('inc/modais.php');?>

<!-- HEADER -->
<div id="header">
	<div class="content">
    	<div class="logo"><img src="img/logopanel.png" alt="Painel de Controle" title="Painel de Controle" /></div><!--/logo-->
        
        <?php $acturl = explode('/',$_GET['exe']);?>

        <ul class="controle">
            <li class="li"><a href="dashboard.php?exe=sis/home" class="home <?php if(in_array('home',$acturl)) echo 'active';?>">Home</a>
                <ul class="submenu">
                    <li><a href="../" target="_blank" class="ger">Site Home</a></li>
                </ul>
            </li><!--/-->
            <li class="li"><a href="dashboard.php?exe=posts/index" class="post <?php if(in_array('posts',$acturl)) echo 'active';?>">Posts</a>
            	<ul class="submenu">
                	<li><a href="dashboard.php?exe=posts/index" class="ger <?php if(in_array('posts',$acturl)) echo 'active';?>">Gerenciar Posts</a></li>
                    <li><a href="#" class="add j_addpost <?php if(in_array('posts',$acturl)) echo 'active';?>">Adicionar Novo</a></li>
                </ul><!--/submenu-->
            </li><!--/-->
            <li class="li"><a href="dashboard.php?exe=categorias/index" class="cats <?php if(in_array('categorias',$acturl)) echo 'active';?>">Categorias</a>
            	<ul class="submenu">
                	<li><a href="dashboard.php?exe=categorias/index" class="ger <?php if(in_array('categorias',$acturl)) echo 'active';?>">Gerenciar Categorias</a></li>
                    <li><a href="#" class="add j_addcat <?php if(in_array('categorias',$acturl)) echo 'active';?>">Adicionar Nova</a></li>
                </ul><!--/submenu-->
            </li><!--/-->
            <li class="li"><a href="dashboard.php?exe=comentarios/index" class="coms <?php if(in_array('comentarios',$acturl)) echo 'active';?>">Comentários</a></li><!--/-->
            <li class="li"><a href="dashboard.php?exe=usuarios/index" class="user <?php if(in_array('usuarios',$acturl)) echo 'active';?>">Usuários</a></li><!--/-->
            <li class="li"><a href="dashboard.php?exe=sis/configuracoes" class="conf <?php if(in_array('configuracoes',$acturl)) echo 'active';?>">Configurar</a></li><!--/-->
            <li class="li"><a href="dashboard.php?exe=exit" class="exit">Sair</a></li><!--/-->
        </ul><!--/controle-->
    
    <div class="clear"></div><!-- /clear -->
    </div><!-- /content -->
</div><!--/header-->