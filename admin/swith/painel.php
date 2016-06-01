<?php
    ob_start();
    session_start();

    require_once('../../dts/configs.php');
    if(!function_exists(myAut)): header('Location: dashboard.php'); die; endif;

    //if(function_exists(myAut)): valNivel('1'); else: header('Location: ../dashboard.php'); die; endif;

    $acao = mysql_real_escape_string($_POST['acao']);

    switch($acao){
        //INICIA HOME
        //Usuários online
        case 'home_usuariosonline':
            echo '<div class="user_title">';
                echo '<span>Usuários:</span>';
                echo '<span>StartView:</span>';
                echo '<span>EndView:</span>';
                echo '<span>IP:</span>';
                echo '<span>Local:</span>';
            echo '</div>';

            echo '<ul class="useronlinelist">';
            $timethis = time();
            delete('useronline',"endview < '$timethis'");
            $readModalUserOnline = read('useronline',"WHERE endview > '$timethis'");
            if(!$readModalUserOnline):
                echo '<li class="notfound">Não existem usuários online agora!</li>';
            else:
                foreach($readModalUserOnline as $modalUserOnline):
                    $i++;
                    echo '<li'; if($i%2==0) echo ' class="line"'; echo '>';
                        echo '<span>'.$i.'</span>';
                        echo '<span>'.date('H:i',$modalUserOnline['startview']).'hs</span>';
                        echo '<span>'.date('H:i',$modalUserOnline['endview']).'hs</span>';
                        echo '<span>'.$modalUserOnline['userip'].'</span>';
                        echo '<span><a href="'.$modalUserOnline['userurl'].'" target="_blank">'.$modalUserOnline['userurl'].'</a></span>';
                    echo '</li>';
                endforeach;
            endif;
            echo '</ul>';

            echo '<div class="user_footer"><strong>'.count($readModalUserOnline).'</strong> USUÁRIOS ONLINE AGORA!</div>';
        break;

        //Usuários real time
       case 'home_userreal';
            $realTime = time();
            delete('useronline',"endview < '$realTime'");
            $readUserRealTime = read('useronline',"WHERE endview > '$realTime'");
            echo count($readUserRealTime);
       break;

        //Gera Estatísticas
        case 'home_estatisticas':
            $c['inicio'] = mysql_real_escape_string($_POST['inicio']);
            $c['final'] = mysql_real_escape_string($_POST['final']);

            if(in_array('',$c)):
                echo 'errempty';
            else:
                $inicio = explode('/',$c['inicio']);
                $inicio = $inicio['2'].'-'.$inicio['1'].'-'.$inicio['0'];

                $final = explode('/',$c['final']);
                $final = $final['2'].'-'.$final['1'].'-'.$final['0'];

                $readTrafic = read('siteviews',"WHERE data >= '$inicio' AND data <= '$final' ORDER BY id DESC");
                if(!$readTrafic):
                    echo 'notfound';
                else:
                    echo '<li class="title">';
                        echo '<span class="date">Dia</span>';
                        echo '<span class="users">Usuários</span>';
                        echo '<span class="views">Visitas</span>';
                        echo '<span class="pages">PageViews</span>';
                    echo '</li>';

                    foreach($readTrafic as $re):
                        $i++;
                        $pageviews = substr($re['pageviews']/$re['usuarios'],0,4);

                        echo '<li'; if($i%2==0) echo ' class="color"'; echo '>';
                            echo '<span class="date"><strong>'.date('d/m/Y',strtotime($re['data'])).'</strong></span>';
                            echo '<span class="users">'.$re['usuarios'].'</span>';
                            echo '<span class="views">'.$re['visitas'].'</span>';
                            echo '<span class="pages">'.$pageviews.'</span>';
                        echo '</li>';

                        $totalusuarios += $re['usuarios'];
                        $totalvisitas += $re['visitas'];
                        $totalpageviews += $re['pageviews'];

                    endforeach;
                        $totalpageviews = substr($totalpageviews/$totalusuarios,0,4);

                        echo '<li class="title">';
                            echo '<span class="date">TOTAL</span>';
                            echo '<span class="users">Usuários</span>';
                            echo '<span class="views">Visitas</span>';
                            echo '<span class="pages">PageViews</span>';
                        echo '</li>';
                        echo '<li>';
                            echo '<span class="date"><strong>'.count($readTrafic).' DIAS</strong></span>';
                            echo '<span class="users">'.$totalusuarios.'</span>';
                            echo '<span class="views">'.$totalvisitas.'</span>';
                            echo '<span class="pages">'.$totalpageviews.'</span>';
                        echo '</li>';
                endif;
            endif;
        break;
        //INICIA POSTS
        //Read categorias
        case 'posts_categoria_read':
            echo '<option value=""></option>';
            $readSessoes = read('categorias','WHERE sessao IS NULL ORDER BY categoria ASC');
            if($readSessoes):
                foreach($readSessoes as $sessao):
                    echo '<option disabled="disabled" value="'.$sessao['id'].'">'.$sessao['categoria'].'</option>';
                    $readCat = read('categorias',"WHERE sessao = '$sessao[id]'ORDER BY categoria ASC");
                    if($readCat):
                        foreach($readCat as $cat):
                            echo '<option value="'.$cat['id'].'">&raquo'.$cat['categoria'].'</option>';
                        endforeach;
                    endif;
                endforeach;
            endif;
        break;
        //Cadastra post
        case 'posts_cadastro':
            $p['categoria'] = mysql_real_escape_string($_POST['categoria']);
            $p['titulo'] = mysql_real_escape_string($_POST['titulo']);
            if(in_array('',$p)):
                echo 'errempty';
            else:
                $p['url'] = setUri($p['titulo']);
                $p['cadastro'] = date('Y-m-d H:i:s');
                $readSessao = read('categorias',"WHERE id = '$p[categoria]'"); foreach($readSessao as $ses);
                $p['sessao'] = $ses['sessao'];
                $readPost = read('posts',"WHERE url = '$p[url]'");
                create('posts',$p);
                $postid = mysql_insert_id();
                if($readPost):
                    $u['url'] = $p['url'].'-'.$postid;
                    update('posts',$u,"id = '$postid'");
                endif;
                echo $postid;
            endif;
        break;

        //atualiza post
        case 'posts_update':
            sleep(1);
            $postid = mysql_real_escape_string($_POST['postid']);

            //Dados em texto
            $f['titulo'] = mysql_real_escape_string($_POST['titulo']);
            $f['categoria'] = mysql_real_escape_string($_POST['categoria']);

            //Lê e recupera sessão
            $readSes = read('categorias',"WHERE id = '$f[categoria]'");
            if($readSes): foreach($readSes as $ses); endif;
            $f['sessao'] = $ses['sessao'];

            $f['video'] = mysql_real_escape_string($_POST['video']);

            //Remove GAS
            $string = '%<p><object id=".+?" width="0" height="0" type="application/gas.+?"></object></p>%';
            $f['conteudo']= preg_replace($string,'',$_POST['conteudo']);
            //---
            $f['conteudo'] = mysql_real_escape_string(trim($f['conteudo']));
            $f['cadastro'] = mysql_real_escape_string($_POST['cadastro']);
            $f['status'] = mysql_real_escape_string($_POST['status']);

            $f['url'] = setUri($f['titulo']);
            $f['cadastro'] = formDate($f['cadastro']);

            $verificaURL = read('posts',"WHERE id != '$postid' AND url = '$f[url]'");
            if($verificaURL):
                $f['url'] = $f['url'].'-'.$postid;
            endif;

            //Validaçao da capa
            if($_FILES['capa']['tmp_name']):
                $readImg = read('posts',"WHERE id = '$postid'");
                foreach($readImg as $img);
                $capa = $_FILES['capa'];
                $pasta = '../../uploads/';

                if(file_exists($pasta.$img['capa']) && !is_dir($pasta.$img['capa'])): unlink($pasta.$img['capa']); endif;

                //gerador de pastas
                $m = date('m');
                $y = date('Y');
                if(!file_exists($pasta.'artigos')): mkdir($pasta.'artigos', 0755); endif;
                if(!file_exists($pasta.'artigos'.$y)): mkdir($pasta.'artigos'.$y, 0755); endif;
                if(!file_exists($pasta.'artigos'.$y.'/'.$m)): mkdir($pasta.'artigos'.$y.'/'.$m, 0755); endif;

                $ext = strrchr($capa['name'],'.');
                $ext = strtolower($ext);
                $baseDir = 'artigos'.$y.'/'.$m.'/';
                $capaName = $baseDir.$postid.'-'.time().$ext;

                $extePerm = array('image/jpeg','image/pjpeg','image/png','image/gif');

                if(!in_array($capa['type'],$extePerm)):
                    echo 'errext';
                else:
                    $f['capa'] = $capaName;
                    uploadImage($capa['tmp_name'], $capaName, 800, $pasta);
                    echo ' sendcapa';
                endif;
            endif;

            //validação da galeria
            if($_FILES['gb']['tmp_name']){
                $count = count($_FILES['gb']['tmp_name']);

                $gb = $_FILES['gb'];
                $pasta = '../../uploads/';

                $m = date('m');
                $y = date('Y');
                if(!file_exists($pasta.'gallery')): mkdir($pasta.'gallery', 0755); endif;
                if(!file_exists($pasta.'gallery'.$y)): mkdir($pasta.'gallery'.$y, 0755); endif;
                if(!file_exists($pasta.'gallery'.$y.'/'.$m)): mkdir($pasta.'gallery'.$y.'/'.$m, 0755); endif;

                for($i=0;$i<=$count;$i++):
                    $ext = strrchr($gb['name'][$i],'.');
                    $ext = strtolower($ext);
                    $baseDir = 'gallery'.$y.'/'.$m.'/';
                    $capaName = $baseDir.$postid.'-'.$i.time().$ext;

                    $extePerm = array('image/jpeg','image/pjpeg','image/png','image/gif');

                    if(in_array($gb['type'][$i],$extePerm)):
                        $cadastra = array('post_id' => $postid, 'imagem' => $capaName, 'uploaded' => date('Y-m-d H:i:s'));
                        create('posts_gallery',$cadastra);
                        uploadImage($gb['tmp_name'][$i], $capaName, 800, $pasta);
                        echo ' sendgb';
                    endif;
                endfor;
            }
            update('posts',$f,"id = '$postid'");
        break;

        //capa
        case 'posts_getcapa':
            $postid = $_POST['thispost'];
            $readCapa = read('posts',"WHERE id = '$postid'");
            foreach($readCapa as $capa);
            echo $capa['capa'];
        break;

        case 'posts_getgallery':
            $postid = $_POST['thisgb'];
            $readGB = read('posts_gallery',"WHERE post_id = '$postid'");
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
        break;

        //deleta imagens da galeria do post
        case 'posts_gbdel':
            $imagemId = $_POST['imagem'];
            $readImagem = read('posts_gallery',"WHERE id = '$imagemId'");
            if($readImagem):
                foreach($readImagem as $img);
                $pasta = '../../uploads/';
                if(file_exists($pasta.$img['imagem']) && !is_dir($pasta.$img['imagem'])): unlink($pasta.$img['imagem']); endif;
                delete('posts_gallery',"id = '$imagemId'");
            endif;
        break;

        //deleta post
        case 'posts_deleta':
            $postid = mysql_real_escape_string($_POST['id']);
            $pasta = '../../uploads/';

            $readAT =read('posts',"WHERE id = '$postid'");
            if($readAT):
                foreach($readAT as $at);
                    if(file_exists($pasta.$at['capa']) && !is_dir($pasta.$at['capa'])): unlink($pasta.$at['capa']); endif;
                    delete('posts_gallery',"id = '$at[id]'");
            endif;

            $readCO =read('comments',"WHERE post_id = '$postid'");
            if($readCO):
                delete('comments',"post_id = '$postid'");
            endif;

            $readGB = read('posts_gallery',"WHERE post_id = '$postid'");
            if($readGB):
                foreach($readGB as $gb):
                    if(file_exists($pasta.$gb['imagem']) && !is_dir($pasta.$gb['imagem'])): unlink($pasta.$gb['imagem']); endif;
                    delete('posts_gallery',"id = '$gb[id]'");
                endforeach;
            endif;
            delete('posts',"id = '$postid'");
        break;

        //INICIA COMENTARIOS
        case 'mod_dellist':
            $comid   = mysql_real_escape_string($_POST['comid']);
            $readCom = read('comments',"WHERE id = '$comid'");

            if($readCom) foreach($readCom as $com);

            if(!$com['status']):

                //Deleta comentario na index
                $logotype   = BASE.'/themes/blue/images/logotype.png';
                $mAssunto   = 'Olá <strong>'.$com['nome'].'</strong>, infelizmente seu comentário foi reprovado! Você comentou em:';
                $mContent   = '<p><strong>Seu Comentário:</strong></p><p>'.$com['comentario'].'</p><p style="font-size:12px"><strong>Mas porque isso? Temos algumas regras de aprovação em nosso site</strong>: Os comentários devem ser bem digitados, falar sobre o assunto do artigo de referência e não conter links externos, insultos ou conteúdo imprópio. Seu comentário provavelmente se encaixa em um destes motivos.</p><p>Mas não fique triste, apenas volte a nos visitar e comente novamente. Da próxima vai dar tudo certo! <strong>Estamos aqui esperando por você e suas opiniões!</strong></p>';
                $recusado   = geraEmail(SITENAME,$logotype,BASE,'Aguardamos você!',$mAssunto,$mContent,$com['post_id']);
                $email      = sendMail('Desculpe, Seu comentário foi reprovado!',$recusado,MAILUSER,SITENAME,$com['email'],$com['nome'],MAILUSER,SITENAME);

            endif;

            delete('comments',"id = '$com[id]' OR resp_id = '$com[id]'");

        break;
        //carrega modal
        case 'com_loadmodal':
            $comid   = mysql_real_escape_string($_POST['com']);
            $readCom = read('comments',"WHERE id = '$comid'");
            if($readCom) foreach($readCom as $com);

            $readArt = read('posts',"WHERE id = '$com[post_id]'");
            if($readArt) foreach($readArt as $art);

            if($com['isadmin']):
                $readAdm = read('usuarios',"WHERE id = '$com[isadmin]'");
                if($readAdm) foreach($readAdm as $a);
                $com['nome'] = $a['nome'];
                $com['email'] = $a['email'];
            endif;

            echo '<div class="contentcom">';
                echo '<p class="titulo">Moderar Comentário: <a href="#" class="j_closemoderal closemoderal">Fechar</a></p>';
                echo '<div class="commentcom">';
                    $comentario = str_replace('</p><p>',"\r\n",$com['comentario']);
                    $comentario = str_replace('<p>','',$comentario);
                    $comentario = str_replace('</p>','',$comentario);

                    $atts = array('alt' => 'Avatar de '.$com['nome'],'title' => 'Avatar de '.$com['nome'],'class' => 'avatar');
                    $avatar = gravatar($com['email'],60,'mm','g',true,$atts);

                    echo '<div class="info">';
                        echo $avatar;
                        echo '<span class="infor">De <strong>'.$com['nome'].'</strong> sobre:<a target="_blank" href="../ver/'.$art['url'].'">'.lmWord($art['titulo'],70).'...</a></span>';
                    echo '</div><!--/info-->';

                    echo '<form name="editcomment" action="" method="post">';
                        echo '<label>';
                            echo '<textarea name="comentario" rows="5">'.$comentario.'</textarea>';
                        echo '</label>';

                        echo '<img class="loadcom" src="img/loader.gif" alt="Carregando" title="Carregando">';
                        echo '<input type="hidden" value="'.$com['id'].'" name="comid"/>';
                        echo '<input type="submit" value="Atualizar Comentário" class="btn" /> ';

                        echo '<label>';
                            echo '<input type="text" name="data" class="formDate" value="'.date('d/m/Y H:i:s',strtotime($com['data'])).'" />';
                        echo '</label>';

                    echo '</form>';
                echo ' </div>';
            echo '</div>';
        break;

        //EDITA COMENTÁRIO
        case 'com_editar':
            $comId = mysql_real_escape_string($_POST['comid']);

            $readCom = read('comments',"WHERE id = '$comId'");
            if($readCom) foreach($readCom as $com);

            $c['comentario']= mysql_real_escape_string(strip_tags(trim($_POST['comentario'])));
            $c['comentario']= str_replace('\r\n','</p><p>',$c['comentario']);
            $c['comentario']= '<p>'.$c['comentario'].'</p>';
            $c['comentario']= str_replace('<p></p>','',$c['comentario']);
            $c['data']      = formDate($_POST['data']);

            if(in_array('',$c)):
                $r['erro'] = true;
            else:
                //UPDATE
                update('comments',$c,"id = '$comId'");
            endif;

            $r['id']            = $comId;
            $r['data']          = 'ENVIADA DIA '.date('d/m/Y',strtotime($c['data'])).' ÀS '.date('H:i',strtotime($c['data'])).'HS';
            $r['comentario']    = $c['comentario'];
            $r['tipo']          = ($com['resp_id'] ? '2' : '1');

            echo json_encode($r);
        break;

        //MODERA COMENTARIOS
        case 'com_moderate':
            $id         = mysql_real_escape_string($_POST['id']);
            $sa         = mysql_real_escape_string($_POST['subaction']);
            $logotype   = BASE.'/themes/blue/images/logotype.png';

            $readCom = read('comments',"WHERE id = '$id'");
            if($readCom) foreach($readCom as $com);

            if($sa == 'ocultar'):
                $ex = "UPDATE comments SET status = NULL WHERE id = '$id'";
                $st = mysql_query($ex);

                if($com['resp_id']):
                    $up = array('status' => '1');
                    update('comments',$up,"id = '$com[resp_id]'");
                endif;
            elseif($sa == 'aceitar'):
                $ex = array('status' => '2');
                update('comments',$ex,"id = '$id'");
                if(!$com['sendmail']):
                    //Aprovado
                    $mAssunto = 'Olá <strong>'.$com['nome'].'</strong>, Bem vindo ao '.SITENAME.', estamos aqui para informar que seu comentário foi aprovado. Você comentou em:';
                    $mContent = '<p><strong>Seu Comentário:</strong></p><p>'.$com['comentario'].'</p>';
                    $aprovado = geraEmail(SITENAME,$logotype,BASE,'Continue navegando em nosso site',$mAssunto,$mContent,$com['post_id']);
                    $email    = sendMail('Olá, seu comentário foi aprovado!',$aprovado,MAILUSER,SITENAME,$com['email'],$com['nome'],MAILUSER,SITENAME);
                    if($email):
                        $sendmail = array('sendmail' => '1');
                        update('comments',$sendmail,"id = '$id'");
                    endif;
                endif;

                if($com['resp_id']):
                    $readResp = read('comments',"WHERE id = '$com[resp_id]'");
                    if($readResp) foreach($readResp as $resp);

                    $readArt    = read('posts',"WHERE id = '$com[post_id]'");
                    if($readArt) foreach($readArt as $art);

                    //Respondido
                    $mAssunto   = 'Olá <strong>'.$resp['nome'].'</strong>, seu comentário foi respondido por <strong>'.$com['nome'].'</strong>. Vocês estão conversando em:';
                    $mContent   = '<p><strong>A resposta foi:</strong></p><p>'.$com['comentario'].'</p>';
                    $respondido = geraEmail(SITENAME,$logotype,BASE.'/ver/'.$art['url'],'Clique aqui e responda',$mAssunto,$mContent,$com['post_id']);
                    $email      = sendMail('Olá, seu comentário foi respondido!',$respondido,MAILUSER,SITENAME,$resp['email'],$resp['nome'],MAILUSER,SITENAME);

                    $readNumCom = read('comments',"WHERE resp_id = '$com[resp_id]' AND status IS NULL");
                    if(!$readNumCom){
                        $up = array('status' => '2');
                        update('comments',$up,"id = $com[resp_id]");
                    }
                endif;
            elseif($sa == 'deletar'):
                if(!$com['status']):
                    //Recusado
                    $mAssunto   = 'Olá <strong>'.$com['nome'].'</strong>, infelizmente seu comentário foi reprovado! Você comentou em:';
                    $mContent   = '<p><strong>Seu Comentário:</strong></p><p>'.$com['comentario'].'</p><p style="font-size:12px"><strong>Mas porque isso? Temos algumas regras de aprovação em nosso site</strong>: Os comentários devem ser bem digitados, falar sobre o assunto do artigo de referência e não conter links externos, insultos ou conteúdo imprópio. Seu comentário provavelmente se encaixa em um destes motivos.</p><p>Mas não fique triste, apenas volte a nos visitar e comente novamente. Da próxima vai dar tudo certo! <strong>Estamos aqui esperando por você e suas opiniões!</strong></p>';
                    $recusado   = geraEmail(SITENAME,$logotype,BASE,'Aguardamos você!',$mAssunto,$mContent,$com['post_id']);
                    $email      = sendMail('Desculpe, Seu comentário foi reprovado!',$recusado,MAILUSER,SITENAME,$com['email'],$com['nome'],MAILUSER,SITENAME);
                endif;

                delete('comments',"id = '$id' OR resp_id = '$id'");
                if(!$com['resp_id']):
                    $_SESSION['dellecom'] = true;
                endif;
            endif;

            if($com['resp_id']):
                echo '2';
            else:
                echo '1';
            endif;
        break;

        //MATA SESSÃO AO FECHAR JANELA
        case 'mode_reses':
            unset($_SESSION['dellecom']);
        break;

        //ADICIONA RESPOSTA ADMIN
        case 'mod_addadmin':
            $ad['resp_id']   = mysql_real_escape_string($_POST['id']);
            $ad['post_id']   = mysql_real_escape_string($_POST['post_id']);
            $ad['comentario']= mysql_real_escape_string(strip_tags(trim($_POST['mensagem'])));
            $ad['comentario']= str_replace('\r\n','</p><p>',$ad['comentario']);
            $ad['comentario']= '<p>'.$ad['comentario'].'</p>';
            $ad['comentario']= str_replace('<p></p>','',$ad['comentario']);

            $ad['isadmin']  = $_SESSION['userlogin']['id'];
            $ad['data']     = date('Y-m-d H:i:s');
            $ad['status']   = '2';

            $readValid = read('comments',"WHERE (status IS NULL OR status = 1) AND (id = '$ad[resp_id]' OR resp_id = '$ad[resp_id]')");

            if(!$ad['comentario']):
                echo 'errempty';
            elseif($readValid):
                echo 'errmod';
            else:
                create('comments',$ad);
                $newPost = mysql_insert_id();

                $readCom = read('comments',"WHERE id = '$newPost'");
                if($readCom) foreach($readCom as $res);

                $readAdmin = read('usuarios',"WHERE id = '$res[isadmin]'");
                if($readAdmin) foreach($readAdmin as $a);
                $res['nome']  = $a['nome'];
                $res['email'] = $a['email'];

                $ratts = array('alt' => 'Avatar de '.$res['nome'],'title' => 'Avatar de '.$res['nome'],'class' => 'avatar');
                $ravatar = gravatar($res['email'],60,'mm','g',true,$ratts);

                echo '<li class="li admin" id="'.$ARTINSERT.'">';
                    echo '<div class="info">';
                        echo $ravatar;
                        echo '<span class="infor">Nova resposta de  <strong>'.$res['nome'].'</strong></span>';
                    echo '</div><!--/info-->';

                    echo '<div class="text">';
                        echo $res['comentario'];
                    echo '</div><!--text-->';

                    echo '<div class="actions">';
                        echo '<span class="data">Enviada dia <strong>'.date('d/m/Y',strtotime($res['data'])).'</strong> às <strong>'.date('H:i',strtotime($res['data'])).'</strong>hs</span>';
                        echo '<div class="moderate">';
                            echo '<a id="'.$res['id'].'" class="editar" href="#" title="Editar"><img src="img/manage/edit.png" title="Editar" alt="Editar" /></a>';
                            echo '<a id="'.$res['id'].'" class="deletar" href="#" title="Deletar"><img src="img/manage/delete.png" title="Delete" alt="Delete" /></a>';
                        echo '</div><!--moderate-->';
                    echo '</div><!--/actions-->';
                echo '</li>';

                $readComResp = read('comments',"WHERE isadmin IS NULL AND (resp_id = '$ad[resp_id]' OR id = '$ad[resp_id]') GROUP BY email");
                if($readComResp):
                    foreach($readComResp as $cresp):
                        $mAssunto   = 'Olá <strong>'.$cresp['nome'].'</strong>, seu comentário foi respondido pela equipe <strong>'.SITENAME.'</strong> em:';
                        $mContent   = '<p><strong>A resposta foi:</strong></p><p>'.$res['comentario'].'</p>';
                        $respondido = geraEmail(SITENAME,$logotype,BASE,'Visite nosso site',$mAssunto,$mContent,$cresp['post_id']);
                        $email      = sendMail('Olá, seu comentário foi respondido!',$respondido,MAILUSER,SITENAME,$cresp['email'],$cresp['nome'],MAILUSER,SITENAME);
                    endforeach;
                endif;
            endif;
        break;

        //INICIA CATEGORIA
        //cadastra categoria
        case 'categoria_cadastro':
            $c['categoria'] = mysql_real_escape_string($_POST['categoria']);
            if(!$c['categoria']):
                echo 'errempty';
            else:
                $readIsset = read('categorias',"WHERE categoria = '$c[categoria]'");
                if($readIsset):
                    echo 'errisset';
                else:
                    $sessao = mysql_real_escape_string($_POST['sessao']);
                    if($sessao): $c['sessao'] = $sessao; endif;
                    $c['url'] = setUri($c['categoria']);
                    $c['cadastro'] = date('Y-m-d H:i:s');
                    create('categorias',$c);
                    $idretorno = mysql_insert_id();
                    echo $idretorno;
                endif;
            endif;
        break;
        //Read categorias
        case 'categoria_read':
              echo '<option value=""></option>';
              $readSessoes = read('categorias','WHERE sessao IS NULL ORDER BY categoria ASC');
              if($readSessoes):
                  foreach($readSessoes as $sessao):
                      echo '<option value="'.$sessao['id'].'">'.$sessao['categoria'].'</option>';
                      $readCat = read('categorias',"WHERE sessao = '$sessao[id]' ORDER BY categoria ASC");
                      if($readCat):
                          foreach($readCat as $cat):
                              echo '<option disabled="disabled" value="'.$cat['id'].'">&raquo'.$cat['categoria'].'</option>';
                          endforeach;
                      endif;
                  endforeach;
              endif;
        break;
        //update categoria
        case 'categoria_update':
           $catid = mysql_real_escape_string($_POST['catid']);
           $c['categoria'] = mysql_real_escape_string($_POST['categoria']);
           $c['url'] = setUri($c['categoria']);
           $c['descricao'] = mysql_real_escape_string($_POST['descricao']);
           $c['cadastro'] = mysql_real_escape_string($_POST['cadastro']);
           $c['cadastro'] = formDate($c['cadastro']);

           if(in_array('',$c)):
                echo 'errempty';
           else:
               $readCat = read('categorias',"WHERE id != '$catid' AND ( categoria = '$c[categoria]' OR url = '$c[url]')");
               if($readCat):
                    echo 'errisset';
               else:
                    $readImg = read('categorias',"WHERE id = '$catid'");
                    foreach($readImg as $img);

                    if($_FILES['capa']['tmp_name']):
                        $capa = $_FILES['capa'];
                        $pasta = '../../uploads/';

                        if(file_exists($pasta.$img['capa']) && !is_dir($pasta.$img['capa'])): unlink($pasta.$img['capa']); endif;

                        //gerador de pastas
                        $m = date('m');
                        $y = date('Y');
                        if(!file_exists($pasta.'categorias')): mkdir($pasta.'categorias', 0755); endif;
                        if(!file_exists($pasta.'categorias'.$y)): mkdir($pasta.'categorias'.$y, 0755); endif;
                        if(!file_exists($pasta.'categorias'.$y.'/'.$m)): mkdir($pasta.'categorias'.$y.'/'.$m, 0755); endif;

                        $ext = strrchr($capa['name'],'.');
                        $ext = strtolower($ext);
                        $baseDir = 'categorias'.$y.'/'.$m.'/';
                        $capaName = $baseDir.time().$ext;

                        $extePerm = array('image/jpeg','image/pjpeg','image/png','image/gif');

                        if(!in_array($capa['type'],$extePerm)):
                            echo 'errext';
                        else:
                            $c['capa'] = $capaName;
                            uploadImage($capa['tmp_name'], $capaName, 500, $pasta);
                            update('categorias',$c,"id='$catid'");
                            echo $c['capa'];
                        endif;
                    else:
                        update('categorias',$c,"id = '$catid'");
                        echo $img['capa'];
                    endif;
               endif;
           endif;
           //print_r($c);
        break;

        //deleta categoria
        case 'categoria_deleta':
            $catid = mysql_real_escape_string($_POST['catid']);
            $vCat = read('categorias',"WHERE sessao = '$catid'");
            $vPos = read('posts',"WHERE sessao = '$catid' OR categoria = '$catid'");
            if($vCat || $vPos):
                echo 'errisset';
            else:
                $pasta = '../../uploads/';
                $readImg = read('categorias',"WHERE id = '$catid'");
                foreach($readImg as $img);
                if(file_exists($pasta.$img['capa']) && !is_dir($pasta.$img['capa'])): unlink($pasta.$img['capa']); endif;
                delete('categorias',"id = '$catid'");
            endif;
        break;
        //INICIA USUÁRIOS
        //Cadastra / edita usuários
        case 'usuarios_manage':
            $u['nivel'] = mysql_real_escape_string($_POST['nivel']);
            $u['nome'] = mysql_real_escape_string($_POST['nome']);
            $u['email'] = mysql_real_escape_string($_POST['email']);
            $u['login'] = mysql_real_escape_string($_POST['login']);
            $u['code'] = mysql_real_escape_string($_POST['pass']);
            $u['senha'] = md5($u['code']);
            $u['cadastro'] = date('d/m/Y H:i:s');
            if(in_array('',$u)):
                echo 'errempty';
            elseif(!isMail($u['email'])):
                echo 'errmail';
            else:
                $exe = $_POST['exe'];
                if($exe == 'cadastro'):
                    $readUserIsset = read('usuarios',"WHERE email = '$u[email]' OR login = '$u[login]'");
                    if($readUserIsset):
                        echo 'errisset';
                    else:
                        create('usuarios',$u);
                        echo $u['login'];
                    endif;
                else:
                    $userId =  mysql_real_escape_string($_POST['id']);
                    $readUserIsset = read('usuarios',"WHERE id != '$userId' AND (email = '$u[email]' OR login = '$u[login]')");
                    if($readUserIsset):
                        echo 'errisset';
                    else:
                        unset($u['cadastro']);
                        update('usuarios',$u,"id = '$userId'");
                        echo $u['login'];
                    endif;
                endif;
            endif;
        break;

        //Consulta e retorna formulário de edição de usuarios
        case 'usuarios_consulta':
            $userid = mysql_real_escape_string($_POST['userid']);
            $readUserEdit = read('usuarios',"WHERE id = '$userid'");
            foreach($readUserEdit as $userEdit);
            echo '<h2>EDITAR USUÁRIO:</h2>';
            echo '<div class="content">';
                echo '<form name="edituser" action="" method="post">';

                    echo '<label>';
                        echo '<span>Nível:</span>';
                        echo '<select name="nivel">';
                            echo '<option ';
                                if($userEdit['nivel'] == '2') echo 'selected="selected" ';
                                echo 'value="2">Admin</option>';
                            echo '<option ';
                                if($userEdit['nivel'] == '1') echo 'selected="selected" ';
                                echo 'value="1">Super Admin</option>';
                        echo '</select>';
                    echo '</label>';

                    echo '<label>';
                        echo '<span>Nome:</span>';
                        echo '<input type="text" name="nome" value="'.$userEdit['nome'].'"/>';
                    echo '</label>';

                    echo '<label>';
                        echo '<span>E-mail:</span>';
                        echo '<input type="text" name="email" value="'.$userEdit['email'].'" />';
                    echo '</label>';

                    echo '<label>';
                        echo '<span>Login:</span>';
                        echo '<input type="text" name="login" value="'.$userEdit['login'].'"/>';
                    echo '</label>';

                    echo '<label>';
                        echo '<span>Senha:</span>';
                        echo '<input type="password" name="pass" value="'.$userEdit['code'].'"/>';
                    echo '</label>';

                    echo '<input type="hidden" name="id" value="'.$userEdit['id'].'" />';

                    echo '<input type="submit" value="Atualizar Usuário" class="btn" />';
                    echo '<img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />';
                echo '</form>';
            echo '</div>';

            echo '<a href="#" class="closemodal j_formclose" id="edituser">X FECHAR</a>';
        break;

        //Deleta usuários
        case 'usuarios_deleta':
            $userId = mysql_real_escape_string($_POST['userid']);
            $readDelUser = read('usuarios',"WHERE id = '$userId'");
            foreach($readDelUser as $delUser);
            if($delUser['nivel'] != '1'):
                delete('usuarios',"id = '$userId'");
            else:
                $readSuper = read('usuarios',"WHERE nivel = '1'");
                $conta = count($readSuper);
                if($conta <= 1):
                    echo 'errsuper';
                else:
                    delete('usuarios',"id = '$userId'");
                endif;
            endif;
        break;
        //INICIA CONFIGURAÇÕES
        //desativa manuteção
        case 'manutencao_desativa':
                $dados = array("manutencao" => '0');
                update('config_manutencao',$dados,"manutencao = '1'");
        break;
        //ativa manutenção
        case 'manutencao_ativa':
                $dados = array("manutencao" => '1');
                update('config_manutencao',$dados,"manutencao = '0'");
        break;
        //atualiza mailserver
        case 'mailserver_atualiza':
            $f['email'] = mysql_real_escape_string($_POST['email']);
            $f['senha'] = mysql_real_escape_string($_POST['senha']);
            $f['porta'] = mysql_real_escape_string($_POST['porta']);
            $f['server'] = mysql_real_escape_string($_POST['server']);

            if(in_array('',$f)){
                echo 'errempty';
            }elseif(!isMail($f['email'])){
                echo 'errmail';
            }else{
                update('config_mailserver',$f,'id = 1');
            }
        break;

        //testa envio de e-mail
        case 'mailserver_teste':
            $readMailServer = read('config_mailserver');
            foreach($readMailServer as $mail);
            $assunto = 'Teste de MailServer';
            $mensagem = 'Seu servidor de emails foi configurado com sucesso. Parabéns.<br/><br/> Enviado em: '.date('d/m/Y H:i:s');
            $sendMail = sendMail($assunto,$mensagem,MAILUSER,SITENAME,MAILUSER,SITENAME);
            if($sendMail):
                echo $mail['email'];
            else:
                echo 'error';
            endif;
            break;

        //atualiza seo e social do site
        case 'seosocial_atualiza':
            $s['titulo'] = mysql_real_escape_string($_POST['titulo']);
            $s['descricao'] = mysql_real_escape_string($_POST['descricao']);
            $s['facebook'] = mysql_real_escape_string($_POST['facebook']);
            $s['twitter'] = mysql_real_escape_string($_POST['twitter']);
            if(in_array('',$s)):
                echo 'errempty';
            else:
                update('config_seosocial',$s,'id = 1');
            endif;
        break;

        //atualiza endereço e telefone
        case 'endtel_atualiza':
            $e['endereco'] = mysql_real_escape_string($_POST['endereco']);
            $e['telefone'] = mysql_real_escape_string($_POST['telefone']);
            if(in_array('',$e)):
                echo 'errempty';
            else:
                update('config_endtel',$e,'id = 1');
            endif;
        break;

        //Cadastra novo tema
        case 'theme_cadastra':
            $t['nome'] = mysql_real_escape_string($_POST['nome']);
            $t['pasta'] = mysql_real_escape_string($_POST['pasta']);
            if(in_array('',$t)):
                echo 'errempty';
            else:
                $t['cadastro'] = date('Y-m-d H:i:s');
                create('config_theme',$t);
            endif;
        break;

        //Theme read
        case 'theme_read':
            $readConfigTheme = read('config_theme',"ORDER BY inuse DESC, cadastro ASC");
            if($readConfigTheme):
                echo '<li class="title">';
                    echo '<span>Tema:</span>';
                    echo '<span>Pasta:</span>';
                    echo '<span>Criado em:</span>';
                    echo '<span>-</span>';
                echo '</li>';
                foreach($readConfigTheme as $configTheme):

                    $pasta = '../../themes/'.$configTheme['pasta'];
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
            endif;
        break;

        //ativa o tema
        case 'theme_ativa':
            $reset = array('inuse' => 0);
            update('config_theme',$reset,"inuse = '1'");

            $themeid = mysql_real_escape_string($_POST['id']);
            $ativa = array('inuse' => 1);
            update('config_theme',$ativa,"id = '$themeid'");

        break;

        //deleta o tema
        case 'theme_deleta':
            $themeid = mysql_real_escape_string($_POST['id']);
            $readTheme = read('config_theme',"WHERE id = '$themeid'");
            if($readTheme):
                foreach($readTheme as $theme);
                if($theme['inuse']):
                    echo 'erractive';
                else:
                    delete('config_theme',"id = '$themeid'");
                endif;
            endif;
        break;

        default:  echo 'Error';
    }
    ob_end_flush();