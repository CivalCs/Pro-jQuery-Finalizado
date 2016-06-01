<?php
ob_start(); session_start();
require('dts/configs.php');
viewManager();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />

<link rel="icon" type="image/png" href="<?php setHome();?>/themes/<?php echo THEME;?>/images/favicon.png"/>
<link  rel="stylesheet" type="text/css" href="<?php setHome();?>/jsc/shadowbox/shadowbox.css" />
<link rel="nofollow" title="base" href="<?php setHome();?>"/>
<link rel="nofollow" title="theme" href="<?php echo THEME;;?>"/>

<script type="text/javascript" src="<?php setHome();?>/jsc/jquery.js"></script>
<script type="text/javascript" src="<?php setHome();?>/jsc/jcycle.js"></script>
<script type="text/javascript" src="<?php setHome();?>/jsc/jmask.js"></script>
<script type="text/javascript" src="<?php setHome();?>/jsc/shadowbox/shadowbox.js"></script>

<?php require('themes/'.THEME.'/css/'.THEME.'.css.php');?>
<?php require('themes/'.THEME.'/js/'.THEME.'.js.php');?>

<!--<script type="text/javascript" src="<?php //setHome();?>/tpl/js/estilos.js"></script>-->


<!--</head>
<body>-->

<?php
    manutencao();  getHome();
?>

</body>
</html>