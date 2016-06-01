<?php
$search = mysql_real_escape_string(urldecode($url[1]));
$pContent = 'Você esta pesquisando po <span class="j_psearch">'.$search.'</span>, veja abaixo os resultados que temos para seus termos. Boa leitura!';
$pCapa = '../themes/blue/images/search.png';

echo getSeo(SITENAME.' | Pesquisa por:'.$search ,$pContent,'pesquisa/'.urlencode($search), $pCapa);
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

    <h1>Pesquisa:</h1>
    <h2><?php echo $pContent;?></h2>

    <div class="capa">
        <?php
            $default = 'themes/blue/images/blue.jpg';
            $capa = 'uploads/'.$cat['capa'];
            $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);
        ?>
       	<img src="<?php setHome();?>/tim.php?src=<?php echo $pCapa;?>&w=200&h=200" alt="Pesquisa por: <?php echo $search;?>" title="Pesquisa por: <?php echo $search;?>" />
    </div><!-- /capa -->
        
</div><!-- /BLOCO UM -->
<div class="clear"></div><!-- /clear -->

<div class="categorias j_search">
    <?php
        $page = mysql_real_escape_string($url[2]);
        $page = preg_replace('/[^0-9]*/','',$page);
        $page = ($page ? $page : 1);

        $maximo = 12;
        $inicio = ($page * $maximo) - $maximo;

        $searchL = mb_strtolower($search,'UTF-8');
        $searchU = mb_strtoupper($search,'UTF-8');
        $readArt = read('posts',"WHERE status = '1' AND (titulo LIKE '%$searchL%' OR titulo LIKE '%$searchU%') ORDER BY cadastro DESC LIMIT $inicio, $maximo");

        if(!$readArt):
            if($page > 1 ):
                $pagenum = $page - 1;
                header('Location: '.BASE.'/categoria/'.$cat['url'].'/'.$pagenum);
            else:
                echo '<div class="notfound">';
                    echo '<h3>Desculpe, sua pesquisa não encontrou resultados!</h3>';
                    echo '<p>Sua pesquisa por <span class="j_psearch">'.$search.'</span> não retornou resultados! Talvez você queira tentar outros termos. Uma pesquisa mais ampla e com menos palavras tem mais chances de retornar resultados!</p>';
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
                            echo '<a title="'.$art['titulo'].'" href="'.BASE.'/ver/'.$art['url'].'">'.lmWord($art['titulo'],62).'</a>';
                        echo '</div>';
                    echo '</li>';
                endforeach;
            echo '</ul>';

            echo '<div class="clear"></div>';

            echo '<div class="paginator">';
                $link = BASE.'/pesquisa/'.urlencode($search).'/';
                readPaginator('posts',"WHERE status = '1' AND (titulo LIKE '%$searchL%' OR titulo LIKE '%$searchU%') ORDER BY cadastro DESC",$maximo,$link, $page);
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