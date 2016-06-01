<div class="content home">
    <?php
        $search = mysql_real_escape_string($_GET['search']);
        $search = urldecode($search);

    ?>
    <h1 class="location">Pesquisar <strong><?php echo $search;?> em categorias </strong> <span><?php echo date('d/m/Y H:i');?></span></h1><!--/location-->
    
	<div class="posts">
        	<?php
                if($_POST['search']):
                    $pesquisar = mysql_real_escape_string($_POST['sposts']);
                    $pesquisar = urlencode($pesquisar);
                    header('Location: dashboard.php?exe=categorias/pesquisa&search='.$pesquisar);
                endif;

                $page = mysql_real_escape_string($_GET['page']);
                $page = ($page == '' ? '1' : $page);
                $maximo = 10;
                $inicio = ($page * $maximo)-$maximo;

                $searchL = mb_strtolower($search,'UTF-8');
                $searchU = mb_strtoupper($search,'UTF-8');
                $readPesquisa = read('categorias',"WHERE categoria LIKE '%$searchL%' OR categoria LIKE '%$searchU%' OR descricao LIKE '%$searchL%' OR descricao LIKE '%$searchU%' LIMIT $inicio, $maximo");

                echo '<div class="paginator">';
                    echo '<div class="paginator_form">';
                        echo '<img src="img/loader.gif" alt="Carregando..." class="load" title="Carregando..." />';
                        echo '<form name="searchpost" action="" method="post">';
                            echo '<input type="text" name="sposts" title="Pesquisar Categorias:" class="j_placeholder"/>';
                            echo '<input type="submit" name="search" value="Buscar" class="btn" />';
                        echo '</form>';
                    echo '</div>';
                    //paginação
                    $link = 'dashboard.php?exe=categorias/pesquisa&search='.urlencode($search).'&page=';
                    readPaginator('categorias',"WHERE categoria LIKE '%$searchL%' OR categoria LIKE '%$searchU%' OR descricao LIKE '%$searchL%' OR descricao LIKE '%$searchU%'",$maximo,$link,$page, '', '5', 'n');
                echo '</div>';

                if(!$readPesquisa):
                    echo '<div class="notfound"><h2>Desculpe, não encontramos sessões ou categorias para sua pesquisa. Favor tente outros termos! Obrigado!</h2></div>';
                else:
                    echo '<ul class="content catli">';
                        foreach($readPesquisa as $pesquisa):

                            $contaCategorias = read('categorias',"WHERE sessao = '$pesquisa[id]'");
                            $subcats = count($contaCategorias);

                            $readPosts = read('posts',"WHERE sessao = '$pesquisa[id]' OR categoria = '$pesquisa[id]'");
                            $posts = count($readPosts);

                            $tipo = ($pesquisa['sessao'] != '' ? 'Categoria' : 'Sessão');

                            echo '<li class="li" id="'.$pesquisa['id'].'">';
                                if($pesquisa['capa'] && is_file('../uploads/'.$pesquisa['capa'])):
                                    echo '<img src="../tim.php?src=../uploads/'.$pesquisa['capa'].'&w=120&h=120" />';
                                else:
                                    echo '<img src="../tim.php?src=../themes/'.THEME.'/images/imgnotfound.jpg&w=120&h=120" />';
                                endif;
                                echo ' <div class="info" style="width:636px;">';
                                    echo '<p class="title">'.$pesquisa['categoria'].'</p>';
                                    echo '<p class="resumo">'.lmWord($pesquisa['descricao'],120).'</p>';
                                    echo '<p class="categoria">'.date('d/m/Y H:i:s', strtotime($pesquisa['cadastro'])).'</p>';
                                    echo '<span>';
                                        if($subcats == '0' && $posts == '0'):
                                            echo '<a title="Excluir" class="delete j_catdelet" id="'.$pesquisa['id'].'" href="#">Excluir</a> ';
                                        endif;
                                        echo '<a title="Editar" class="edit" href="dashboard.php?exe=categorias/edit&catid='.$pesquisa['id'].'">Editar</a> ';
                                        echo '<a title="Ver" class="ver" href="../categoria/'.$pesquisa['url'].'" target="_blank">Ver</a>';
                                    echo '</span>';
                                echo '</div>';
                                echo '<ul class="sub">';
                                    echo '<li>TIPO: <strong>'.$tipo.'</strong></li>';
                                echo '</ul>';
                            echo '</li>';
                        endforeach;
                    echo '</ul>';
                    //paginação
                    echo '<div class="paginator">';
                        echo '<div class="paginator_form">';
                            echo '<img src="img/loader.gif" alt="Carregando..." class="load" title="Carregando..." />';
                            echo '<form name="searchpost" action="" method="post">';
                                echo '<input type="text" name="sposts" title="Pesquisar Categorias:" class="j_placeholder"/>';
                                echo '<input type="submit" name="search" value="Buscar" class="btn" />';
                            echo '</form>';
                        echo '</div>';

                        $link = 'dashboard.php?exe=categorias/pesquisa&search='.urlencode($search).'&page=';
                        readPaginator('categorias',"WHERE categoria LIKE '%$searchL%' OR categoria LIKE '%$searchU%' OR descricao LIKE '%$searchL%' OR descricao LIKE '%$searchU%'",$maximo,$link,$page, '', '5', 'n');
                    echo '</div>';
                endif;
            ?>

	</div><!--/posts -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->