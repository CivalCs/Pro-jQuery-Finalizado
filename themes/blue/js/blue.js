$(function(){
    //GLOBAIS DO SISTEMA
    base = $('link[title="base"]').attr('href');
    theme = $('link[title="theme"]').attr('href');
    urlaction = base+'/themes/'+theme+'/switch/'+theme+'.php';

    //PRE CARREGAR IMAGENS E SUBSTITUIR POR ERRO
    $('#site').find('img').load(function(){
         $(this).fadeIn("slow");
    });


    //Inicia a shadowbox
	Shadowbox.init();

	
	//Navegacao Geral
	$('.pagecontato').click(function(){
		$('.navtopo li a[href="#contato"]').addClass('active');
        $.post(urlaction,{acao:'load_contato'},function(form_contato){
            $('.j_contato').html(form_contato).animate({width: 'toggle'});
            $('.formFone').mask("(99) 9999.9999",{placeholder:""});
        });
		return false;
	});

    //Fecha contato closecontato
    $('.j_contato').on('click','.closecontato',function(){
        $('.contato').animate({width: 'toggle'},function(){
            $('.j_contato').empty;
        });
        $('.navtopo li a[href="#contato"]').removeClass('active');
        return false;
    });

    //Envia contato
    $('.j_contato').on('submit','form[name="contato"]',function(){
        var dados = $(this).serialize() + '&acao=send_contato';
        var forma = $(this);
        $.ajax({
            url: urlaction,
            type: 'POST',
            data: dados,
            beforeSend: function(){
                forma.find('.imgload').fadeIn("fast");
            },
            success: function(resposta){
                if(resposta == 'errdados'){
                    myDial('alert','<p>Desculpe, para enviar um contato à nossa equipe, é preciso que você preencha todos os campos requisitados!</p><p><strong>Informe seus dados e envie sua mensagem!</strong></p>');
                }else if(resposta == 'erremail'){
                    myDial('error','<p>Desculpe, o e-mail que você informou não tem um formato válido, assim não poderemos responder!</p><p><strong>Favor informe um e-mail válido!</strong></p>');
                }else{
                    myDial('accept','<p>Parabéns <strong>'+ resposta +'</strong> sua mensagem foi enviada com sucesso. Em breve estaremos respondento!</p><p><strong>Obrigado por entrar em contato!</strong></p>');
                    $('.dialog').one('click','.closedial',function(){
                        $('.j_contato').animate({width: 'toggle'},function(){
                            $(this).empty();
                        });
                        $('.msg').fadeOut("slow",function(){
                            $(this).empty();
                        });
                        $('.navtopo li a[href="#contato"]').removeClass('active');
                        return false;
                    });
                }
            },
            complete: function(){
                forma.find('.imgload').fadeIn("slow");
            }
        });
        return false;
    });

    //Efeito Menu categorias
    $('.subopen').click(function(){
        return false;
    });

    $('.navtopo li').hover(function(){
        $(this).find('.submenu').fadeIn('fast');
    }).mouseleave(function(){
        $(this).find('.submenu').fadeOut("fast");
    });

    //PAGINA CATEGORIAS
    $('.j_catpag').on('click','.paginator a',function(){
       if($(this).hasClass('atv')){
            return false;
       }else{
           $('.j_catpag .paginator span').css({color:'#FFF','border-color':'#fff'});
           $('.j_catpag .paginator a').removeClass('atv');
           $(this).addClass('atv');

           var url = $(this).attr('href');
           var data = url.lastIndexOf('/') + 1;
           var data = url.substr(data);

           $('.j_catpag ul').fadeTo(500,'0.2');

           $.post(urlaction,{acao:'cat_paginar',page:data},function(paginacao){
               $('html, body').delay(500).animate({scrollTop:$('h2').offset().top},500);
               window.setTimeout(function(){
                   $('.j_catpag').html(paginacao);
                   $('.j_catpag ul').fadeTo(500,'1');
               },600);
           });
       }
        return false;
    });

    //REALIZA VALIDAÇAO DE PESQUISA
    $('form[name="search"]').submit(function(){
        var pesquisa = $(this).find('input[name="s"]').val();
        if(!pesquisa){
            myDial('alert','<p>Para realizar uma pesquisa, informe os termos desejados. Somente depois, clique em pesquisar!</p><p><strong>Favor realize sua pesquisa!</strong></p>');
        }else{
            var compara = $('.j_search');
            if(compara.is(':visible')){
                //REMOVER FAZER VIA AJAX
                var title = $(document).attr('title');
                var change = title.indexOf('Pesquisa por:') + 13;
                var newtitle = title.substr(0,change);

                $(document).attr('title',newtitle + ' '+ pesquisa);
                $('.capa img').attr({alt:'Pesquisa por: '+ decodeURI(pesquisa), title:'Pesquisa por: '+ decodeURI(pesquisa)});
                $('.j_psearch').text(decodeURI(pesquisa));

                $('.j_search ul').fadeTo(500,'0.2');

                $.post(urlaction,{acao:'search_get',pesquisa:pesquisa},function(pesquisa){
                    $('html, body').delay(500).animate({scrollTop:$('h2').offset().top},500);
                    window.setTimeout(function(){
                        $('.j_search').html(pesquisa);
                        $('.j_search ul').fadeTo(500,'1');
                    },600);
                });
            }else{
                var pesquisa = encodeURI(pesquisa);
                $(location).attr('href',base+'/pesquisa/'+pesquisa);
            }

        }

        return false;
    });

    //PAGINA SEARCH
    $('.j_search').on('click','.paginator a',function(){
        if($(this).hasClass('atv')){
            return false;
        }else{
            $('.j_search .paginator span').css({color:'#FFF','border-color':'#fff'});
            $('.j_search .paginator a').removeClass('atv');
            $(this).addClass('atv');

            var url = $(this).attr('href');
            var data = url.lastIndexOf('/') + 1;
            var data = url.substr(data);

            var pesquisa = $('form[name="search"]').find('input[name="s"]').val();

            $('.j_search ul').fadeTo(500,'0.2');

            $.post(urlaction,{acao:'search_paginar',page:data,pesquisa:pesquisa},function(paginacao){
                $('html, body').delay(500).animate({scrollTop:$('h2').offset().top},500);
                window.setTimeout(function(){
                    $('.j_search').html(paginacao);
                    $('.j_search ul').fadeTo(500,'1');
                },600);
            });
        }
        return false;
    });

	
	//FECHA O DIAL
	$('.dialog').on('click','.closedial',function(){
		$(this).parent().fadeOut("slow",function(){
			$('.dialog').fadeOut("fast",function(){
                $(this).empty();
            });
		});	
		return false;	
	});
	
	function myDial(clase,content){
		var strong = (clase == 'alert' ? 'Opsss:' : (clase == 'accept' ? 'Sucesso:' : (clase == 'error' ? 'Erro:' : 'Olá')));
		$('.dialog').fadeIn("fast",function(){
			$('.dialog').html('<div class="msg '+ clase +'"><strong class="tt">'+ strong +'</strong>'+ content +'<a href="#" class="closedial">X FECHAR</a></div>');	
		    $('.dialog .msg').fadeIn('slow');
        });
	}

    function confuse(){
        $('.dialog').fadeIn("fast",function(){
            $('.dialog').html('<div class="msg alert"><strong class="tt">Estamos confusos:</strong><p>Estamos confusos sobre o que você está lendo agora. Nao será possível enviar um comentário!</p><p><strong>Atualize a página para comentar!</strong></p><span class="confuse">ATUALIZAR PÁGINA AGORA!</span></div>');
            $('.dialog .msg').css({height: '210px','margin-top':'-105px'}).fadeIn('slow');
        });
    }

    $('.dialog').on('click','.confuse',function(){
        location.reload();
        return false;
    });

    function loadmoderal(){
        $.post(urlaction,{acao:'load_moderate'},function(resposta){
            if(resposta){
                $('.moderar').fadeOut("fast");
                $('.moderajax').html(resposta);
                $('html, body').animate({scrollTop:$('.comments').offset().top},500,function(){
                    $('.moderajax').fadeIn("slow");
                });
            }
        });
    }


    //LIMPA TAMANHO DE ELEMENTOS NA SINGLE CONTENT
	$('.artigo .content img').each(function(){
		$(this).removeAttr('width').removeAttr('height');
	});
	
	$('.artigo .content iframe').each(function(){	
		var url = $(this).attr("src");
		var char = "?";
		if(url.indexOf("?") != -1){
			var char = "&";
		}
		
		var iw = $(this).width();
		var ih = $(this).height();
		var width = '660';
		var height = (width*ih) / iw;		
		$(this).attr({'width':'660px','height':height+'px','src':url+char+'wmode=transparent'});
	});
	
	//CONTROLA A BOX DE COMENTÁRIOS
    $('.opencomment').click(function(){
        var id = $(this).attr('id');
        $.post(urlaction,{acao:'load_comment',postid:id},function(form_comment){
            if(form_comment == 'confuse'){
                confuse();
            }else{
               $('.commentbox').fadeIn("fast",function(){
                    $('.commentbox').html(form_comment).find('form').fadeIn("slow");
                });
            }
        });
        return false;
    });

    $('.commentbox').on('click','.closecomment',function(){
        $('.commentbox').find('form').fadeOut("slow",function(){
            $('.commentbox').empty().fadeOut("fast");
        });
        return false;
    });

    //Envia novo comentário
    $('.commentbox').on('submit','form[name="addcomment"]',function(){
        var dados = $(this).serialize() + '&acao=send_comment';
        var forma = $(this);
        $.ajax({
            url: urlaction,
            type: 'POST',
            data: dados,
            beforeSend: function(){
                forma.find('.imgload').fadeIn("fast");
            },
            success: function(retorna){
                if(retorna == 'confuse'){
                    confuse();
                }else if(retorna == 'errdados'){
                    myDial('alert','<p>Para enviar seu comentario, precisamos que você preencha o formulário informando seus dados!</p><p><strong>Preencha e envie seu comentário!</strong></p>');
                }else if(retorna == 'errmail'){
                    myDial('error','<p>Opsss. O e-mail que você informou não tem um formato válido, assim não poderemos responder a você!</p><p><strong>Informe seu e-mail para comentar!</strong></p>');
                }else{
                    myDial('accept','<p>Olá <strong>'+ retorna +'</strong>, seu comentário foi cadastrado com sucesso, antes de liberar vamos moderá-lo!</p><p><strong>Aguarde novidades!</strong></p>');
                    $('.dialog').one('click','.closedial',function(){
                        $('.commentbox').find('form').delay("fast").fadeOut("fast",function(){
                            $('.commentbox').empty().fadeOut("fast");
                        });
                        $('.msg').fadeOut("fast",function(){
                            $(this).empty();
                        });
                        loadmoderal();
                        return false;
                    });
                }
            },
            complete: function(){
                forma.find('.imgload').fadeOut("slow");
            }
        });

        return false;
    });

    //carrega modal de resposta
    $('.commentlist').on('click','.addresp',function(){
        var idc = $(this).attr('id');
        $.post(urlaction,{acao:'load_resposta',commnetid:idc},function(form_comment){
            if(form_comment == 'confuse'){
                confuse();
            }else{
                $('.commentbox').fadeIn("fast",function(){
                    $('.commentbox').html(form_comment).find('form').fadeIn("slow");
                });
            }
        });
        return false;
    });

    //envia nova resposta
    $('.commentbox').on('submit','form[name="addresposta"]',function(){
        var dados = $(this).serialize() + '&acao=send_resp';
        var forma = $(this);
        $.ajax({
            url: urlaction,
            type: 'POST',
            data: dados,
            beforeSend: function(){
                forma.find('.imgload').fadeIn("fast");
            },
            success: function(retorna){
                if(retorna == 'confuse'){
                    confuse();
                }else if(retorna == 'errdados'){
                    myDial('alert','<p>Para enviar sua resposta, precisamos que você preencha o formulário informando seus dados!</p><p><strong>Preencha e envie sua resposta!</strong></p>');
                }else if(retorna == 'errmail'){
                    myDial('error','<p>Opsss. O e-mail que você informou não tem um formato válido, assim não poderemos responder a você!</p><p><strong>Informe seu e-mail para comentar!</strong></p>');
                }else{
                    myDial('accept','<p>Olá <strong>'+ retorna +'</strong>, sua resposta foi cadastrada com sucesso, antes de liberar vamos moderá-la!</p><p><strong>Aguarde novidades!</strong></p>');
                    $('.dialog').one('click','.closedial',function(){
                        $('.commentbox').find('form').delay("fast").fadeOut("fast",function(){
                            $('.commentbox').empty().fadeOut("fast");
                        });
                        $('.msg').fadeOut("fast",function(){
                            $(this).empty();
                        });

                        loadmoderal();
                        return false;
                    });
                }
            },
            complete: function(){
                forma.find('.imgload').fadeOut("slow");
            }
        });

        return false;
    });

    //Load more comments
    $('.loadmorcomment').click(function(){
        var loadmo = $(this);
        var thisid = $(this).attr('id');
        var numlis = $('.commentlist .comp').length;

        loadmo.html('<img style="display:block; width:20px; margin:0 auto;" src="'+base+'/themes/blue/images/loader2.gif" width="20" title="Carregando" alt="Carregando">');
        $.post(urlaction,{acao:'loadmore_btn',postid:thisid,numcom:numlis},function(countcom){
            if(countcom >= 1){
                $.post(urlaction,{acao:'loadmore_com',postid:thisid,numcom:numlis},function(morecom) {
                    $('.commentlist .comp:last').after(morecom);
                    $('.commentlist .comp').fadeIn("fast");
                });
            }

            if(countcom > 5){
                loadmo.html('CARREGAR MAIS COMENTÁRIOS!');
            }else{
                loadmo.fadeOut("slow");
            }
        });
    });
});

$(window).load(function(){
	$('#site').find('img').fadeIn("slow");
});