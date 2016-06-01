<?php
    $pgurl = mysql_real_escape_string($_GET['url']);
    $pgurl = explode('/',$pgurl);
?>
<div class="logo">
        <img src="<?php setHome();?>/themes/blue/images/logotype.png" />
</div><!-- /logo --> 


<div class="left">
    <ul class="navtopo">
        <li><a class="a" title="<?php echo SITENAME;?> | Home" href="<?php setHome();?>">Home</a></li>
        <li><a class="a<?php if(in_array('videos',$pgurl)) echo ' active';?>" title="<?php echo SITENAME;?> | Vídeos" href="<?php setHome();?>/categoria/videos">Vídeos</a></li>
        <li><a class="a<?php if(in_array('artigos',$pgurl)) echo ' active';?>" title="<?php echo SITENAME;?> | Artigos" href="<?php setHome();?>/categoria/artigos">Artigos</a></li>
        <li class="sub"><a class="subopen" title="<?php echo SITENAME;?> | Ver Mais" href="#">+</a>
            <?php
            $readSubMenu = read('categorias',"WHERE url != 'videos' AND url != 'artigos' AND sessao IS NOT NULL");
            if($readSubMenu):
                echo '<ul class="submenu">';
                foreach($readSubMenu as $smenu):
                    echo '<li><a ';
                    if(in_array($smenu['url'],$pgurl)) echo 'class="ativa" ';
                    echo 'href="'.BASE.'/categoria/'.$smenu['url'].'" title="'.SITENAME.' | '.$smenu['categoria'].'">'.$smenu['categoria'].'</a></li>';
                endforeach;
                echo '</ul>';
            endif;
            ?>
        </li>
        <li><a title="<?php echo SITENAME;?> | Fale Conosco" class="pagecontato a" href="#contato">Contato</a></li>
    </ul><!-- /topo -->
</div><!--  left -->