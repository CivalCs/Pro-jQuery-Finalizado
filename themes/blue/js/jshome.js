$(function(){
    //Home destak e de olho
    $('.navbldois').on('click','.j_destaq',(function(){
        var manipula = $('.bloco_dois .arts');

        if($(this).hasClass('j_retorna')){
            $(this).css('background-color','rgba(120,56,74,0.7)');
            manipula.fadeTo(500,'0.2',function(){
                $.post(urlaction,{acao:'home_getdefault'},function(retorna_default){
                    manipula.html(retorna_default);
                    manipula.queue(function(){
                        manipula.fadeTo(500,'1');
                    });
                    manipula.dequeue();
                    $('.j_deolho').removeClass('j_retorna');
                    $('.j_destaq').removeClass('j_retorna');
                });
            });
        }else{
            $(this).css('background-color','rgb(120,56,74)');
            $('.navbldois .j_deolho').css('background-color','rgba(14,60,73,0.7)');
            manipula.fadeTo(500,'0.2',function(){
                $.post(urlaction,{acao:'home_getdestaque'},function(retorna_destaque){
                    manipula.html(retorna_destaque);
                    manipula.queue(function(){
                        manipula.fadeTo(500,'1');
                    });
                    manipula.dequeue();
                    $('.j_destaq').addClass('j_retorna');
                    $('.j_deolho').removeClass('j_retorna');
                });
            });
        }
    }));

    $('.navbldois').on('click','.j_deolho',(function(){
        var manipula = $('.bloco_dois .arts');

        if($(this).hasClass('j_retorna')){
            $(this).css('background-color','rgba(14,60,73,0.7)');
            manipula.fadeTo(500,'0.2',function(){
                $.post(urlaction,{acao:'home_getdefault'},function(retorna_default){
                    manipula.html(retorna_default);
                    manipula.queue(function(){
                        manipula.fadeTo(500,'1');
                    });
                    manipula.dequeue();
                    $('.j_deolho').removeClass('j_retorna');
                    $('.j_destaq').removeClass('j_retorna');
                });
            });
        }else{
            $(this).css('background-color','rgb(14,60,73)');
            $('.navbldois .j_destaq').css('background-color','rgba(120,56,74,0.7)');
            manipula.fadeTo(500,'0.2',function(){
                $.post(urlaction,{acao:'home_getdeolho'},function(retorna_deolho){
                    manipula.html(retorna_deolho);
                    manipula.queue(function(){
                        manipula.fadeTo(500,'1');
                    });
                    manipula.dequeue();
                    $('.j_deolho').addClass('j_retorna');
                    $('.j_destaq').addClass('j_retorna');
                });
            });
        }
    }));

    //height da box
    $('.bloco').each(function(){
        var altura = $(window).height();
        altura = altura - 110;
        $(this).css('min-height',altura);
    });

	//navegacao
	$('.navtopo li a').click(function(){
		var goto = $(this).attr("href");
		
		//CONTATO MODIFY
		if(goto != '#contato'){
			var gooo = $(goto).offset().top;
			$('html, body').animate({scrollTop:gooo},1000);
			
		}
		return false;
	});	
	
	//marcando atual
	var menuid = $('.navtopo li');
	var menuit = menuid.find('.a');
	
	var navit = menuit.map(function(){
		var cadait = $($(this).attr("href"));
		if(cadait.length) { return cadait; }
	});
	
	$('.navtopo li a:first').addClass('active');
	$(window).scroll(function(){
		var menuh = menuid.height();
		var dotopo = $(this).scrollTop()+menuh;
		
		var atual = navit.map(function(){
			var posicao = $(this).position().top;
			if(posicao < dotopo){
				return this;	
			}
		});
		
		atual = atual[atual.length-1];
		var este = atual && atual.length ? atual[0].id : "";
		
		if(menuid != este){
			menuid.find('a').removeClass('active');
			menuid.find('a[href="#'+este+'"]').addClass('active');	
		}
		
		//ACRESENTA HOVER FOOTER
		if(este != 'home'){
			$('#footer').slideDown("slow");	
		}else{
			$('#footer').slideUp("slow");	
		}
	});
});

$(window).load(function(){
	$('.slidequery li').each(function(){
		var img = $(this).find('img').attr("src");
		var pix = $(window).width();
		<!-- Alterações TIM THUMB	-->
		$(this).find('img').attr("src",'tim.php?src='+img+'&w='+pix+'&h=350&a=c');
	});

    //slide
    $('.slidequery').cycle({
        fx:      'fade',
        speed:    1000,
        timeout:  3000 ,
        pager:  '.slidequerynav'
    });
});