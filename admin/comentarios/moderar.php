
<?php
$comid 		 = mysql_real_escape_string($_GET['commnet']);
$readComment = read('comments',"WHERE id = '$comid'");
if(!$readComment):
    header('Location: dashboard.php?exe=comentarios/index&notfound=true');
else:
    foreach($readComment as $com);

    $readArt = read('posts',"WHERE id = '$com[post_id]'");
    if($readArt) foreach($readArt as $art);

    $atts = array('alt' => 'Avatar de '.$com['nome'],'title' => 'Avatar de '.$com['nome'],'class' => 'avatar');
    $avatar = gravatar($com['email'],60,'mm','g',true,$atts);
endif;
?>

<a href="#back" class="linkback j_back" title="voltar">Voltar</a>

<div class="content home">
    <h1 class="location">Moderar Comentário:<span><?php echo date('d/m/Y H:i');?></span></h1><!--/location-->

    <div class="commentmanage">
        <div class="principal<?php if(!$com['status']) echo ' pendente';?>">

            <div class="info">
                <?php echo $avatar;?>
                <span class="infor">De <strong><?php echo $com['nome'];?></strong> sobre <strong><a target="_blank" href="../ver/<?php echo $art['url'];?>"><?php echo lmWord($art['titulo'],70);?>...</a></strong></span>
            </div><!--/info-->

            <div class="text" id="<?php echo $com['id'];?>">
                <?php echo $com['comentario'];?>
            </div><!--text-->

            <div class="actions">
                <span class="data">Enviada dia <strong><?php echo date('d/m/Y',strtotime($com['data']));?></strong> às <strong><?php echo date('H:i',strtotime($com['data']));?></strong>hs</span>
                <div class="moderate">
                    <a id="<?php echo $com['id'];?>" class="editar" href="#" title="Editar"><img src="img/manage/edit.png" title="Editar" alt="Editar" /></a>
                    <a id="<?php echo $com['id'];?>" class="deletar" href="#" title="Deletar"><img src="img/manage/delete.png" title="Delete" alt="Delete" /></a>
                    <?php if(!$com['isadmin']):?>
                        <a<?php if($com['status']) echo ' style="display:none"';?> id="<?php echo $com['id'];?>" class="aceitar" href="#" title="Aceitar Comentário"><img src="img/manage/moderate.png" title="Aceitar Comentário" alt="Aceitar Comentário" /></a>
                        <a<?php if(!$com['status']) echo ' style="display:none"';?> id="<?php echo $com['id'];?>" class="ocultar" href="#" title="Ocultar Comentário"><img src="img/manage/active.png" title="Ocultar Comentário" alt="Ocultar Comentário" /></a>
                    <?php endif;?>


                </div><!--moderate-->
            </div><!--/actions-->

        </div><!--principal-->


        <?php
        $readResp = read('comments',"WHERE resp_id = '$com[id]' ORDER BY data ASC");
        echo '<ul class="respostas">';
        if($readResp):
            foreach($readResp as $res):

                if($res['isadmin']):
                    $readAdmin = read('usuarios',"WHERE id = '$res[isadmin]'");
                    if($readAdmin) foreach($readAdmin as $a);
                    $res['nome']  = $a['nome'];
                    $res['email'] = $a['email'];
                endif;

                $ratts = array('alt' => 'Avatar de '.$res['nome'],'title' => 'Avatar de '.$res['nome'],'class' => 'avatar');
                $ravatar = gravatar($res['email'],60,'mm','g',true,$ratts);

                $class = ($res['isadmin'] ? ' admin' : (!$res['status'] ? ' pendente' : ''));
                ?>
                <li class="li<?php echo $class;?>" id="<?php echo $res['id'];?>">
                    <div class="info">
                        <?php echo $ravatar;?>
                        <span class="infor">Nova resposta de  <strong><?php echo $res['nome'];?></strong></span>
                    </div><!--/info-->

                    <div class="text" id="<?php echo $res['id'];?>">
                        <?php echo $res['comentario'];?>
                    </div><!--text-->

                    <div class="actions">
                        <span class="data">Enviada dia <strong><?php echo date('d/m/Y',strtotime($res['data']));?></strong> às <strong><?php echo date('H:i',strtotime($res['data']));?></strong>hs</span>
                        <div class="moderate">
                            <a id="<?php echo $res['id'];?>" class="editar" href="#" title="Editar"><img src="img/manage/edit.png" title="Editar" alt="Editar" /></a>
                            <a id="<?php echo $res['id'];?>" class="deletar" href="#" title="Deletar"><img src="img/manage/delete.png" title="Delete" alt="Delete" /></a>

                            <?php if(!$res['isadmin']):?>
                                <a<?php if($res['status']) echo ' style="display:none"';?> id="<?php echo $res['id'];?>" class="aceitar" href="#" title="Aceitar Comentário"><img src="img/manage/moderate.png" title="Aceitar Comentário" alt="Aceitar Comentário" /></a>
                                <a<?php if(!$res['status']) echo ' style="display:none"';?> id="<?php echo $res['id'];?>" class="ocultar" href="#" title="Ocultar Comentário"><img src="img/manage/active.png" title="Ocultar Comentário" alt="Ocultar Comentário" /></a>
                            <?php endif;?>

                        </div><!--moderate-->
                    </div><!--/actions-->
                </li>
            <?php
            endforeach;

        endif;
        echo '</ul><!--/respostas-->';
        ?>

        <form name="addresposta" action="" method="post">
            <h3>Adicionar Resposta:</h3>
            <textarea name="mensagem" rows="5"></textarea>
            <input type="hidden" value="<?php echo $com['id'];?>" name="id">
            <input type="hidden" value="<?php echo $com['post_id'];?>" name="post_id">
            <input type="submit" value="Adicionar" class="btn" />
            <img class="load" style="display: none;" src="img/loader.gif" alt="Carregando..." title="Carregando..." />
        </form>


    </div><!--/ commentmanage -->

    <div class="clear"></div><!-- /clear -->
</div><!-- /content -->

<div class="ajaxmoderate"></div><!--ajaxmoderate-->