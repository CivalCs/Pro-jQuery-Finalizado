<?php session_start(); ob_start(); require_once('dts/configs.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alterar Tema do site!</title>
</head>

<body>

<?php
	$theme = mysql_real_escape_string($_SERVER['REQUEST_URI']);
	$theme = strrchr($theme,'/');
	$theme = str_replace('/','',$theme);

	if($theme):
		if(file_exists('themes/'.$theme) && is_dir('themes/'.$theme)):			
			$_SESSION['sampletheme'] = $theme;
			header('Location:'.BASE);
		else:
			echo '<h1>Erro ao ativar tema. Pasta não existe</h1>';
        endif;
    else:
        echo '<h1>Erro ao ativar tema. Tema não informado!</h1>';
    endif;

?>

</body>
</html>

<?php ob_end_flush();?>