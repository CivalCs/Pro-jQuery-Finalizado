<?php
if(!function_exists(myAut)): header('Location: dashboard.php'); die; endif;
//if(function_exists(myAut)): myAut('1'); else: header('Location: ../dashboard.php'); die; endif;
?>
<div class="dialog">
    <div class="boxleft modaluseronline">
        <h3>Usuários online agora: <a href="#" class="j_closeuseronline">Fechar</a></h3>
        <div class="content">
        </div><!--/content-->
    </div><!--/modaluseronline-->

    <div class="boxleft trafego modaltrafego">
        <h3>Tráfego por mês: <a href="#" class="j_closetrafic">Fechar</a></h3>
        <div class="content">

            <form name="geradados" action="" method="post">
                <span class="title">Consultar Estatísticas:</span>
                <label>
                    <span>de:</span>
                    <input type="text" class="formDay" name="inicio" />
                </label>

                <label>
                    <span>à:</span>
                    <input type="text" class="formDay" name="final" value="<?php echo date('d/m/Y');?>" />
                </label>

                <img src="img/loader.gif" class="load" alt="Carregando" title="Carregando" />
                <input type="submit" class="btn" value="Gerar Relatório" />

            </form>

            <ul class="relatorio j_relatorio">
                <li class="title">
                    <span class="date">Dia</span>
                    <span class="users">Usuários</span>
                    <span class="views">Visitas</span>
                    <span class="pages">PageViews</span>
                </li>
                <?php
                    $readRelatório = read('siteviews',"ORDER BY id DESC LIMIT 7");
                    if($readRelatório):
                        foreach($readRelatório as $re):
                            $i++;
                            $pageviews = substr($re['pageviews']/$re['usuarios'],0,4);

                            echo '<li'; if($i%2==0) echo ' class="color"'; echo '>';
                                echo '<span class="date"><strong>'.date('d/m/Y',strtotime($re['data'])).'</strong></span>';
                                echo '<span class="users">'.$re['usuarios'].'</span>';
                                echo '<span class="views">'.$re['visitas'].'</span>';
                                echo '<span class="pages">'.$pageviews.'</span>';
                            echo '</li>';

                            $totalvisitas += $re['visitas'];
                            $totalusuarios += $re['usuarios'];
                            $totalpageviews += $re['pageviews'];
                        endforeach;

                        $totalpageviews = substr($totalpageviews/$totalusuarios,0,4);
                    endif;
                ?>

                <li class="title">
                    <span class="date">TOTAL</span>
                    <span class="users">Usuários</span>
                    <span class="views">Visitas</span>
                    <span class="pages">PageViews</span>
                </li>
                <li>
                    <span class="date"><strong>7 DIAS</strong></span>
                    <span class="users"><?php echo $totalusuarios;?></span>
                    <span class="views"><?php echo $totalvisitas;?></span>
                    <span class="pages"><?php echo $totalpageviews;?></span>
                </li>
            </ul><!--/relatorio-->

        </div><!--/content-->
    </div><!--/modaltrafego-->

    <div class="loadmodal j_editposts">
        <p class="title">
            <img src="img/loader.gif" alt="Carregando" title="Carregando" />
            ATUALIZANDO POST!
            <span>Aguarde enquanto todos os dados são processados.</span>
        </p>
        <div class="content">
            <div class="progress">
                <div class="bar">0%</div>
            </div>

            <p class="accept">
                <strong>Parabéns</strong>. Seu post foi atualizado com sucesso!
                <a href="#" class="j_closeloadposts">FECHAR</a>
            </p>
        </div><!--/CONTENT-->
    </div><!--/LOADMODAL-->

    <div class="loadsistem">
        <img src="img/loader.gif" alt="Carregando o sistema" title="Carregando o sistema"/>
        <p>Aguarde, Carregando o sistema!</p>
    </div><!-- msg -->

    <div class="ajaxmsg msg"></div><!-- Modal para mensagens. Não remover -->
    
    <!-- NEW POST -->
    <div class="modal newpost">
    	<h2>NOVO POST:</h2>
        <div class="content">
            <form name="cadnewpost" action="" method="post">
                <label>
                    <span>Selecione a categoria:</span>
                    <select name="categoria">
                    </select>
                </label>
                <label>
                    <span>Titulo do post:</span>
                    <input type="text" name="titulo" />
                </label>
                
                <input type="submit" value="Cadastrar" class="btn" />
                <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />
            </form>
            
        </div><!--/content-->
        <a href="#" class="closemodal" id="newpost">X FECHAR</a>
    </div><!--/newpost-->
    
    
    <!-- NEW CAT -->
    <div class="modal newcat">
    	<h2>NOVA CATEGORIA:</h2>
        <div class="content">
            <form name="cadnewcat" action="" method="post">
                <label>
                    <span>Sessão:</span>
                    <select name="sessao">
                    </select>
                </label>
                <label>
                    <span>Categoria:</span>
                    <input type="text" name="categoria" />
                </label>
                
                <input type="submit" value="Cadastrar" class="btn" />
                <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />
            </form>
            
        </div><!--/content-->
        <a href="#" class="closemodal" id="newcat">X FECHAR</a>
    </div><!--/newcat-->
    
    
    <!-- NEW USER -->
    <div class="modal newuser">
    	<h2>CADASTAR USUÁRIO:</h2>
        <div class="content">
            <form name="cadnewuser" action="" method="post">
                <label>
                    <span>Nível:</span>
                    <select name="nivel">
                        <option value="2">Admin</option>
                        <option value="1">Super Admin</option>
                    </select>
                </label>
                <label>
                    <span>Nome:</span>
                    <input type="text" name="nome" />
                </label>
                
                <label>
                    <span>E-mail:</span>
                    <input type="text" name="email" />
                </label>
                
                <label>
                    <span>Login:</span>
                    <input type="text" name="login" />
                </label>
                
                <label>
                    <span>Senha:</span>
                    <input type="password" name="pass" />
                </label>
                
                <input type="submit" value="Cadastrar" class="btn" />
                <img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />
            </form>
            
        </div><!--/content-->
        <a href="#" class="closemodal" id="newuser">X FECHAR</a>
    </div><!--/newuser-->

    <div class="modal edituser"></div><!--/edita usuário via ajax-->

</div><!-- /dialog -->