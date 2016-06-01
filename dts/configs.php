<?php
//DEFINE BANCO DEDADOS
define('HOST','localhost');
define('USER','root');
define('PASS','');
define('DBSA','projquery');

//BASE DO SITE
define('BASE','http://127.0.0.1/projquery/');
define('IMAGEW','700px');

//CONECTA NO BANCO
$conn = mysql_connect(HOST, USER, PASS) or die ('Erro ao conectar: '.mysql_error());
$dbsa = mysql_select_db(DBSA) or die ('Erro ao selecionar banco: '.mysql_error());


//INCLUI FUNÇÕES DO PRO PHP
require_once('functions.php');

function myAut(){
    if($_SESSION['userLogin']){
        $id = $_SESSION['userLogin']['id'];
        $login = $_SESSION['userLogin']['login'];
        $senha = $_SESSION['userLogin']['senha'];
        $readAutUser = read('usuarios',"WHERE id = '$id' AND login = '$login' AND senha = '$senha'");
        if(!$readAutUser):
            unset($_SESSION['userLogin']);
            header('Location: index.php?restrito=true');
        endif;
    }else{
        header('Location: index.php?restrito=true');
    }
}

function valNivel($nivel = NULL){
    if($nivel && $nivel != $_SESSION['userLogin']['nivel']):
        header('Location: dashboard.php?exe=sis/403');
    endif;
}

/*function myAut($nivel = NULL){
    if($_SESSION['userLogin']){
        $id = $_SESSION['userLogin']['id'];
        $login = $_SESSION['userLogin']['login'];
        $senha = $_SESSION['userLogin']['senha'];
        $readAutUser = read('usuarios',"WHERE id = '$id' AND login = '$login' AND senha = '$senha'");
        if(!$readAutUser):
            unset($_SESSION['userLogin']);
            header('Location: index.php?restrito=true');
        else:
            if($nivel && $nivel != $_SESSION['userLogin']['nivel']):
                header('Location: dashboard.php?exe=sis/403');
            endif;
        endif;
    }else{
        header('Location: index.php?restrito=true');
    }
}*/

//DEFINE O TEMA A SER USADO
$config_readTheme = read('config_theme',"WHERE inuse = '1'");
if($_SESSION['sampletheme']):
    define('THEME',$_SESSION['sampletheme']);
elseif($config_readTheme):
    foreach($config_readTheme as $config_theme);
    define('THEME',$config_theme['pasta']);
else:
    define('THEME','default');
endif;


//DEFINE O SERVIDOR DE E-MAIL
$config_readMailServer = read('config_mailserver');
if($config_readMailServer):
    foreach($config_readMailServer as $config_mailserver);
    define('MAILUSER',$config_mailserver['email']);
    define('MAILPASS',$config_mailserver['senha']);
    define('MAILPORT',$config_mailserver['porta']);
    define('MAILHOST',$config_mailserver['server']);
else:
    define('MAILUSER','null');
    define('MAILPASS','null');
    define('MAILPORT','null');
    define('MAILHOST','null');
endif;

//DEFINE O SEO SOCIAL
$config_readSeoSocial = read('config_seosocial');
if($config_readSeoSocial):
    foreach($config_readSeoSocial as $config_seosocial);
    define('SITENAME',$config_seosocial['titulo']);
    define('SITEDESC',$config_seosocial['descricao']);
    define('FACEBOOK',$config_seosocial['facebook']);
    define('TWITTER',$config_seosocial['twitter']);
else:
    define('SITENAME','null');
    define('SITEDESC','null');
    define('FACEBOOK','null');
    define('TWITTER','null');
endif;


//MEUS DADOS
$config_readEndTel = read('config_endtel');
if($config_readEndTel):
    foreach($config_readEndTel as $config_endtel);
    define('ENDERECO',$config_endtel['endereco']);
    define('TELEFONE',$config_endtel['telefone']);
else:
    define('ENDERECO','null');
    define('TELEFONE','null');
endif;



