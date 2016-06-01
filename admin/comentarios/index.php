<?php date_default_timezone_set('America/Fortaleza'); ?>

<div class="content home">
    <h1 class="location">Comentários <span><?php echo date('d/m/Y - H:i:s');?></span></h1><!--/location-->

    <div class="comentarios">

        <?php

        if($_SESSION['dellecom']):
            echo '<div class="dellcom">Comentário removido com sucesso!<span>X</span></div>';
            //unset($_SESSION['dellecom']);
        elseif($_GET['notfound']):
                echo '<div class="notfound">Desculpe. Você tentou editar um comentário que não existe!</div>';
        endif;

        echo '<ul class="listcom">';

        $page = mysql_real_escape_string($_GET['page']);
        $page = ($page == '' ? '1' : $page);
        $maximo = 10;
        $inicio = ($page * $maximo) - $maximo;

        $readComment = read('comments',"WHERE resp_id IS NULL AND isadmin IS NULL ORDER BY status ASC, data DESC LIMIT $inicio,$maximo");
        if(!$readComment):

            if($page > 1):
                $page = $page - 1;
                header('Location: dashboard.php?exe=comentarios/index&page='.$page);
            else:
                echo '<li class="notfound">Ainda não existem comentários em seu site!</li>';
            endif;

        else:
            foreach($readComment as $com):

                $readArt = read('posts',"WHERE id = '$com[post_id]'");
                if($readArt) foreach($readArt as $art);

                $readResp = read('comments',"WHERE resp_id = '$com[id]'");

                $matts = array('alt' => 'Avatar de '.$com['nome'],'title' => 'Avatar de '.$com['nome']);
                $mavatar = gravatar($com['email'],60,'mm','g',true,$matts);

                echo '
			<li class="li'; if($com['status'] < 2) echo ' pendente" '; echo '" id="'.$com['id'].'">
				'.$mavatar.'
				<div class="commentitem">
					<span class="infor">De <strong>'.$com['nome'].'</strong> sobre <strong><a target="_blanck" href="../ver/'.$art['url'].'">'.lmWord($art['titulo'],50).'...</a></strong></span>
					<p>'.lmWord($com['comentario'],160).'...</p>
					<strong style="color:#666">'.date('d/m/Y H:i',strtotime($com['data'])).'</strong>
					<div class="actions" style="display:none;">
					    <a href="#" class="delete" id="'.$com['id'].'">Deletar</a>
						<a href="dashboard.php?exe=comentarios/moderar&commnet='.$com['id'].'" class="edit">Moderar comentários</a>
					</div><!-- /actions -->
				</div><!--/commentitem-->
				<div class="estats">
					<strong>'.count($readResp).'</strong> respostas
				</div><!--/estatis-->
			</li>
		';

            endforeach;
        endif;
        echo '</ul><!--/comentários-->';

        //PAGINACAO
        $link = 'dashboard.php?exe=comentarios/index&page=';
        readPaginator('comments', 'WHERE resp_id IS NULL AND isadmin IS NULL ORDER BY status ASC, data DESC', $maximo, $link, $page, '', '5', 'n','div');
        ?>

    </div><!--/ comentarios -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->