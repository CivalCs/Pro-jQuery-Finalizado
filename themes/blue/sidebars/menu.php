<div class="logo">
        <img src="<?php setHome();?>/themes/blue/images/logotype.png" />
</div><!-- /logo --> 


<div class="left">
    <ul class="navtopo">
        <li><a class="a" title="<?php echo SITENAME;?> | Home" href="#home">Home</a></li>
        <li><a class="a" title="<?php echo SITENAME;?> | Vídeos" href="#videos">Vídeos</a></li>
        <li><a class="a" title="<?php echo SITENAME;?> | Artigos" href="#artigos">Artigos</a></li>
        <li class="sub"><a class="subopen" title="<?php echo SITENAME;?> | Ver Mais" href="#">+</a>
                <?php
                    $readSubMenu = read('categorias',"WHERE url != 'videos' AND url != 'artigos' AND sessao IS NOT NULL");
                    if($readSubMenu):
                        echo '<ul class="submenu">';
                            foreach($readSubMenu as $smenu):
                                    echo '<li><a href="'.BASE.'/categoria/'.$smenu['url'].'" title="'.SITENAME.' | '.$smenu['categoria'].'">'.$smenu['categoria'].'</a></li>';
                            endforeach;
                        echo '</ul>';
                    endif;
                ?>
        </li>
        <li><a class="a pagecontato" title="<?php echo SITENAME;?> | Fale Conosco" href="#contato">Contato</a></li>
    </ul><!-- /topo -->
</div><!--  left -->