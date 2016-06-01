<?php
if(function_exists(myAut)):
    myAut();
else:
    header('Location: ../dashboard.php');
    die;
endif;
?>
<div class="content home">
    <h1 class="location">Oppsss. Acesso negado! <span><?php echo date('d/m/Y H:i');?></span></h1><!--/location-->
    
	<div class="erronotfound">
    	<h2>Oppsss, seu nível de usuário deve ser super admin para acessar essas configurações!</h2>
        <span><strong>Oppsss. Erro 403</strong>Acesso negado!</span>
    </div><!--404-->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->