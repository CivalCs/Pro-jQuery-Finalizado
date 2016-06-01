$(function(){

	//CONTROLA O LOGIN
	//$('.msg').hide().empty();
	/*
    $('.loginbox h1 img').hide();
	
	$('form[name="login"]').submit(function(){
		$('.loginbox h1 img').fadeIn("fast",function(){
			$('.msg').delay(1000).html('<p class="sucesso"><strong>Login efetuado com sucesso!</strong></p>').fadeIn("slow",function(){
				window.setTimeout( function(){
					$(location).attr('href','dashboard.php');	
				}, 1000 );	
			});
		});		
		return false;
	});
	*/

    //FUNÇÃO - TELA LOGIN
    $('form').submit(function(){
        var login = $(this).serialize() + '&acao=login';
        $.ajax({
            url:        'swith/login.php',
            data:       login,
            type:       'POST',
            success:    function( resposta ){
                //alert(resposta);
                if(resposta == 'erroempty'){
                    $('.msg').empty().html('<p class="aviso">Informe seu usuário e senha!</p>').fadeIn("slow");
                }
                else if(resposta == 'errosenha'){
                    $('.msg').empty().html('<p class="erro">Erro ao logar! Dados não conferem!</p>').fadeIn("slow");
                }
                else if(resposta == 'success'){
                    $('.msg').empty().html('<p class="sucesso">Login efetuado, aguarde...</p>').fadeIn("slow");
                    window.setTimeout( function(){
                        $(location).attr('href','dashboard.php'); //REDIRECIONAMENTO
                    }, 1000);
                }
                else{
                    alert('Erro no sistema');
                }
            },
            beforeSend: function(){
                $('.loginbox h1 img').fadeIn("fast");
            },
            complete:   function(){
                $('.loginbox h1 img').fadeOut("slow");
            },
            error:      function(){
                alert('Erro no sistema, contate o administrador.')
            }
        });
        return false;
    });
	
});