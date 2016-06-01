<a href="#" class="j_back linkback" title="Voltar">VOLTAR</a>
<div class="content home">
    <?php
        $postid = mysql_real_escape_string($_GET['id']);
        $rPosts = read('posts',"WHERE id = '$postid'");
        if(!$rPosts):
            header('Location: dashboard.php?exe=posts/index&notfound=true');
        else:
            foreach($rPosts as $p);
        endif;

    ?>
    <h1 class="location"><strong>Editar Post:</strong> <?php echo $p['titulo']; ?></h1><!--/location-->
    
	<div class="posts">
    	<form name="editpost" class="formfull" action="" method="post">
        
        	<label class="label">
            	<span class="field">Titulo do post:</span>
        		<input type="text" name="titulo" value="<?php echo $p['titulo']; ?>" />
            </label>
                      
            <label class="label">
                    <span class="field">Selecione a categoria:</span>
                    <select name="categoria">
                        <?php
                            $readSessoes = read('categorias','WHERE sessao IS NULL ORDER BY categoria ASC');
                            if($readSessoes):
                                foreach($readSessoes as $sessao):
                                    echo '<option disabled="disabled" value="'.$sessao['id'].'">'.$sessao['categoria'].'</option>';
                                    $readCat = read('categorias',"WHERE sessao = '$sessao[id]' ORDER BY categoria ASC");
                                    if($readCat):
                                        foreach($readCat as $cat):
                                            echo '<option';
                                               if($p['categoria'] == $cat['id']): echo ' selected="selected"'; endif;
                                                echo ' value="'.$cat['id'].'">&raquo'.$cat['categoria'].'</option>';
                                        endforeach;
                                    endif;
                                endforeach;
                            endif;
                        ?>
                    </select>
             </label>
             
             <div class="label">
             	<span class="field">Imagem de capa:</span>
                    <input name="capa" type="file" class="j_capa" accept="image/*" />
                    <div class="j_false"></div>
                    <img src="img/upload.png" class="j_send" alt="Enviar Capa" title="Enviar Capa" />
                   
                    <div class="viewcapa" <?php if(!$p['capa']) echo ' style="display:none;"';?>>
                        <img <?php if(!$p['capa']) echo ' style="display:none;"';?> src="tim.php?src=../uploads/<?php echo $p['capa'];?>&h=42" alt="Capa" title="Capa" />
                        <a href="../uploads/<?php echo $p['capa'];?>"  title="Ver Capa">Ver Capa</a>
                    </div><!--viewcapa-->                                
             </div>
             
             <label class="label">
            	<span class="field">Vídeo:</span>
        		<input type="text" name="video" value="<?php echo $p['video']; ?>" />
            </label>
                          
             <label class="label tinymce">
             	<span class="field">Conteúdo:</span>
             	<textarea class="timeditor" name="conteudo" rows="10"><?php echo htmlspecialchars($p['conteudo']); ?></textarea>
             </label>
             
             <label class="label">
            	<span class="field">Data:</span>
        		<input type="text" class="formDate" name="cadastro" value="<?php echo date('d/m/Y H:i:s', strtotime($p['cadastro']));?>" />
            </label>
            
            <div class="gallery">
                
                <div class="label" style="margin:0;">
                	<span class="field">Galeria:</span>
                    <input name="gb[]" type="file" class="j_gallery" multiple="multiple" accept="image/*" />
                    <div class="j_gfalse">Selecione quantas imagens quiser!</div>
                    <img src="img/upload.png" class="j_gsend" alt="Enviar Capa" title="Enviar Capa" style="margin:0 0 10px 10px;" />
             	</div>
                <!--"-->
                <?php
                    $readGB = read('posts_gallery',"WHERE post_id = '$p[id]'");
                    echo '<ul>';
                    if($readGB):
                        foreach($readGB as $gb):
                            echo '<li id="'.$gb['id'].'">';
                            echo '<a href="../uploads/'.$gb['imagem'].'" class="gb_open" title="Ver Imagem">';
                                echo '<img src="tim.php?src=../uploads/'.$gb['imagem'].'&w=148&h=90" />';
                                echo '</a>';
                            echo '<a href="#" id="'.$gb['id'].'" class="gallerydel" title="Excluir">X</a>';
                            echo '</li>';
                        endforeach;
                    endif;
                    echo '</ul>';
                ?>
            </div><!--/gallery-->

            <input type="hidden" name="postid" value="<?php echo $p['id']; ?>" />

            <img src="img/loader.gif" class="j_editpostimgload" alt="Carregando" title="Carregando" />

            <input type="submit" value="Atualizar post" class="btn" />  
            <label class="check"><input <?php if($p['status']) echo 'checked="checked"';?> name="status" type="checkbox" value="1" />Publicar Isto</label><!-- /check -->
            
        </form>
	</div><!--/posts -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->