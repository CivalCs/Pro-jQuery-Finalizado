<?php
/*****************************
FUNÇÃO DO PRO PHP
FAZ A NAVEGAÇÃO AMIGÁVEL
*****************************/
function getHome(){
    $url = $_GET['url'];
    $url = explode('/', $url);
    $url[0] = ($url[0] == NULL ? 'index' : $url[0]);

    if(file_exists('themes/'.THEME.'/'.$url[0].'.php')){
        require_once('themes/'.THEME.'/'.$url[0].'.php');
    }elseif(file_exists('themes/'.THEME.'/'.$url[0].'/'.$url[1].'.php')){
        require_once('themes/'.THEME.'/'.$url[0].'/'.$url[1].'.php');
    }else{
        require_once('themes/'.THEME.'/404.php');
    }
}
/*****************************
FUNÇÃO DO PRO PHP
SETA URL DA HOME
*****************************/
function setHome(){
	echo BASE;	
}
/*****************************
FUNÇÃO DO PRO PHP
INCLUE ARQUIVOS
*****************************/
function setArq($nomeArquivo){
	if(file_exists($nomeArquivo.'.php')){
		include($nomeArquivo.'.php');
	}else{
		echo 'Erro ao incluir <strong>'.$nomeArquivo.'.php</strong>, arquivo ou caminho não conferem!';	
	}
}
/*****************************
FUNÇÃO DO PRO PHP
GERA RESUMOS
*****************************/
function lmWord($string, $words = '100'){
	$string 	= strip_tags($string);
	$count		= strlen($string);
	
	if($count <= $words){
		return $string;	
	}else{
		$strpos = strrpos(substr($string,0,$words),' ');
		return substr($string,0,$strpos).'...';
	}
	
}
/*****************************
FUNÇÃO DO PRO PHP
TRANFORMA STRING EM URL
*****************************/
function setUri($string){
	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
	$b = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';	
	$string = utf8_decode($string);
	$string = strtr($string, utf8_decode($a), $b);
	$string = strip_tags(trim($string));
	$string = str_replace(" ","-",$string);
	$string = str_replace(array("-----","----","---","--"),"-",$string);
	return strtolower(utf8_encode($string));
}
/*****************************
FUNÇÃO DO PRO PHP
SOMA VISITAS
*****************************/
function setViews($topicoId){
    $topicoId = mysql_real_escape_string($topicoId);
    $readArtigo = read('posts',"WHERE id = '$topicoId'");

    foreach($readArtigo as $artigo);
    $views = $artigo['views'];
    $views = $views +1;
    $dataViews = array(
        'views' => $views
    );
    update('posts',$dataViews,"id = '$topicoId'");
}

/*****************************
FUNÇÃO DO PRO PHP
FUNÇÃO DE CADASTRO NO BANCO
*****************************/
function create($tabela, array $datas){
	$fields = implode(", ",array_keys($datas));
	$values = "'".implode("', '",array_values($datas))."'";			
	$qrCreate = "INSERT INTO {$tabela} ($fields) VALUES ($values)";
	$stCreate = mysql_query($qrCreate) or die ('Erro ao cadastrar em '.$tabela.' '.mysql_error());
	
	if($stCreate){
		return true;
	}
}	
/*****************************
FUNÇÃO DO PRO PHP
FUNÇÃO DE LEITURA NO BANCO
*****************************/
function read($tabela, $cond = NULL){		
	$qrRead = "SELECT * FROM {$tabela} {$cond}";
	$stRead = mysql_query($qrRead) or die ('Erro ao ler em '.$tabela.' '.mysql_error());
	$cField = mysql_num_fields($stRead);
	for($y = 0; $y < $cField; $y++){
		$names[$y] = mysql_field_name($stRead,$y);
	}
	for($x = 0; $res = mysql_fetch_assoc($stRead); $x++){
		for($i = 0; $i < $cField; $i++){
			$resultado[$x][$names[$i]] = $res[$names[$i]];
		}
	}
	return $resultado;
}	
/*****************************
FUNÇÃO DO PRO PHP
FUNÇÃO DE EDIÇÃO NO BANCO
*****************************/	
function update($tabela, array $datas, $where){
	foreach($datas as $fields => $values){
		$campos[] = "$fields = '$values'";
	}
	
	$campos = implode(", ",$campos);
	$qrUpdate = "UPDATE {$tabela} SET $campos WHERE {$where}";
	$stUpdate = mysql_query($qrUpdate) or die ('Erro ao atualizar em '.$tabela.' '.mysql_error());

	if($stUpdate){
		return true;	
	}
	
}	
/*****************************
FUNÇÃO DO PRO PHP
FUNÇÃO DE DELETAR NO BANCO
*****************************/
function delete($tabela, $where){
	$qrDelete = "DELETE FROM {$tabela} WHERE {$where}";
	$stDelete = mysql_query($qrDelete) or die ('Erro ao deletar em '.$tabela.' '.mysql_error());
}
/*****************************
FUNÇÃO DO PRO PHP
ENVIA O EMAIL
*****************************/	
function sendMail($assunto,$mensagem,$remetente,$nomeRemetente,$destino,$nomeDestino, $reply = NULL, $replyNome = NULL){
	
	require_once('mail/class.phpmailer.php'); //Include pasta/classe do PHPMailer
	
	$mail = new PHPMailer(); //INICIA A CLASSE
	$mail->IsSMTP(); //Habilita envio SMPT
	$mail->SMTPAuth = true; //Ativa email autenticado
	$mail->IsHTML(true);
	
	$mail->Host = MAILHOST; //Servidor de envio
	$mail->Port = MAILPORT; //Porta de envio
	$mail->Username = MAILUSER; //email para smtp autenticado
	$mail->Password = MAILPASS; //seleciona a porta de envio
	
	$mail->From = utf8_decode($remetente); //remtente
	$mail->FromName = utf8_decode($nomeRemetente); //remtetene nome
	
	if($reply != NULL){
		$mail->AddReplyTo(utf8_decode($reply),utf8_decode($replyNome));	
	}
	
	$mail->Subject = utf8_decode($assunto); //assunto
	$mail->Body = utf8_decode($mensagem); //mensagem
	$mail->AddAddress(utf8_decode($destino),utf8_decode($nomeDestino)); //email e nome do destino
	
	if($mail->Send()){
		return true;
	}else{
		return false;
	}
}

/*****************************
FUNÇÃO PRO jQUERY
GERA HTML PARA ENVIO DE E-MAILS
*****************************/	
function geraEmail($mSender,$mLogo,$mLink,$mLinkText,$mAssunto,$mContent,$postid = NULL){
	$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	
	<style type="text/css">
		body{background:#09F;}
		table{padding:10px;}
		table td{padding:20px; background:#fff;}
		table td p{font:normal 14px Arial, Helvetica, sans-serif; color:#333; display:block; margin:20px 0;}		
		.head td{padding:0 0 20px 0; background:#09F; text-transform:uppercase; color:#fff; font-size:14px; font-weight:600; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;}
		.assunto td{font:20px Arial, Helvetica, sans-serif; text-transform:uppercase; color:#09F; border-bottom:5px solid #eee;}
		.content td{padding:0 20px;}		
		.topic td{padding:20px; background:#eee;}
		.topic td img{float:left; margin-right:20px;}
		.topic td a{font:normal 18px Arial, Helvetica, sans-serif; color:#069;}
		.topic td p{display:block; font:normal 12px Arial, Helvetica, sans-serif; color:#666; margin:0; margin-top:10px;}		
		.linktr td{background:#eee;}
		.linktr td a{font:bold 14px Arial, Helvetica, sans-serif; text-transform:uppercase; color:#09F;}
		.bottom td{border-top:5px solid #09F; padding:5px 10xp; text-align:center; font:200 14px "Courier New", Courier, monospace; color:#666; text-transform:uppercase;}
	</style>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>'.strip_tags($mSender).' - '.strip_tags($mAssunto).'</title>
	</head>
	<body>
	
	<table width="680" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr class="head">
		<td><img width="200px" src="'.$mLogo.'" alt="'.$mSender.'" title="'.$mSender.'" /></td>
		<td align="right" valign="middle" width="100%" style="font-size:20px;">'.date('d/m/Y H:i:s').'</td>
	  </tr>
	  <tr class="assunto">
		<td colspan="2">'.$mAssunto.'</td>
	  </tr>';	
	
	  if($postid):
	  	$readPost = read('posts',"WHERE id = '$postid'");
		foreach($readPost as $post);
		$body .= '<tr class="topic">
				<td><img src="'.BASE.'/tim.php?src=uploads/'.$post['capa'].'&w=200&h=120" width="200"></td>
				<td style="padding-left:0" valign="top"><a href="'.BASE.'/ver/'.$post['url'].'">'.$post['titulo'].'</a><p>'.lmWord($post['conteudo'],260).'...</p></td>
			</tr>';
	  endif;	
				  
	  $body .= '
	  <tr class="content">
		<td colspan="2">'.$mContent.'</td>
	  </tr>
	  <tr class="linktr">
		<td colspan="2" align="center"><a href="'.$mLink.'" title="'.$mLinkText.'">'.$mLinkText.'</a></td>
	  </tr>
	  <tr class="bottom">
		<td colspan="2">Este e-mail foi enviado automaticamente por nosso sistema de gestÃ£o<br>Atenciosament '.$mSender.' em: '.date('d/m/Y').' Ã s '.date('H:i').'hs</td>
	  </tr>
	</table>
			
	</body></html>';	
	return $body;
}

/*****************************
FUNÇÃO DO PRO PHP
Paginação de resultados
*****************************/
function readPaginator($tabela, $cond, $maximos, $link, $pag, $width = NULL, $maxlinks = 4, $type = NULL, $div = NULL){
	$readPaginator = read("$tabela","$cond");
	$total = count($readPaginator);
	if($total > $maximos){
		$paginas = ceil($total/$maximos);
		if($width && $div){
			echo '<div class="paginator" style="width:'.$width.'">';
		}elseif($div){
			echo '<div class="paginator">';
		}
        if($type != 'n'): echo '<a href="'.$link.'1">Primeira Página</a>'; endif;
		for($i = $pag - $maxlinks; $i <= $pag - 1; $i++){
			if($i >= 1){
				echo '<a href="'.$link.$i.'">'.$i.'</a>';
			}
		}
		echo '<span class="atv">'.$pag.'</span>';
		for($i = $pag + 1; $i <= $pag + $maxlinks; $i++){
			if($i <= $paginas){
				echo '<a href="'.$link.$i.'">'.$i.'</a>';
			}
		}
        if($type != 'n'): echo '<a href="'.$link.$paginas.'">Última Página</a>'; endif;
		if($div): echo '</div>'; endif;
	}
}
/*****************************
FUNÇÃO DO PRO PHP
IMAGE UPLOAD
*****************************/
function uploadImage($tmp, $nome, $width, $pasta){
	$ext = substr($nome,-3);
	
	switch($ext){
		case 'jpg': $img = imagecreatefromjpeg($tmp); break;
		case 'png': $img = imagecreatefrompng($tmp); break;
		case 'gif': $img = imagecreatefromgif($tmp); break;	
	}		
	$x = imagesx($img);
	$y = imagesy($img);
	$height = ($width*$y) / $x;
	$nova   = imagecreatetruecolor($width, $height);
	
	imagealphablending($nova,false);
	imagesavealpha($nova,true);
	imagecopyresampled($nova, $img, 0, 0, 0, 0, $width, $height, $x, $y);

	switch($ext){
		case 'jpg': imagejpeg($nova, $pasta.$nome, 100); break;
		case 'png': imagepng($nova, $pasta.$nome); break;
		case 'gif': imagegif($nova, $pasta.$nome); break;	
	}
	imagedestroy($img);
	imagedestroy($nova);
}
/*****************************
FUNÇÃO DO PRO PHP
FORMATA DATA EM TIMESTAMP
*****************************/	
function formDate($data){
	$timestamp = explode(" ",$data);
	$getData = $timestamp[0];
	$getTime = $timestamp[1];
	
		$setData = explode('/',$getData);
		$dia = $setData[0];
		$mes = $setData[1];
		$ano = $setData[2];
		
	if(!$getTime):
		$getTime = date('H:i:s');
	endif;
		
	$resultado = $ano.'-'.$mes.'-'.$dia.' '.$getTime;
	
	return $resultado;
	
}
/*****************************
FUNÇÃO DO PRO PHP
VALIDA O EMAIL
*****************************/	
function isMail($email){
    if(preg_match('/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/',$email)){
        return true;
    }else{
        return false;
    }
}

/*****************************
FUNÇÃO DO GRAVATAR
PEGA AVATAR DE USUARIOS
 *****************************/
function gravatar( $email, $s = 180, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}
/*****************************
FUNÇÃO CONTROLE DE MANUTENÇÃO
 *****************************/
function manutencao(){
    $readMain = read('config_manutencao');
    foreach($readMain as $main);

    if($main['manutencao']):
        if(!$_SESSION['userlogin']['id']):
            header('Location: '.BASE.'/manutencao.html');
        else:
            $divmanu = '
			<div class="ismanutencao" style="position:fixed; border-bottom:5px solid #460000; left:0px; top:0px; z-index:999; width:100%; text-align:center; text-transform:uppercase; color:#fff; padding:10px; background:#900">
				Atenção: Módulo de manutenção ativo. Somente administradores podem acessar o site!
				<a style="font-weight:bold; font-size:12px; padding:5px 10px; margin-left:10px; background:#460000; text-decoration:none; color:#FFF;" href="'.BASE.'/admin/dashboard.php?exe=sis/configuracoes" title="Desativar Manutenção">Desativar Manutenção</a>
			</div>
			';
            echo $divmanu;
        endif;
    endif;
}

/*****************************
FUNÇÃO CONTROLE DE ACESSOS
E GESTÃO DE ESTATÍSTICAS
 ******************************/
function viewManager(){
    if(!$_SESSION['userlogin']):

        //LER E CRIAR ESTATISTICAS DO DIA
        $dayOfManager = date('Y-m-d');
        $readSiteManager = read('siteviews',"WHERE data = '$dayOfManager'");
        if(!$readSiteManager):
            $createDay = array('data' => $dayOfManager, 'usuarios' => '1', 'visitas' => '1', 'pageviews' => '0');
            create('siteviews',$createDay);
            header('Location: '.$_SERVER['REQUEST_URI']);
        else:
            foreach($readSiteManager as  $sm);

            //CONFERE E ATUALIZA PAGE VIEWS
            $pageViewsMais = $sm['pageviews'] + 1;
            $updatePageViews = array('pageviews' => $pageViewsMais);
            update('siteviews',$updatePageViews,"data = '$dayOfManager'");

            //CONFERE E ATUALIZA VISITAS
            if(!$_SESSION['useracess']['sesid']):
                $_SESSION['useracess']['sesid'] = session_id();
                $_SESSION['useracess']['startview']  = time();
                $_SESSION['useracess']['endview']  = time() + 1200; //60*20 (20 minutos)
                $_SESSION['useracess']['userip']  = $_SERVER['REMOTE_ADDR'];
                $_SESSION['useracess']['userurl']  = $_SERVER['REQUEST_URI'];

                $cadUserOn = array(
                    "sesid" => $_SESSION['useracess']['sesid'],
                    "startview" => $_SESSION['useracess']['startview'],
                    "endview" => $_SESSION['useracess']['endview'],
                    "userip" => $_SESSION['useracess']['userip'],
                    "userurl" => $_SESSION['useracess']['userurl']
                );

                create('useronline',$cadUserOn);

                $visitasMais = $sm['visitas'] + 1;
                $updateVisitas = array('visitas' => $visitasMais);
                update('siteviews',$updateVisitas,"data = '$dayOfManager'");

            elseif($_SESSION['useracess']['endview'] < time()):
                unset($_SESSION['useracess']);
            else:
                $_SESSION['useracess']['userurl']  = $_SERVER['REQUEST_URI'];
                $_SESSION['useracess']['endview'] = time() + 1200;

                $updateUserOn = array(
                    "userurl" => $_SESSION['useracess']['userurl'],
                    "endview" => $_SESSION['useracess']['endview']
                );

                $sesid = $_SESSION['useracess']['sesid'];
                update('useronline',$updateUserOn,"sesid = '$sesid'");
            endif;

            //REMOVE USUARIOS EXPIRADOS
            $timeRemove = time();
            delete('useronline',"endview < '$timeRemove'");

            //CONFERE E ATUALIZA USUÁRIOS
            if(!$_COOKIE['MyContentUserAcess']):
                setcookie('MyContentUserAcess',time(),time()+86400); //60*60*24 (24 horas)

                $usuariosMais = $sm['usuarios'] + 1;
                $updateUsuarios = array('usuarios' => $usuariosMais);
                update('siteviews',$updateUsuarios,"data = '$dayOfManager'");
            endif;
        endif;//leu estatísticas

    endif;//verifica login
}

/*****************************
GERA SEO E SOCIAL
 ******************************/
function getSeo($title, $content, $url = NULL, $image = NULL){
    $pgTitle = $title;
    $title = lmWord($title,'70');
    $content = lmWord($content,'160');
    $url = BASE.'/'.$url;

    $pasta = 'uploads/';
    $default = BASE.'/themes/'.THEME.'/images/siteavatar.png';
    $image = ($image && file_exists($pasta.$image) && !is_dir($pasta.$image) ? BASE.'/'.$pasta.$image : $default);

    //NORMAL PAGE
    $result  = '<title>'.$pgTitle.'</title>'."\n";
    $result .= '<meta name="description" content="'.$content.'"/>'."\n";
    $result .= '<meta name="robots" content="index, follow" />'."\n";
    $result .= '<link rel="canonical" href="'.$url.'">'."\n";
    $result .= "\n";

    //FACEBOOK
    $result .= '<meta property="og:site_name" content="'.SITENAME.'" />'."\n";
    $result .= '<meta property="og:locale" content="pt_BR" />'."\n";
    $result .= '<meta property="og:title" content="'.$title.'" />'."\n";
    $result .= '<meta property="og:description" content="'.$content.'" />'."\n";
    $result .= '<meta property="og:image" content="'.$image.'" />'."\n";
    $result .= '<meta property="og:url" content="'.$url.'" />'."\n";
    $result .= '<meta property="og:type" content="article" />'."\n";

    //ITEM GROUP (TWITTER)
    $result .= '<meta itemprop="name" content="'.$title.'">'."\n";
    $result .= '<meta itemprop="description" content="'.$content.'">'."\n";
    $result .= '<meta itemprop="url" content="'.$url.'">'."\n";
    return $result;
}