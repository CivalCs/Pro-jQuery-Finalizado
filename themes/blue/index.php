<script type="text/javascript" src="<?php setHome();?>/themes/<?php echo THEME;?>/js/jshome.js"></script>

<?php echo getSeo(SITENAME.' | Home',SITEDESC); ?>

</head>

<!--body -->
<body>

<div class="dialog"></div><!--Mensagens ajax-->
<div class="contato j_contato"></div><!--Contato ajax-->
<div class="body"></div><!--Efeito pontinhos na body-->

<?php
	setArq('themes/'.THEME.'/sidebars/slider');
	setArq('themes/'.THEME.'/sidebars/header');
?> 

<!-- BLOCO SITE GERAL HOME -->
<div id="site">
<div class="home">



<!-- BLOCO UM - h1. h2. Img Topo -->
<div class="bloco_um">
    <h1><?php echo SITENAME;?></h1>
    <h2><?php echo SITEDESC;?></h2>

    <div class="capa">
        <?php
            $readCapaSite = read('posts',"WHERE status = '1' ORDER BY views DESC LIMIT 1");
            if($readCapaSite):
                foreach($readCapaSite as $hcs);

                $default = 'themes/blue/images/blue.jpg';
                $capa = 'uploads/'.$hcs['capa'];
                $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

                echo '<img alt="'.$hcs['titulo'].'" title="'.$hcs['titulo'].'" src="'.BASE.'/tim.php?src='.$capa.'&w=200&h=200" width="200" height="200" />';
                echo '<a href="'.BASE.'/ver/'.$hcs['url'].'" title="'.$hcs['titulo'].'">';
                     echo lmWord($hcs['titulo'],34);
                     echo '<p>'.lmWord($hcs['conteudo'],60).'</p>';
                echo '</a>';
            endif;
        ?>
    </div><!-- /capa -->
</div><!-- /BLOCO UM -->


<!-- BLOCO DOIS - Destaques, de olho, feed x 4 -->
<div class="bloco_dois">
    
    <ul class="navbldois">
        <li class="destaq j_destaq">DESTAQUES!</li>
        <li class="deolho j_deolho">DE OLHO!</li>
    </ul>       

    <div class="content"> 
                    
        <ul class="arts">
        <?php
            $readDeOlho = read('posts',"WHERE status = '1' ORDER BY cadastro DESC LIMIT 3,4");
            if($readDeOlho):
                foreach($readDeOlho as $deOlho):

                $default = 'themes/blue/images/blue.jpg';
                $capa = 'uploads/'.$deOlho['capa'];
                $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

                    $i++;
                    echo '<li'; if($i%2==0) echo ' style="float:right;"'; echo '>';
                        echo '<img alt="'.$deOlho['title'].'" title="'.$deOlho['title'].'" src="'.BASE.'/tim.php?src='.$capa.'&w=100&h=100" width="100" height="100"/>';
                        echo '<a title="'.$deOlho['titulo'].'" href="'.BASE.'/ver/'.$deOlho['url'].'">'.lmWord($deOlho['titulo'],42).'</a>';
                        echo '<p>'.lmWord($deOlho['conteudo'],'70').'</p>';
                    echo '</li>';
                endforeach;
            endif;
        ?>
        </ul><!-- /arts -->  
                         
    </div><!-- /content -->                    
</div><!-- /BLOCO DOIS -->


<!-- BLOCO TRES - vídeos -->
<div id="videos" class="bloco_tres">
    <div class="content">
        <h2>Vídeos</h2>
        
        <ul class="videos">
        <?php
            $readCatVideos = read('categorias',"WHERE url = 'videos'");
            if($readCatVideos) foreach($readCatVideos as $catVideos);
            $readVideos = read('posts',"WHERE status = '1' AND categoria = '$catVideos[id]' ORDER BY cadastro DESC LIMIT 3");
            if($readVideos):
                foreach($readVideos as $hv):

                $default = 'themes/blue/images/blue.jpg';
                $capa = 'uploads/'.$hv['capa'];
                $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

                    $v++;
                    echo '<li'; if($v%3==0) echo ' style="float:right; margin-right:0"'; echo '>';
                         echo '<img alt="'.$hv['titulo'].'" title="'.$hv['titulo'].'" src="'.BASE.'/tim.php?src='.$capa.'&w=300&h=150" width="300" height="150"/>';
                         echo '<div class="licontent">';
                             echo '<a title="'.$hv['titulo'].'" href="'.BASE.'/ver/'.$hv['url'].'">'.lmWord($hv['titulo'],40).'</a>';
                             echo '<p>'.lmWord($hv['conteudo'],136).'</p>';
                         echo '</div>';
                    echo '</li>';
                endforeach;
            endif;

            echo '<li class="readmore"><a href="'.BASE.'/categoria/'.$catVideos['url'].'" title="Ver '.$catVideos['categoria'].' na '.SITENAME.'">VEJA +</a></li>';
        ?>
        </ul>
    </div><!-- /content -->
</div><!-- /BLOCO TRESs -->


<!-- BLOCO QUATRO - artigos -->
<div id="artigos" class="bloco_quatro">
    <div class="content">
        <h2>Artigos</h2>
        

            <?php
                echo '<div class="destaq">';
                    $readCatArt = read('categorias',"WHERE url = 'artigos'");
                    if($readCatArt) foreach($readCatArt as $catArt);
                    $readArtD = read('posts',"WHERE status = '1' AND categoria = '$catArt[id]' ORDER BY cadastro DESC LIMIT 1");
                    if($readArtD) foreach($readArtD as $artd);

                    $default = 'themes/blue/images/blue.jpg';
                    $capa = 'uploads/'.$artd['capa'];
                    $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

                    echo '<img title="'.$artd['titulo'].'" alt="'.$artd['titulo'].'" src="'.BASE.'/tim.php?src=/'.$capa.'&w=300&h=300" width="300" heigth="300" />';
                    echo '<a title="'.$artd['titulo'].'" href="'.BASE.'/ver/'.$artd['url'].'">'.lmWord($artd['titulo'],40).'</a>';
                    echo '<p>'.lmWord($artd['conteudo'],186).'</p>';
                echo '</div>';

                $readArt = read('posts',"WHERE status = '1' AND categoria = '$catArt[id]' ORDER BY cadastro DESC LIMIT 1,3");
                if($readArt):
                    echo '<ul class="artigos">';
                    foreach($readArt as $art):

                    $default = 'themes/blue/images/blue.jpg';
                    $capa = 'uploads/'.$art['capa'];
                    $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

                        echo '<li>';
                            echo '<img title="'.$art['titulo'].'" alt="'.$art['titulo'].'" src="'.BASE.'/tim.php?src='.$capa.'&w=100&h=100" width="100" heigth="100"/>';
                            echo '<a title="'.$art['titulo'].'" href="'.BASE.'/ver/'.$art['url'].'">'.lmWord($art['titulo'],50).'</a>';
                            echo '<p>'.lmWord($art['conteudo'],120).'</p>';
                        echo '</li>';
                    endforeach;
                endif;
            echo '<li class="readmore"><a href="'.BASE.'/categoria/'.$catArt['url'].'" title="Ver '.$catArt['categoria'].' na '.SITENAME.'">VEJA +</a></li>';
                echo '</ul>';
            ?>
    </div><!-- /content -->
</div><!-- /BLOCO QUATRO -->



<div class="clear"></div><!--/clear-->
</div><!-- /HOME GERAL -->  
</div><!-- #SITE -->
    
  
<!-- FOOTER -->    
<div id="footer" class="footer">
    <div class="content">
        <?php setArq('themes/'.THEME.'/sidebars/menu');?>
    </div><!-- /content -->
</div><!-- /#FOOTER -->