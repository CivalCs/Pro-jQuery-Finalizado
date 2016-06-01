<!-- SLIDE HOME TOPO -->
<div id="home" class="homeslide">
<ul class="slidequery">

    <?php
        $readHomeSlide = read('posts',"WHERE status = '1' ORDER BY cadastro DESC LIMIT 3");
        if($readHomeSlide):
            foreach($readHomeSlide as $hsl):
               $default = BASE.'/themes/'.THEME.'/images/'.THEME.'.jpg';
               $capa = BASE.'/uploads/'.$hsl['capa'];
               $slCapa = ($hsl['capa'] && file_exists($capa) && !is_dir($capa) ? $capa : $default);

               echo '<li>';
                  echo '<img title="'.$hsl['titulo'].'" alt="'.$hsl['titulo'].'" src="'.$capa.'" />';
                   echo ' <div class="info">';
                       echo ' <div class="content">';
                          echo '<div class="texto">';
                               echo '<h2><a title="'.$hsl['titulo'].'" href="'.BASE.'/ver/'.$hsl['url'].'">'.lmWord($hsl['titulo'],45).'</a></h2>';
                              echo '<p>'.lmWord($hsl['conteudo'],240).'</p>';
                           echo ' </div>';
                        echo '<div class="slidequerynav"></div>';
                    echo '<div class="clear"></div>';
                    echo '</div>';
                    echo '</div>';
                echo '</li>';
            endforeach;
        endif;
    ?>

</ul>
</div><!-- /homeslide -->