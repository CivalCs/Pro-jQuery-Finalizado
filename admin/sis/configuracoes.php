<?php
/*valNivel('1');
if(!function_exists(myAut)): header('Location: dashboard.php'); die; endif;*/
if(function_exists(myAut)): valNivel('1'); else: header('Location: ../dashboard.php'); die; endif;
?>
<div class="content home">
    <h1 class="location">Configurações<span><?php echo date('d/m/Y H:i');?></span></h1><!--/location-->
    
    <div class="configs">
    
        <ul class="abas_config">
            <li><a href="config_manutencao" class="active" title="Módulo de Manutenção">Módulo de Manutenção</a></li>
            <li><a href="config_email" title="E-mail de envio">Servidor de e-mail</a></li>
            <li><a href="config_seo" title="Otimizar Home">Otimizar Home</a></li>
            <li><a href="config_dados" title="Endereço e Telefone">Endereço e Telefone</a></li>
            <li><a href="config_theme" title="Gerenciar Temas">Gerenciar Temas</a></li>
        </ul><!--/navega-->
    
    
    	<!-- //FORM CONFIG MANUTENÇÃO -->
    	<form name="config_manutencao" class="first" action="" method="post">        
            <fieldset>
                <?php
                    $readMain = read('config_manutencao');
                    foreach($readMain as $manutencao);
                    $main = $manutencao['manutencao'];
                ?>
                <legend>Acesso ao site:</legend>
                <div class="j_manutencao_desativa main<?php if($main == 1) echo ' block';?>">
                    <span class="field">Modo Manutenção: <strong style="color:red">ATIVO</strong></span>
                    <input type="submit" value="DESATIVAR MANUTENÇÃO" class="btn j_config_manutencao_no" /> 
                    <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />
                </div>
                
                <div class="j_manutencao_ativa main <?php if($main != 1) echo ' block';?>">
                    <span class="field">Modo Manutenção: <strong style="color:green">INATIVO</strong></span>
                    <input type="submit" value="ATIVAR MANUTENÇÃO" class="btn j_config_manutencao_yes" /> 
                    <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />
                </div>
            </fieldset>
        </form>
        
        
        
        <!-- //FORM SERVER MAIL -->
        <form name="config_email" action="" method="post">
            <?php
                $readMailServer = read('config_mailserver');
                foreach($readMailServer as $mailServer);
            ?>
            <fieldset>
                <legend>Email de envio:</legend>
                    <label class="label">
                        <span class="field">E-mail:</span>
                        <input type="text" name="email" value="<?php echo $mailServer['email'];?>"/>
                    </label>
                    
                    <label class="label">
                        <span class="field">Senha:</span>
                        <input type="password" name="senha" value="<?php echo $mailServer['senha'];?>"/>
                    </label>
                    
                    <label class="label">
                        <span class="field">Porta:</span>
                        <input type="text" name="porta" value="<?php echo $mailServer['porta'];?>"/>
                    </label>
                    
                    <label class="label">
                        <span class="field">Server:</span>
                        <input type="text" name="server" value="<?php echo $mailServer['server'];?>"/>
                    </label>

                <input type="submit" value="Atualizar Dados" class="btn" />
                <input type="button" value="Testar Envio" class="btn teste j_config_email_teste" />
                <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />
            </fieldset>               
        </form>
        
        
        <!-- //FORM CONFIG SEO -->
        <form name="config_seo" action="" method="post">
            <?php
                $readConfigSeo = read('config_seosocial');
                foreach($readConfigSeo as $configSeo);
            ?>
        <fieldset>
        	<legend>SEO/Social:</legend>
            	<label class="label">
        			<span class="field">Titulo:</span>
                    <input type="text" name="titulo" value="<?php echo $configSeo['titulo'];?>"/>
                </label>
                
                <label class="label">
        			<span class="field">Descrição:</span>
                    <textarea name="descricao" rows="5"><?php echo $configSeo['descricao'];?></textarea>
                </label>
                
                <label class="label">
        			<span class="field">Facebook:</span>
                    <input type="text" name="facebook" value="<?php echo $configSeo['facebook'];?>"/>
                </label>
                
                <label class="label">
        			<span class="field">Twitter:</span>
                    <input type="text" name="twitter" value="<?php echo $configSeo['twitter'];?>"/>
                </label>  
                
                <input type="submit" value="Otimizar Site" class="btn" /> 
                <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />          
        </fieldset>     
        </form>

        <!-- //FORM ENDEREÇO E TELEFONE -->
        <form name="config_dados" action="" method="post">
            <?php
                $readConfigEnd = read('config_endtel');
                foreach($readConfigEnd as $configEnd);
            ?>
            <fieldset>
                <legend>Endereço/Telefone:</legend>
                <label class="label">
                    <span class="field">Endereço:</span>
                    <input type="text" name="endereco" value="<?php echo $configEnd['endereco'];?>" />
                </label>

                <label class="label">
                    <span class="field">Telefone:</span>
                    <input type="text" class="formFone" name="telefone" value="<?php echo $configEnd['telefone'];?>"/>
                </label>

                <input type="submit" value="Atualizar Dados" class="btn" />
                <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />
            </fieldset>
        </form>

        <!-- //FORM THEME -->
        <form name="config_theme" action="" method="post">
            <?php
                $readConfigTheme = read('config_theme',"ORDER BY inuse DESC, cadastro ASC");
                if($readConfigTheme):
                    echo '<ul class="themes">';
                        echo '<li class="title">';
                            echo '<span>Tema:</span>';
                            echo '<span>Pasta:</span>';
                            echo '<span>Criado em:</span>';
                            echo '<span>-</span>';
                        echo '</li>';
                        foreach($readConfigTheme as $configTheme):

                            $pasta = '../themes/'.$configTheme['pasta'];
                            $valid = (file_exists($pasta) && is_dir($pasta) ? '1' : '0');
                            $stdir = ($valid ? '<strong style="color: green">&radic;</strong>':'<strong style="color: #900">&Chi;</strong>');

                            echo '<li id="'.$configTheme['id'].'"'; if($configTheme['inuse']) echo ' class="active"'; echo '>';
                                echo '<span>'.$configTheme['nome'].'</span>';
                                echo '<span>'.$stdir.' - '.$configTheme['pasta'].'</span>';
                                echo '<span>'.date('d/m/Y',strtotime($configTheme['cadastro'])).'</span>';
                                if(!$configTheme['inuse'] && $valid):
                                    echo '<span><a href="#" title="Ativar Tema: '.$configTheme['nome'].'" id="'.$configTheme['id'].'" class="j_themeactive">ATIVAR TEMA</a></span>';
                                elseif(!$valid):
                                    echo '<span><a href="#" title="Deletar Tema: '.$configTheme['nome'].'" id="'.$configTheme['id'].'" class="j_themedelete">DELETAR TEMA</a></span>';
                                else:
                                    echo '<span>ATIVO!</span>';
                                endif;
                            echo '</li>';
                        endforeach;
                    echo '</ul>';
                endif;
            ?>
            <fieldset>
                <legend>Adicionar Tema:</legend>
                <label class="label">
                    <span class="field">Nome:</span>
                    <input type="text" name="nome"/>
                </label>

                <label class="label">
                    <span class="field">Pasta:</span>
                    <input type="text" name="pasta"/>
                </label>

                <input type="submit" value="Adicionar Tema" class="btn" />
                <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />
            </fieldset>
        </form>

    </div><!--/configs -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->