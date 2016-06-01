<?php
    echo getSeo(SITENAME.' | Oppssss, não encontrado!','O conteúdo que você procura não foi encontrado. Este pode ter sido alterado ou removido, mas fique tranquilo e navegue por nosso menu principal!','404','../themes/blue/images/notfound.png');
?>

</head>

<!--body -->
<body>

<div class="dialog"></div><!--Mensagens ajax-->
<div class="contato j_contato"></div><!--Contato ajax-->
<div class="body"></div><!--Efeito pontinhos na body-->

<?php
	setArq('themes/blue/sidebars/pgheader');
?> 
  
<!-- BLOCO SITE GERAL HOME -->
<div id="site">
<div class="home">



<!-- BLOCO UM - h1. h2. Img Topo -->
<div class="bloco_um">

    <h1>Oppss!</h1>
    <h2>O conteúdo que você procura não foi encontrado. Este pode ter sido alterado ou removido, mas fique tranquilo e navegue por nosso menu principal!</h2>

    <div class="capa">
        <img src="<?php setHome();?>/tim.php?src=themes/blue/images/notfound.png&w=200&h=200" alt="Erro 404. Oppsss, não existe!" title="Erro 404. Oppsss, não existe!" width="200" height="200"/>
    </div><!-- /capa -->
        
</div><!-- /BLOCO UM -->
<div class="clear"></div><!-- /clear -->

<div class="bloco_erro">

   <div class="notfound">
        <h3>Oppsss, o conteúdo que você procura não foi encontrado!</h3>
        <p>Talvez você queira utilizar nosso menu de navegaçao ou nosso sistema de pesquisa. Temos muito para mostrar, não vá embora. Tente realizar uma pesquisa e temos certeza que você encontrará o que procura!</p>
        <p class="att">Atenciosamente <?php echo SITENAME;?>!</p>
   </div>';
        
    <div class="clear"></div><!-- /clear -->
    
</div><!--/bloco_tres-->



<div class="clear"></div><!--/clear-->
</div><!-- /HOME GERAL -->  
</div><!-- #SITE -->
    
  
<!-- FOOTER -->    
<div class="footer">
    <div class="content">
        <?php setArq('themes/blue/sidebars/pgmenu');?>
    </div><!-- /content -->
</div><!-- /#FOOTER -->