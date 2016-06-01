<?php
ob_start();
session_start();

require('../../../dts/configs.php');


$acao = mysql_real_escape_string($_POST['acao']);

switch($acao){
    case 'home_getdefault':
        $readDeOlho = read('posts',"WHERE status = '1' ORDER BY cadastro DESC LIMIT 3,4");
        if($readDeOlho):
            foreach($readDeOlho as $deOlho):

            $default = '../../../themes/blue/images/blue.jpg';
            $capa = '../../../uploads/'.$deOlho['capa'];
            $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

                $i++;
                echo '<li'; if($i%2==0) echo ' style="float:right;"'; echo '>';
                echo '<img style="display: block;" alt="'.$deOlho['title'].'" title="'.$deOlho['title'].'" src="'.BASE.'/tim.php?src='.$capa.'" width="100" height="100"/>';
                echo '<a title="'.$deOlho['titulo'].'" href="'.BASE.'/ver/'.$deOlho['url'].'">'.lmWord($deOlho['titulo'],42).'</a>';
                echo '<p>'.lmWord($deOlho['conteudo'],'70').'</p>';
                echo '</li>';
            endforeach;
        endif;
    break;

    case 'home_getdestaque':
        $qr = "SELECT posts.*,(SELECT COUNT(comments.id)FROM comments WHERE comments.post_id = posts.id) AS total_comment FROM posts ORDER BY total_comment DESC LIMIT 4";
        $ex = mysql_query($qr) or die(mysql_error());

        while($destaque = mysql_fetch_assoc($ex)):

            $default = '../../../themes/blue/images/blue.jpg';
            $capa = '../../../uploads/'.$destaque['capa'];
            $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

            $i++;
            echo '<li'; if($i%2==0) echo ' style="float:right;"'; echo '>';
                echo '<img style="display: block;" alt="'.$destaque['title'].'" title="'.$destaque['title'].'" src="'.BASE.'/tim.php?src='.$capa.'" width="100" height="100"/>';
                echo '<a title="'.$destaque['titulo'].'" href="'.BASE.'/ver/'.$destaque['url'].'">'.lmWord($destaque['titulo'],42).'</a>';
                echo '<p>'.lmWord($destaque['conteudo'],'70').'</p>';
            echo '</li>';
        endwhile;
    break;

    case 'home_getdeolho':
        $readDeOlho = read('posts',"WHERE status = '1' ORDER BY views DESC, cadastro DESC LIMIT 4");
        if($readDeOlho):
            foreach($readDeOlho as $deOlho):

            $default = '../../../themes/blue/images/blue.jpg';
            $capa = '../../../uploads/'.$deOlho['capa'];
            $capa = (file_exists($capa) && !is_dir($capa) ? $capa : $default);

                $i++;
                echo '<li'; if($i%2==0) echo ' style="float:right;"'; echo '>';
                    echo '<img style="display: block;" alt="'.$deOlho['title'].'" title="'.$deOlho['title'].'" src="'.BASE.'/tim.php?src='.$capa.'" width="100" height="100"/>';
                    echo '<a title="'.$deOlho['titulo'].'" href="'.BASE.'/ver/'.$deOlho['url'].'">'.lmWord($deOlho['titulo'],42).'</a>';
                    echo '<p>'.lmWord($deOlho['conteudo'],'70').'</p>';
                echo '</li>';
            endforeach;
        endif;
    break;

    //PAGINA CATEGORIAS
    case 'cat_paginar':
        $page = mysql_real_escape_string($_POST['page']);
        $page = preg_replace('/[^0-9]*/','',$page);
        $page = ($page ? $page : 1);

        $maximo = 12;
        $inicio = ($page * $maximo) - $maximo;

        $catid = $_SESSION['useracess']['catid'];

        $readArt = read('posts',"WHERE status = '1' AND categoria = '$catid' ORDER BY cadastro DESC LIMIT $inicio,$maximo");
        echo '<ul>';
        foreach($readArt as $art):
            $i++;

            $artdefault = '../../../themes/blue/images/blue.jpg';
            $artcapa = '../../../uploads/'.$art['capa'];
            $artcapa = (file_exists($artcapa) && !is_dir($artcapa) ? $artcapa : $artdefault);

            echo '<li'; if($i%4==0) echo ' style="float:right; margin-right:0"'; echo '>';
                echo '<img style="display: block;" alt="'.$art['titulo'].'" title="'.$art['titulo'].'" src="'.BASE.'/tim.php?src='.$artcapa.'&w=220&h=200" width="220" heigth="200"/>';
                echo '<div class="licontent">';
                    echo '<a title="'.$art['titulo'].'" href="'.BASE.'/ver/'.$art['url'].'">'.lmWord($art['titulo'],62).'</a>';
                echo '</div>';
            echo '</li>';
        endforeach;
        echo '</ul>';

        echo '<div class="clear"></div>';

        echo '<div class="paginator">';
            $link = BASE.'/categoria/'.$cat['url'].'/';
            readPaginator('posts',"WHERE status = '1' AND categoria = '$catid' ORDER BY cadastro DESC",$maximo,$link, $page);
        echo '</div>';
    break;

    //REALIZA PESQUISA
    case 'search_get':
        $search = mysql_real_escape_string($_POST['pesquisa']);
        $searchL = mb_strtolower($search,'UTF-8');
        $searchU = mb_strtoupper($search,'UTF-8');
        $readArt = read('posts',"WHERE status = '1' AND (titulo LIKE '%$searchL%' OR titulo LIKE '%$searchU%') ORDER BY cadastro DESC LIMIT 12");

        if(!$readArt):
            echo '<div class="notfound">';
                echo '<h3>Desculpe, sua pesquisa não encontrou resultados!</h3>';
                echo '<p>Sua pesquisa por <span class="j_psearch">'.$search.'</span> não retornou resultados! Talvez você queira tentar outros termos. Uma pesquisa mais ampla e com menos palavras tem mais chances de retornar resultados!</p>';
                echo '<p class="att">Atenciosamente '.SITENAME.'!</p>';
            echo '</div>';
        else:
            echo '<ul>';
            foreach($readArt as $art):
                $i++;

                $artdefault = '../../../themes/blue/images/blue.jpg';
                $artcapa = '../../../uploads/'.$art['capa'];
                $artcapa = (file_exists($artcapa) && !is_dir($artcapa) ? $artcapa : $artdefault);

                echo '<li'; if($i%4==0) echo ' style="float:right; margin-right:0"'; echo '>';
                    echo '<img style="display: block;" alt="'.$art['titulo'].'" title="'.$art['titulo'].'" src="'.BASE.'/tim.php?src='.$artcapa.'&w=220&h=200" width="220" heigth="200"/>';
                    echo '<div class="licontent">';
                        echo '<a title="'.$art['titulo'].'" href="'.BASE.'/ver/'.$art['url'].'">'.lmWord($art['titulo'],62).'</a>';
                    echo '</div>';
                echo '</li>';
            endforeach;
            echo '</ul>';

            echo '<div class="clear"></div>';

            echo '<div class="paginator">';
                $link = BASE.'/pesquisa/'.urlencode($search).'/';
                readPaginator('posts',"WHERE status = '1' AND (titulo LIKE '%$searchL%' OR titulo LIKE '%$searchU%') ORDER BY cadastro DESC",'12',$link, '1');
            echo '</div>';
        endif;
    break;

    //PAGINA SEARCH
    case 'search_paginar':
        $page = mysql_real_escape_string($_POST['page']);
        $page = preg_replace('/[^0-9]*/','',$page);
        $page = ($page ? $page : 1);

        $maximo = 12;
        $inicio = ($page * $maximo) - $maximo;

        $search = mysql_real_escape_string($_POST['pesquisa']);
        $searchL = mb_strtolower($search,'UTF-8');
        $searchU = mb_strtoupper($search,'UTF-8');
        $readArt = read('posts',"WHERE status = '1' AND (titulo LIKE '%$searchL%' OR titulo LIKE '%$searchU%') ORDER BY cadastro DESC LIMIT $inicio,$maximo");

        echo '<ul>';
        foreach($readArt as $art):
            $i++;

            $artdefault = '../../../themes/blue/images/blue.jpg';
            $artcapa = '../../../uploads/'.$art['capa'];
            $artcapa = (file_exists($artcapa) && !is_dir($artcapa) ? $artcapa : $artdefault);

            echo '<li'; if($i%4==0) echo ' style="float:right; margin-right:0"'; echo '>';
                echo '<img style="display: block;" alt="'.$art['titulo'].'" title="'.$art['titulo'].'" src="'.BASE.'/tim.php?src='.$artcapa.'&w=220&h=200" width="220" heigth="200"/>';
                echo '<div class="licontent">';
                    echo '<a title="'.$art['titulo'].'" href="'.BASE.'/ver/'.$art['url'].'">'.lmWord($art['titulo'],62).'</a>';
                echo '</div>';
            echo '</li>';
        endforeach;
        echo '</ul>';

        echo '<div class="clear"></div>';

        echo '<div class="paginator">';
            $link = BASE.'/pesquisa/'.urlencode($search).'/';
            readPaginator('posts',"WHERE status = '1' AND (titulo LIKE '%$searchL%' OR titulo LIKE '%$searchU%') ORDER BY cadastro DESC",$maximo,$link, $page);
        echo '</div>';
    break;

    //CARREGA CONTATO
    case 'load_contato':
           if($_SESSION['useracess']['sendmail'] && $_SESSION['useracess']['sendmail'] > time()):
               $timeAtual = time();
               $timeFalta = $_SESSION['useracess']['sendmail'];
               $tempoFalta =  ($timeFalta - $timeAtual) / 60;
               $tentenovamente = ($tempoFalta > 1 ? round($tempoFalta).' minutos':($tempoFalta < 1 ? substr($tempoFalta,2,2).' segundos' : round($tempoFalta).' minuto'));

               echo '<h2>Fale Conosco:</h2>';
               echo '<h3>Desculpe, acabamos de receber uma mensagem sua, não será possível enviar outra mensagem agora!</h3>';
               echo '<p class="alert">Por favor, volte a tentar em <strong>'.$tentenovamente.'</strong>, e ai sim poderemos receber sua nova mensagem. <strong>Obrigado!</strong></p>';
           else:
               echo ' <h2>Fale Conosco:</h2>';
               echo ' <p>Preencha e envie o formulário abaixo, teremos o prazer em responder o mais breve!</p>';
                echo '<form name="contato" action="" method="post">';
                   echo ' <label>';
                      echo '  <span>Nome:</span>';
                       echo ' <input type="text" name="nome" />';
                   echo ' </label>';
                    echo '<label>';
                       echo ' <span>E-mail:</span>';
                       echo ' <input type="text" name="email" />';
                    echo '</label>';
                    echo '<label>';
                        echo '<span>Telefone:</span>';
                        echo '<input class="formFone" type="text" name="telefone" />';
                    echo '</label>';
                    echo '<label>';
                        echo '<span>Mensagem:</span>';
                        echo '<textarea name="mensagem" rows="3"></textarea>';
                    echo '</label>';
                    echo '<input type="submit" value="Enviar Contato" class="btn" />';
                    echo '<img class="imgload" src="'.BASE.'/themes/blue/images/loader2.gif" alt="Carregando..." title="Carregando...">';
                echo '<div class="clear"></div>';
                echo '</form>';
           endif;
        echo '<a href="#" class="closecontato">FECHAR</a>';
    break;

    //ENVIA CONTATO
    case 'send_contato':
        $f['nome'] = mysql_real_escape_string(strip_tags(trim($_POST['nome'])));
        $f['email'] = mysql_real_escape_string(strip_tags(trim($_POST['email'])));
        $f['telefone'] = mysql_real_escape_string(strip_tags(trim($_POST['telefone'])));
        $f['mensagem'] = mysql_real_escape_string(strip_tags(trim($_POST['mensagem'])));

        if(in_array('',$f)){
            echo 'errdados';
        }elseif(!isMail($f['email'])){
            echo 'erremail';
        }else{
            $assunto = 'Recebemos sua mensagem';
            $mensagem = '<p>Olá '.$f['nome'].', recebemos sua mensagem! Confira abaixo:</p><hr/><p>'.$f['mensagem'].'</p><hr/><p>Nome: '.$f['nome'].'<br>Email: '.$f['email'].'<br>Telefone: '.$f['telefone'].'<br>Enviada em: '.date('d/m/Y H:i').'</p><hr/><p>Atenciosamente '.SITENAME.'</p>';
            sendMail($assunto,$mensagem,MAILUSER,SITENAME,$f['email'],$f['nome'], MAILUSER, SITENAME);

            $assunto = 'Nova mensagem de '.$f['nome'];
            $mensagem = '<p>'.$f['mensagem'].'</p><hr/><p>Nome: '.$f['nome'].'<br>Email: '.$f['email'].'<br>Telefone: '.$f['telefone'].'<br>Enviada em: '.date('d/m/Y H:i').'</p>';
            sendMail($assunto,$mensagem,MAILUSER,SITENAME,MAILUSER,SITENAME, $f['email'], $f['nome']);
            echo $f['nome'];

            $_SESSION['useracess']['sendmail'] = time() + 60*10;
        }
    break;

    //INICIA COMENTARIOS
    //Carregar a box
    case 'load_comment':
        if($_COOKIE['MyContentComment']):
            $email = base64_decode($_COOKIE['MyContentComment']);
            $readEmail = read('comments',"WHERE email = '$email' ORDER BY data DESC LIMIT 1");
            if($readEmail) foreach($readEmail as $mail);
        endif;

        $checkId = mysql_real_escape_string(base64_decode($_POST['postid']));
        if($checkId != $_SESSION['useracess']['postid']):
            echo 'confuse';
        else:
            echo '<form name="addcomment" style="display: none;" action="" method="post">';
                echo '<h3>Deixe seu comentário:</h3>';
                echo '<label>';
                    echo '<span class="field">Nome:</span>';
                    echo '<input type="text" name="nome" value="'.$mail['nome'].'">';
                echo '</label>';
                echo '<label>';
                    echo '<span class="field">E-mail:</span>';
                    echo '<input type="text" name="email" value="'.$mail['email'].'">';
                echo '</label>';
                echo '<label>';
                    echo '<span class="field">Diga:</span>';
                    echo '<textarea name="comentario" rows="3"></textarea>';
                echo '</label>';
                echo '<input type="hidden" value="'.base64_encode($checkId).'" name="ispost">';
                echo '<input type="submit" value="Enviar Comentário" class="btn">';
                echo '<img class="imgload" style="display: none;" src="'.BASE.'/themes/blue/images/loader2.gif" alt="Carregando..." title="Carregando...">';
                echo '<a href="#closecomment" class="closecomment" title="Fechar">X Fechar</a>';
            echo '</form>';
        endif;
    break;

    //envia comentário
    case 'send_comment':
        $c['post_id'] = $_SESSION['useracess']['postid'];
        $c['nome'] = mysql_real_escape_string(strip_tags(trim($_POST['nome'])));
        $c['email'] = mysql_real_escape_string(strip_tags(trim($_POST['email'])));
        $c['comentario'] = mysql_real_escape_string(strip_tags(trim($_POST['comentario'])));
        $c['comentario'] = str_replace('\r\n','<p></p>',$c['comentario']);
        $c['comentario'] = '<p>'.$c['comentario'].'</p>';
        $c['comentario'] = str_replace('<p></p>','',$c['comentario']);
        $c['data'] = date('Y-m-d H:i:s');

        $checkId = mysql_real_escape_string(base64_decode($_POST['ispost']));
        if($checkId != $_SESSION['useracess']['postid']):
            echo 'confuse';
        elseif(in_array('',$c)):
            echo 'errdados';
        elseif(!isMail($c['email'])):
            echo 'errmail';
        else:
            echo $c['nome'];
            create('comments',$c);
            $timeend = time() + 60*60*24*30;
            setcookie('MyContentComment',base64_encode($c['email']),$timeend,'/');

            $readTopic = read('posts',"WHERE id = '$checkId'");
            if($readTopic) foreach ($readTopic as $to);
           
            $mAssunto = 'Olá Francisval, novo comentário de <strong>'.$c['nome'].'</strong> em <strong>'.$to['titulo'].'</strong>';

            $mensagem = geraEmail(SITENAME,BASE.'/themes/blue/images/logotype.png',BASE.'/admin','Moderar Comentário!',$mAssunto,'<p>'.$c['comentario'].'</p>',$checkId);

            sendMail('Novo comentário para moderar',$mensagem,MAILUSER,SITENAME,MAILUSER,SITENAME);

        endif;
    break;

    //carrega resposta
    case 'load_resposta':
       $_SESSION['useracess']['responder'] = mysql_real_escape_string($_POST['commnetid']);
       if($_COOKIE['MyContentComment']):
           $email = base64_decode($_COOKIE['MyContentComment']);
           $readEmail = read('comments',"WHERE email = '$email' ORDER BY data DESC LIMIT 1");
           if($readEmail) foreach($readEmail as $mail);
       endif;

       $postid = $_SESSION['useracess']['postid'];
       $comid = base64_decode($_SESSION['useracess']['responder']);
       $readComment = read('comments',"WHERE post_id = '$postid' AND id = '$comid'");

       if(!$readComment):
            echo 'confuse';
       else:
           foreach($readComment as $com);
           if($com['isadmin']):
               $readAdmin = read('usuarios',"WHERE id = '$com[isadmin]'");
               if($readAdmin) foreach($readAdmin as $a);
               $com['nome'] = $a['nome'];
               $com['email'] = $a['email'];
           endif;
           $atts = array('alt'=>'Avatar de '.$com['nome'],'title'=>'Avatar de '.$com['nome'],'class'=>'radius');
           $avatar = gravatar($com['email'],60,'mm','g',true,$atts);
           echo ' <form name="addresposta" class="formresponder" action="" method="post">';
                echo ' <div class="user">';
                    echo $avatar;
                    echo '<div class="info">Responda o comentário de <span>'.$com['nome'].'</span></div>';
                echo '</div>';
                echo '<h3>Responda o comentário:</h3>';
                echo '<label>';
                    echo '<span class="field">Nome:</span>';
                    echo '<input type="text" name="nome" value="'.$mail['nome'].'">';
                    echo '</label>';
                echo '<label>';
                        echo '<span class="field">E-mail:</span>';
                        echo '<input type="text" name="email" value="'.$mail['email'].'">';
                echo '</label>';
                echo '<label>';
                    echo '<span class="field">Diga:</span>';
                    echo '<textarea name="comentario" rows="3"></textarea>';
                    echo '</label>';
                echo '<input type="hidden" name="ispost" value="'.base64_encode($postid).'">';
                echo '<input type="hidden" name="isresp" value="'.base64_encode($comid).'">';
                echo '<input type="submit" value="Enviar Resposta" class="btn">';
                echo '<img class="imgload" style="display:none;" src="'.BASE.'/themes/blue/images/loader2.gif" alt="Carregando..." title="Carregando...">';
                echo '<a href="#closecomment" class="closecomment" title="Fechar">X Fechar</a>';
            echo '</form>';
       endif;
    break;

    //envia resposta
    case 'send_resp':
        $c['post_id'] = $_SESSION['useracess']['postid'];
        $c['nome'] = mysql_real_escape_string(strip_tags(trim($_POST['nome'])));
        $c['email'] = mysql_real_escape_string(strip_tags(trim($_POST['email'])));
        $c['comentario'] = mysql_real_escape_string(strip_tags(trim($_POST['comentario'])));
        $c['comentario'] = str_replace('\r\n','<p></p>',$c['comentario']);
        $c['comentario'] = '<p>'.$c['comentario'].'</p>';
        $c['comentario'] = str_replace('<p></p>','',$c['comentario']);
        $c['data'] = date('Y-m-d H:i:s');

        $respId = base64_decode($_SESSION['useracess']['responder']);
        $readResp = read('comments',"WHERE id = '$respId'");
        if($readResp) foreach($readResp as $re);
        $c['resp_id'] = ($re['resp_id'] ? $re['resp_id'] : $re['id']);

        $ispost = mysql_real_escape_string(base64_decode($_POST['ispost']));
        $isresp = mysql_real_escape_string(base64_decode($_POST['isresp']));

        if($c['post_id'] != $ispost || $respId != $isresp):
            echo 'confuse';
        elseif(in_array('',$c)):
            echo 'errdados';
        elseif(!isMail($c['email'])):
            echo 'errmail';
        else:
            echo $c['nome'];
            create('comments',$c);
            $timeend = time() + 60*60*24*30;
            setcookie('MyContentComment',base64_encode($c['email']),$timeend,'/');
            $upstatus = array('status'=>'1');
            update('comments',$upstatus,"id = '$c[resp_id]'");

            $readTopic = read('posts',"WHERE id = '$ispost'");
            if($readTopic) foreach ($readTopic as $to);

            $mAssunto = 'Olá Francisval, nova resposta de <strong>'.$c['nome'].'</strong> em <strong>'.$to['titulo'].'</strong>';

            $mensagem = geraEmail(SITENAME,BASE.'/themes/blue/images/logotype.png',BASE.'/admin','Moderar Resposta!',$mAssunto,'<p>'.$c['comentario'].'</p>',$ispost);

            sendMail('Nova resposta para moderar',$mensagem,MAILUSER,SITENAME,MAILUSER,SITENAME);
        endif;
    break;

    //Carrega moderates
    case 'load_moderate':
        if($_COOKIE['MyContentComment']):
            $email = base64_decode($_COOKIE['MyContentComment']);
            $postid	  = $_SESSION['useracess']['postid'];
            $readMail = read('comments',"WHERE post_id = '$postid' AND status IS NULL AND email = '$email' ORDER BY data DESC");
            if($readMail) foreach($readMail as $mail):
                $matts = array('alt' => 'Avatar de '.$mail['nome'],'title' => 'Avatar de '.$mail['nome'],'class' => 'radius', 'style' => 'display:block');
                $mavatar = gravatar($mail['email'],60,'mm','g',true,$matts);
				echo '<p class="alert">Olá <strong>'.$mail['nome'].'</strong>, seu comentário neste artigo esta sendo moderado, em breve liberaremos!</p>';
				echo '<div class="user">';
					echo $mavatar;
					echo '<div class="info"><strong>por</strong> <span>VOCÊ!</span> <strong>em</strong> <span>'.date('d/m/y H:i',strtotime($mail['data'])).'</span></div>';
				echo '</div>';
				echo $mail['comentario'];
            endforeach;
        endif;
        break;


    //count load more
    case 'loadmore_btn':
        sleep(1);
        $postid = mysql_real_escape_string(base64_decode($_POST['postid']));
        $numcom = mysql_real_escape_string($_POST['numcom']);
        $readCount = read('comments',"WHERE post_id = '$postid' AND status >= '1' AND resp_id IS NULL LIMIT $numcom,6");
        echo count($readCount);
        break;


    //loade more comments
    case 'loadmore_com':
        $postid = mysql_real_escape_string(base64_decode($_POST['postid']));
        $numcom = mysql_real_escape_string($_POST['numcom']);
        $readComment = read('comments',"WHERE post_id = '$postid' AND status >= '1' AND resp_id IS NULL ORDER BY data DESC LIMIT $numcom,5");
        if($readComment):
            foreach($readComment as $com):
                $atts = array('alt' => 'Avatar de '.$com['nome'],'title' => 'Avatar de '.$com['nome'],'class' => 'radius', 'style' => 'display:block');
                $avatar = gravatar( $com['email'],60,'mm','g',true,$atts);

                echo '<li class="li comp" style="display:none;">';
				echo '<div class="user">';
					echo $avatar;
					echo '<div class="info"><strong>por</strong> <span>'.$com['nome'].'</span> <strong>em</strong> <span>'.date('d/m/y H:i',strtotime($com['data'])).'</span></div>';
				echo '</div>';
				echo $com['comentario'];
				echo '<a href="#" title="Responda '.$com['nome'].'" class="addresp" id="'.base64_encode($com['id']).'">Responder</a>';


                $postid	  = $_SESSION['useracess']['postid'];
                $readResp = read('comments',"WHERE status = '2' AND post_id = '$postid' AND resp_id = '$com[id]' ORDER BY data ASC");
                if($readResp):
                    //add and admin and null
                    echo '<ul class="resposta">';
                    foreach($readResp as $resp):
                        if($resp['isadmin']):
                            $readAdmin = read('usuarios',"WHERE id = '$resp[isadmin]'");
                            if($readAdmin) foreach($readAdmin as $a);
                            $resp['nome']  = $a['nome'];
                            $resp['email'] = $a['email'];
                        endif;

                        $cc++;
                        $attsR = array('alt' => 'Avatar de '.$resp['nome'],'title' => 'Avatar de '.$resp['nome'],'class' => 'radius', 'style' => 'display:block');
                        $avatarR = gravatar( $resp['email'],42,'mm','g',true,$atts);

                        echo '<li'; if($resp['isadmin']) echo ' class="admin" '; elseif($cc%2==0) echo ' class="add" '; echo '>';
                            echo '<div class="user">';
                                    echo $avatarR;
                                    echo '<div class="info"><strong>por</strong> <span>'.$resp['nome'].'</span> <strong>em</strong> <span>'.date('d/m/y H:i',strtotime($resp['data'])).'</span></div>';
                            echo '</div>';
							echo $resp['comentario'];
                            echo '<a href="#" title="Responda '.$resp['nome'].'" class="addresp" id="'.base64_encode($resp['id']).'">Responder</a>';
						echo '</li>';
                    endforeach;
                    echo '</ul>';
                endif;
                echo '</li>';
            endforeach;
        endif;
    break;
}

ob_end_flush();