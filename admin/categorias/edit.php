<a href="#" class="j_back linkback" title="Voltar">VOLTAR</a>

<div class="content home">
    <?php
        $catid = mysql_real_escape_string($_GET['catid']);
        $readCat = read('categorias',"WHERE id = '$catid'");
        if(!$readCat):
            header('Location: dashboard.php?exe=categorias/index&notfound=true');
        else:
            foreach($readCat as $cat);
        endif;
    ?>
    <h1 class="location"><strong>Editar Categoria:</strong> <?php echo $cat['categoria'];?></span></h1><!--/location-->
    
	<div class="posts">
    	<form name="editcat" class="formfull" action="" method="post">

            <label class="label">
                <?php
                    $readSes = read('categorias',"WHERE id = '$cat[sessao]'");
                    if($readSes):
                        foreach($readSes as $ses);
                        echo '<span class="field" style="font-size:30px; color:#ccc;">Sessão: '.$ses['categoria'].'</span>';
                    endif;
                ?>
            </label>
        
        	<label class="label">
            	<span class="field">Categoria:</span>
        		<input type="text" name="categoria" value="<?php echo $cat['categoria'];?>" />
            </label>
            
            <label class="label">
             	<span class="field">Descrição:</span>
             	<textarea name="descricao" rows="2" style="resize:none"><?php echo $cat['descricao'];?></textarea>
            </label>                     
             
             <div class="label">
             	<span class="field">Imagem de capa:</span>
                    <input type="file" class="j_capa" accept="image/*" name="capa" />
                    <div class="j_false"></div>
                    <img src="img/upload.png" class="j_send" alt="Enviar Capa" title="Enviar Capa" />
                   
                    <div class="viewcapa" <?php if(!$cat['capa']) echo 'style="display: none;"'; ?>>
                        <img src="tim.php?src=../uploads/<?php echo $cat['capa'];?>&h=42" alt="Capa" title="Capa" />
                        <a href="../uploads/<?php echo $cat['capa'];?>" title="Ver Capa">Ver Capa</a>
                    </div><!--viewcapa-->                                
             </div>
             
             <div class="progress" style="display: none;"><div class="bar">0%</div></div>
                          
             <label class="label">
            	<span class="field">Data:</span>
        		<input type="text" class="formDate" name="data" value="<?php echo date('d/m/Y H:i:s');?>" />
            </label>

            <img src="img/loader.gif" class="load loadimg" alt="Carregando..." title="Carregando..." />
            <input type="hidden" value="<?php echo $cat['id'];?>" name="catid">
            <input type="submit" value="Atualizar Categoria" class="btn" />     
            
        </form>
	</div><!--/posts -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->