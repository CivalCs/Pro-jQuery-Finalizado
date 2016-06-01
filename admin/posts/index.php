<div class="content home">
    <h1 class="location">Gerenciar Posts <span><?php echo date('d/m/Y H:i');?></span></h1><!--/location-->

    <?php if($_GET['notfound']) echo '<div class="postsnotfound">Opsss, você tentou editar um post que não existe!</div>';?>
    
	<div class="posts">
        <?php
            if($_POST['search']):
                $pesquisar = mysql_real_escape_string($_POST['sposts']);
                $pesquisar = urlencode($pesquisar);
                header('Location: dashboard.php?exe=posts/pesquisa&search='.$pesquisar);
            endif;

            $page = mysql_real_escape_string($_GET['page']);
            $page = ($page == '' ? '1' : $page);
            $maximo = 10;
            $inicio = ($page * $maximo)-$maximo;

            $readPosts = read('posts',"ORDER BY status ASC, cadastro DESC LIMIT $inicio, $maximo");

            echo '<div class="paginator">';
                echo '<div class="paginator_form">';
                    echo '<form name="searchpost" action="" method="post">';
                        echo '<input type="text" name="sposts" title="Pesquisar Posts:" class="j_placeholder"/>';
                        echo '<input type="submit" name="search" value="Buscar" class="btn" />';
                    echo '</form>';
                echo '</div>';
                //paginação
                $link = 'dashboard.php?exe=posts/index&page=';
                readPaginator('posts','ORDER BY status ASC, cadastro DESC',$maximo,$link,$page, '', '5', 'n');
            echo '</div>';

            if(!$readPosts):
                if($page > '1'):
                    $back = $page - 1;
                    header('Location: dashboard.php?exe=posts/index&page='.$back);
                else:
                    echo '<div class="postsnotfound" style="float: left; width: 950px; margin-top: 10px;">Ainda não existem posts cadastrados!</div>';
                endif;
            else:
                echo '<ul class="content j_hover postsul">';
                    foreach($readPosts as $p):
                        $readCategoria = read('categorias',"WHERE id = '$p[categoria]'");

                        $visitas = ($p['views'] ? $p['views'] : '0');
                        $readComments = read('comments',"WHERE post_id = $p[id]");

                        foreach($readCategoria as $cat);

                        echo '<li class="li'; if(!$p['status']) echo ' off'; echo '" id="'.$p['id'].'">';
                            if($p['capa'] && is_file('../uploads/'.$p['capa'])):
                                echo '<img src="../tim.php?src=../uploads/'.$p['capa'].'&w=200&h=120" />';
                            else:
                                echo '<img src="../tim.php?src=../themes/'.THEME.'/images/imgnotfound.jpg&w=200&h=120" />';
                            endif;
                            echo '<div class="info">';
                               echo '<p class="title">'.lmWord($p['titulo'],60).'</p>';
                               echo '<p class="resumo">'.lmWord($p['conteudo'],120).'</p>';
                               echo ' <p class="categoria"><a href="'.BASE.'/categoria/'.$cat['url'].'" target="_blank" style="text-transform: uppercase; font-size: 12px;">'.$cat['categoria'].'</a> &nbsp;&nbsp;/&nbsp;&nbsp; '.date('d/m/y H:i', strtotime($p['cadastro'])).'</p>';
                               echo '<span style="display: none;">';
                                   echo ' <a title="Excluir" class="delete j_postsdel" href="#" id="'.$p['id'].'">Excluir</a>';
                                   echo '<a title="Compartilhar" class="share j_postshare" href="'.BASE.'/ver/'.$p['url'].'">Compartilhar</a>';
                                   echo '<a title="Editar" class="edit" href="dashboard.php?exe=posts/edit&id='.$p['id'].'">Editar</a>';
                                   echo '<a title="Ver" target="_blank" class="ver" href="'.BASE.'/ver/'.$p['url'].'">Ver</a>';
                               echo ' </span>';
                           echo ' </div>';
                           echo '<ul class="sub">';
                               echo ' <li><strong>'.$visitas.'</strong> visitas</li>';
                               echo '<li><strong>'.count($readComments).'</strong> comentários</li>';
                           echo '</ul>';
                        echo '</li>';
                    endforeach;
                echo '</ul>';
            endif;

            echo '<div class="paginator">';
                echo '<div class="paginator_form">';
                    echo '<form name="searchpost" action="" method="post">';
                        echo '<input type="text" name="sposts" title="Pesquisar Posts:" class="j_placeholder"/>';
                        echo '<input type="submit" name="search" value="Buscar" class="btn" />';
                    echo '</form>';
                echo '</div>';
                //paginação
                $link = 'dashboard.php?exe=posts/index&page=';
                readPaginator('posts','ORDER BY status ASC, cadastro DESC',$maximo,$link,$page, '', '5', 'n');
            echo '</div>';
        ?>
	</div><!--/posts -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->