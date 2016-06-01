<?php
$urlpost = mysql_real_escape_string($url[1]);
$readpost = read('posts',"WHERE url = '$urlpost'");
if(!$readpost):
    header('Location: '.BASE.'/404');
else:
    foreach($readpost as $p);
    if(!$p['status'] && !$_SESSION['userlogin']['id']):
        header('Location: '.BASE.'/404');
    else:
        $_SESSION['useracess']['postid'] = $p['id'];
        setViews($p['id']);
        echo getSeo($p['titulo'].' | '.SITENAME, $p['conteudo'],'ver/'.$p['url'],$p['capa']);
    endif;
endif;

?>

</head>

<!--body -->
<body>

<div class="dialog"></div><!--Mensagens ajax-->
<div class="contato j_contato"></div><!--Contato ajax-->
<div class="body"></div><!--Efeito pontinhos na body-->

<?php
	setArq('themes/'.THEME.'/sidebars/pgheader');
?> 

<div class="commentbox" style="display: none;"></div><!-- /commentbox -->
  
<!-- BLOCO SITE GERAL HOME -->
<div id="site">
<div class="home single">

	<ul class="sidebar">
    
     	<li>
        	<h3>COMPARTILHE:</h3>
            <div class="content">           
                <ul class="social">
                    <li class="radius">
                        <div class="fb-like" 
                            data-href="<?php setHome();?>/ver/<?php echo $p['url'];?>"
                            data-send="false" 
                            data-layout="box_count"
                            data-show-faces="true">
                        </div>
                    </li>
                    
                    <li class="radius">
                        <a href="http://twitter.com/share" 
                        class="twitter-share-button" 
                        data-url="<?php setHome();?>/ver/<?php echo $p['url'];?>"
                        data-count="vertical" 
                        data-via="Conectese">Tweet</a>
                    </li>
                    
                    <li class="radius">
                        <g:plusone size="tall" href="<?php setHome();?>/ver/<?php echo $p['url'];?>"></g:plusone>
                    </li>
                </ul><!-- /redes -->   
            </div><!-- /content -->
        </li>
        <li>
        	<h3>RELACIONADOS:</h3>
            <div class="content">
            	<ul class="related">
                    <?php
                        $readRelated = read('posts',"WHERE status = '1' AND id != '$p[id]' AND categoria = '$p[categoria]' ORDER BY cadastro DESC LIMIT 5");
                        if($readRelated):
                            foreach($readRelated as $re):

                                $default = 'themes/blue/images/blue.jpg';
                                $capa = 'uploads/'.$re['capa'];
                                $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

                                echo '<li>';
                                    echo '<img src="'.BASE.'/tim.php?src='.$capa.'&w=240&h=120" alt="'.$re['titulo'].'" title="'.$re['titulo'].'" />';
                                    echo '<a title="'.$re['titulo'].'" href="'.BASE.'/ver/'.$re['url'].'">'.lmWord($re['titulo'],70).'</a>';
                                    echo '<p>'.lmWord($re['conteudo'],160).'</p>';
                                echo '</li>';
                            endforeach;
                        else:
                            echo '<li>Desculpe, nunhum post relacionado foi encontrado!</li>';
                        endif;
                    ?>
                </ul><!--/related -->
            </div><!-- /content -->
        </li>
        <li>
        	<h3>FACEBOOK:</h3>
            <div class="content" style="padding-bottom:30px;">
                <div style="padding:0 5px;" class="fb-page" data-href="https://www.facebook.com/webpiaui/" data-tabs="timeline" data-width="232" data-height="360" data-small-header="false" data-adapt-container-width="false" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/webpiaui/"><a href="https://www.facebook.com/webpiaui/">WebPi</a></blockquote></div></div>
            </div><!-- /content -->
        </li>
             
    </ul><!-- /sidebar -->
    
<div class="artigo">

    <h1><?php echo $p['titulo'];?></h1>
    <?php if($p['video']):?>
        <iframe width="700" height="394" src="https://www.youtube.com/embed/<?php echo $p['video'];?>" frameborder="0" allowfullscreen></iframe>
    <?php else:
        $default = 'themes/blue/images/blue.jpg';
        $artcapa = 'uploads/'.$p['capa'];
        $artcapa = (file_exists($artcapa) && !is_dir($artcapa) ? $artcapa : $default);
    ?>
	    <img title="<?php echo $p['titulo'];?>" alt="<?php echo $p['titulo'];?>" width="<?php echo IMAGEW;?>" src="<?php setHome();?>/tim.php?src=<?php echo $artcapa;?>&w=<?php echo IMAGEW;?>">
    <?php endif;?>
    <div class="content">
        <?php echo stripslashes($p['conteudo']);?>
        	<?php
                $readGB = read('posts_gallery',"WHERE post_id = '$p[id]' ORDER BY uploaded ASC");
                if($readGB):
                    echo '<ul class="gallery">';
                        foreach($readGB as $gb):

                            if($i%5==0): 	$w = '300'; $h = '320';
                            else: 			$w = '150'; $h = '150';		endif;
                            if($i%10==0):	$class = ''; else: $class = 'right'; endif;
                            $i++;

                            $default = 'themes/blue/images/blue.jpg';
                            $gbcapa = 'uploads/'.$gb['imagem'];
                            $gbcapa = (file_exists($gbcapa) && !is_dir($gbcapa) ? $gbcapa : $default);

                            echo '<li class="'.$class.'">
                                <a href="'.BASE.'/'.$gbcapa.'" rel="shadowbox['.$p['id'].']" title="'.$p['titulo'].'">
                                    <img src="'.BASE.'/tim.php?src='.$gbcapa.'&w='.$w.'&h='.$h.'" alt="'.$p['titulo'].'" title="'.$p['titulo'].'"/>
                                </a>
                            </li>';
                        endforeach;
                    echo '</ul>';
                    echo '<div class="clear"></div>';
                endif;
			?>
    </div><!-- /content -->
    
    <div class="comments">
    	<h3>Comente isso!   <a id="<?php echo base64_encode($p['id']);?>" class="radius opencomment" href="#comment">Comentar</a></h3>
        <ul class="commentlist">
            <li class="li moderar moderajax" style="display:none;"></li>
            <?php
            if($_COOKIE['MyContentComment']):
                $email = base64_decode($_COOKIE['MyContentComment']);
                $readMail = read('comments',"WHERE post_id = '$p[id]' AND status IS NULL AND email = '$email' ORDER BY data DESC");
                if($readMail) foreach($readMail as $mail):
                    $matts = array('alt' => 'Avatar de '.$mail['nome'],'title' => 'Avatar de '.$mail['nome'],'class' => 'radius');
                    $mavatar = gravatar( $mail['email'],60,'mm','g',true,$matts);
                    echo '<li class="li moderar">';
                        echo '<p class="alert">Olá <strong>'.$mail['nome'].'</strong>, seu comentário neste artigo esta sendo moderado, em breve liberaremos!</p>';
                        echo '<div class="user">';
                            echo $mavatar;
                            echo '<div class="info"><strong>por</strong> <span>VOCÊ!</span> <strong>em</strong> <span>'.date('d/m/y H:i',strtotime($mail['data'])).'</span></div>';
                        echo '</div>';
                        echo $mail['comentario'];
                    echo '</li>';
                endforeach;
            endif;

            $readComment = read('comments',"WHERE post_id = '$p[id]' AND status >= '1' AND resp_id IS NULL ORDER BY data DESC LIMIT 5");
                if($readComment):
                    foreach($readComment as $com):
                        $atts = array('alt'=>'Avatar de '.$com['nome'],'title'=>'Avatar de '.$com['nome'],'class'=>'radius');
                        $avatar = gravatar($com['email'],60,'mm','g',true,$atts);
                        echo '<li class="li comp">';
                            echo '<div class="user">';
                                echo $avatar;
                                echo '<div class="info"><strong>por</strong> <span>'.$com['nome'].'</span> <strong>em</strong> <span>'.date('d/m/Y H:i',strtotime($com['data'])).'</span></div>';
                            echo '</div>';
                            echo '<p>'.$com['comentario'].'</p>';
                            echo '<a href="#" title="Responda '.$com['nome'].'" class="addresp" id="'.base64_encode($com['id']).'">Responder</a>';

                            $postid = $_SESSION['useracess']['postid'];
                            $readResp = read('comments',"WHERE status = '2' AND post_id = '$postid' AND resp_id = '$com[id]' ORDER BY data ASC");
                            if($readResp):
                                //add and admin and null
                                echo '<ul class="resposta">';
                                    foreach($readResp as $resp):
                                    if($resp['isadmin']):
                                        $readAdmin = read('usuarios',"WHERE id = '$resp[isadmin]'");
                                        if($readAdmin) foreach($readAdmin as $a);
                                        $resp['nome'] = $a['nome'];
                                        $resp['email'] = $a['email'];
                                    endif;
                                        $cc ++;
                                        $attsR = array('alt'=>'Avatar de '.$resp['nome'],'title'=>'Avatar de '.$resp['nome'],'class'=>'radius');
                                        $avatarR = gravatar($resp['email'],42,'mm','g',true,$atts);
                                         echo '<li'; if($resp['isadmin']) echo ' class="admin" '; elseif($cc%2==0) echo ' class="add" '; echo '>';
                                             echo '<div class="user">';
                                                echo $avatarR;
                                                echo '<div class="info"><strong>por</strong> <span>'.$resp['nome'].'</span> <strong>em</strong> <span>'.date('d/m/Y H:i',strtotime($resp['data'])).'</span></div>';
                                             echo '</div>';
                                             echo $resp['comentario'];
                                             echo '<a href="#" title="Responda '.$resp['nome'].'" class="addresp" id="'.base64_encode($resp['id']).'">Responder</a>';
                                         echo '</li>';
                                    endforeach;
                                echo '</ul>';
                            endif;
                        echo ' </li>';
                    endforeach;
                else:
                    echo '<li class="li nocomments">Não existem comentários ainda, clique em comentar e seja o primeiro!</li>';
                endif;

                $readCount = read('comments',"WHERE post_id = '$p[id]' AND status >= '1' AND resp_id IS NULL");
                if(count($readCount) > count($readComment)):
                    echo '<span id="'.base64_encode($p['id']).'" class="loadmorcomment">CARREGAR MAIS COMENTÁRIOS!</a>';
                //<img src="'.BASE.'/themes/blue/images/loader2.gif" width="20" title="Carregando" alt="Carregando">
                endif;
            ?>

        </ul><!--/commentlist-->
    </div><!--/comments-->
        
</div><!-- /artigo -->
<div class="clear"></div><!-- /clear -->


</div><!-- /HOME GERAL -->  
</div><!-- #SITE -->   
  
<!-- FOOTER -->    
<div class="footer">
    <div class="content">
        <?php setArq('themes/'.THEME.'/sidebars/pgmenu');?>
    </div><!-- /content -->
</div><!-- /#FOOTER -->

<script type="text/javascript" src="<?php setHome();?>/themes/<?php echo THEME;?>/js/facebook.js"></script>
<script type="text/javascript" src="<?php setHome();?>/themes/<?php echo THEME;?>/js/twitter.js"></script>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'pt-BR'}</script>