<?php
    $urlCat = mysql_real_escape_string($url[1]);
    $readCategoria = read('categorias',"WHERE url = '$urlCat'");
    if(!$readCategoria):
        header('Location: '.BASE.'/404');
    else:
        foreach($readCategoria as $cat);
        $_SESSION['useracess']['catid'] = $cat['catid'];
    endif;

echo getSeo(SITENAME.' | '.$cat['categoria'], $cat['descricao'],'categoria/'.$cat['url'],$cat['capa']);
?>

</head>

<!--body -->
<body>

<div class="dialog"></div><!--Mensagens ajax-->
<div class="contato j_contato"></div><!--Contato ajax-->
<div class="body"></div><!--Efeito pontinhos na body-->

<?php	setArq('themes/'.THEME.'/sidebars/pgheader');?>
  
<!-- BLOCO SITE GERAL HOME -->
<div id="site">
<div class="home">

<!-- BLOCO UM - h1. h2. Img Topo -->
<div class="bloco_um">

    <h1><?php echo $cat['categoria'];?></h1>
    <h2><?php echo $cat['descricao'];?></h2>

    <div class="capa">
        <?php
            $default = 'themes/blue/images/blue.jpg';
            $capa = 'uploads/'.$cat['capa'];
            $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);
        ?>
       	<img src="<?php setHome();?>/tim.php?src=<?php echo $capa;?>&w=200&h=200" alt="Ver mais em <?php echo $cat['categoria'];?>" title="Ver mais em <?php echo $cat['categoria'];?>" />
    </div><!-- /capa -->
        
</div><!-- /BLOCO UM -->
<div class="clear"></div><!-- /clear -->

<div class="categorias j_catpag">
    <?php
        $page = mysql_real_escape_string($url[2]);
        $page = preg_replace('/[^0-9]*/','',$page);
        $page = ($page ? $page : 1);

        $maximo = 12;
        $inicio = ($page * $maximo) - $maximo;


        $readArt = read('posts',"WHERE status = '1' AND categoria = '$cat[id]' ORDER BY cadastro DESC LIMIT $inicio,$maximo");
        if(!$readArt):
            if($page > 1 ):
                $pagenum = $page - 1;
                header('Location: '.BASE.'/categoria/'.$cat['url'].'/'.$pagenum);
            else:
                echo '<div class="notfound">';
                    echo '<h3>Desculpe, ainda não existem artigos cadastrados em '.$cat['categoria'].'</h3>';
                    echo '<p>Não tem problema, em breve teremos muito conteúdo de primeira aqui. Volte logo. Mas não vá embora ainda,tente usar nosso menu ou pesquisa e temos certeza que você vai encontrar conteúdo de primeira.</p>';
                    echo '<p class="att">Atenciosamente '.SITENAME.'!</p>';
                echo '</div>';
            endif;
        else:
            echo '<ul>';
                foreach($readArt as $art):
                    $i++;

                    $artdefault = 'themes/blue/images/blue.jpg';
                    $artcapa = 'uploads/'.$art['capa'];
                    $artcapa = (file_exists($artcapa) && !is_dir($artcapa) ? $artcapa : $artdefault);

                    echo '<li'; if($i%4==0) echo ' style="float:right; margin-right:0"'; echo '>';
                        echo '<img alt="'.$art['titulo'].'" title="'.$art['titulo'].'" src="'.BASE.'/tim.php?src='.$artcapa.'&w=220&h=200" width="220" heigth="200"/>';
                        echo '<div class="licontent">';
                            echo '<a title="'.$art['titulo'].'" href="'.BASE.'/ver/'.$art['url'].'">'.lmWord($art['titulo'],50).'</a>';
                        echo '</div>';
                    echo '</li>';
                endforeach;
            echo '</ul>';

            echo '<div class="clear"></div>';

            echo '<div class="paginator">';
                $link = BASE.'/categoria/'.$cat['url'].'/';
                readPaginator('posts',"WHERE status = '1' AND categoria = '$cat[id]' ORDER BY cadastro DESC",$maximo,$link,$page);
            echo '</div>';
        endif;
    ?>
</div><!--/categorias-->

<div class="clear"></div><!--/clear-->
</div><!-- /HOME GERAL -->  
</div><!-- #SITE -->   
  
<!-- FOOTER -->    
<div class="footer">
    <div class="content">
        <?php setArq('themes/'.THEME.'/sidebars/pgmenu');?>
    </div><!-- /content -->
</div><!-- /#FOOTER -->