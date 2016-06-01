<div class="content home">
    <h1 class="location">Painel Home <span><?php echo date('d/m/Y H:i');?></span></h1><!--/location-->
    
    <div class="left">
        <?php
            function countViews($coluna, $tabela){
                $qr = "SELECT SUM({$coluna}) as views FROM {$tabela}";
                $st = mysql_query($qr) or die('Erro ao contar dados '. mysql_error());
                $views = 0;
                $visitas = mysql_result($st,$views,"views");

                if($visitas >= 1):
                    $visitas = $visitas;
                else:
                    $visitas = '0';
                endif;

                return $visitas;
            }

            $visitas = countViews('visitas','siteviews');
            $usuarios = countViews('usuarios','siteviews');
            $pageviews = countViews('pageviews','siteviews');
            $pageviews = substr($pageviews/$usuarios,0,4);

            $posts = read('posts');
            $comments = read('comments');
            $categorias = read('categorias');
        ?>
    
        <div class="boxleft estatisticas">
            <h3>Estatísticas totais:</h3>
            <div class="content">
            
                <ul class="views">
                    <li class="visitas"><?php echo $visitas;?><small>visitas</small></li><!--/visitas-->
                    <li class="users"><?php echo $usuarios;?><small>usuários</small></li><!--/visitantes-->
                    <li class="media right"><?php echo $pageviews;?><small>pageviews</small></li><!--/pageviews-->
                </ul><!--/views-->
                
                <ul class="conteudo">
                    <li class="topic"><?php echo count($posts);?><small>posts</small></li><!--/artigos-->
                    <li class="comment"><?php echo count($comments);?><small>comentários</small></li><!--/comentários-->
                    <li class="cats"><?php echo count($categorias);?><small>categorias</small></li><!--/categorias-->
                </ul><!--/views-->
                <?php
                    $timeHome = time();
                    delete('useronline',"endview < '$timeHome'");
                    $readHomeUserOnline = read('useronline',"WHERE endview > '$timeHome'");
                ?>
                <a href="#" title="Ver usuários online agora!" class="useronline j_useronline"><strong class="j_useronlinerealtime"><?php echo count($readHomeUserOnline);?></strong> Usuários Online Agora</a>
            </div><!--/content-->
        </div><!--/estatisticas-->
        
        
        <div class="boxleft trafego">
            <h3>Tráfego diário: <a href="#" title="Gerar Relatórios" class="j_gerastats">Tráfego</a></h3>
            <div class="content">
                <ul class="relatorio">
                    <li class="title">
                        <span class="date">Mês/Ano</span>
                        <span class="users">Usuários</span>
                        <span class="views">Visitas</span>
                        <span class="pages">PageViews</span>
                    </li>
                    <?php
                        $readRelatórioH = read('siteviews',"ORDER BY id DESC LIMIT 7");
                        if($readRelatórioH):
                            foreach($readRelatórioH as $reH):
                                $i++;
                                $pageviewsH = substr($reH['pageviews']/$reH['usuarios'],0,4);

                                echo '<li'; if($i%2==0) echo ' class="color"'; echo '>';
                                echo '<span class="date"><strong>'.date('d/m/Y',strtotime($reH['data'])).'</strong></span>';
                                echo '<span class="users">'.$reH['usuarios'].'</span>';
                                echo '<span class="views">'.$reH['visitas'].'</span>';
                                echo '<span class="pages">'.$pageviewsH.'</span>';
                                echo '</li>';

                                $totalvisitasH += $reH['visitas'];
                                $totalusuariosH += $reH['usuarios'];
                                $totalpageviewsH += $reH['pageviews'];
                            endforeach;

                            $totalpageviewsH = substr($totalpageviewsH/$totalusuariosH,0,4);
                        endif;
                    ?>
                    <li style="background: #7BBEFF;">
                        <span class="date"><strong>7 DIAS</strong></span>
                        <span class="users"><?php echo $totalusuariosH;?></span>
                        <span class="views"><?php echo $totalvisitasH;?></span>
                        <span class="pages"><?php echo $totalpageviewsH;?></span>
                    </li>
                </ul><!--/relatorio-->
            </div><!--/content-->
        </div><!--/estatisticas-->
    
    </div><!--/left-->
    
    
    <div class="comments boxleft">
        <h3>Comentários: <a title="Comentários" href="dashboard.php?exe=comentarios/index">MODERAR</a></h3>
        <div class="content">
            <ul class="comentarios">
               <?php
                    $readComment = read('comments',"WHERE resp_id IS NULL AND isadmin IS NULL ORDER BY status ASC, data DESC LIMIT 7");
                    if(!$readComment):
                        echo '<li>Ainda nÃ£o existem comentÃ¡rios em seu site!</li>';
                    else:
                        foreach($readComment as $com):
                            $readArt = read('posts',"WHERE id = '$com[post_id]'");
                            if($readArt) foreach($readArt as $art);
                            
                            $matts = array('alt' => 'Avatar de '.$com['nome'],'title' => 'Avatar de '.$com['nome']);
                            $mavatar = gravatar($com['email'],60,'mm','g',true,$matts);
                            
                                echo '
                                    <li'; if($com['status'] < 2) echo ' class="pendente" '; echo '>
                                        '.$mavatar.'
                                        <div class="commentitem">
                                            <span>De <strong>'.$com['nome'].'</strong> sobre <strong>'.lmWord($art['titulo'],36).'...</strong></span>   
                                            <p>'.lmWord($com['comentario'],120).'...</p>
                                        </div><!--/commentitem-->
                                    </li>
                                ';
                        endforeach;
                    endif;          
                ?> 

            </ul><!--/comentários-->
        </div><!--/content-->
    </div><!--/ comments -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->