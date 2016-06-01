

$(function(){

	//CUSTOM
	$('.controle .li').mouseenter(function(){
		$(this).find('.submenu').slideDown("fast");
	}).mouseleave(function(){
		$(this).find('.submenu').slideUp("fast");
	});
	
	$('.dialog').hide();
	$('.dialog .msg').hide();
	$('.dialog .modal').hide();
	
	$('.closemodal, .closedial').click(function(){
		$('.dialog').find('div').fadeOut("slow",function(){
			$('.dialog').fadeOut("slow");	
		});
		
		return false;	
	});
	
		
	$('.posts .content .li span').hide();
	$('.posts .content .li').each(function(){
		$(this).mouseover(function(){
			$(this).find('span').fadeIn("fast");	
		}).mouseleave(function(){
			$(this).find('span').fadeOut("fast");
		});	
	});
	
	$('.j_addpost').click(function(){
		$('.dialog').fadeIn("fast", function(){
			$('.newpost').find('div').fadeIn("fast").find('img').hide(0,function(){
				$('.newpost').fadeIn("fast");
			});	
		});		
		return false;	
	});
	
	$('form[name="cadnewpost"]').submit(function(){
		$('.newpost').find('img').fadeIn('fast',function(){
			var id = '22';
			window.setTimeout( function(){
				$(location).attr('href','dashboard.php?exe=posts/edit&id=' + id );
			} , 2000);
		});	
		return false;
	});
	
	$('.j_send').click(function(){
		$('.j_capa').one('click',function(){
			$(this).click();
		}).change(function(){
			$('.viewcapa').fadeOut("slow",function(){
				$('.j_false').text($('.j_capa').val().replace('C:\\fakepath\\', ""));
			});			
		});
	});
	
	$('.j_gsend').click(function(){
		$('.j_gallery').one('click',function(){
			$(this).click();
		}).change(function(){
			$('.j_gprogress').animate({width:'880px'},500,function(){
				$(this).find('.bar').text('100%').css('max-width','864px').animate({width:'100%'},3500);
			});			
		});
	});
	
	$('.formfull .check').click(function(){
		if($(this).find('input').is(':checked')){
			$(this).css('background','#0C0');
		}else{
			$(this).css('background','#999');
		};
	});
	if($('.formfull .check input').is(':checked')){
		$('.formfull .check').css('background','#0C0');
	}else{
		$('.formfull .check').css('background','#999');
	};	
	
	
	$('.j_addcat').click(function(){
		$('.dialog').fadeIn("fast", function(){
			$('.newcat').find('div').fadeIn("fast").find('img').hide(0,function(){
				$('.newcat').fadeIn("fast");
			});	
		});
		
		return false;	
	});
	
	$('form[name="cadnewcat"]').submit(function(){
		$('.newcat').find('img').fadeIn('fast',function(){
			var id = '18';
			window.setTimeout( function(){
				$(location).attr('href','dashboard.php?exe=categorias/edit&id=' + id );
			} , 2000);
		});	
		return false;
	});
	
	$('.comentarios .listcom .li .commentitem .actions').hide();
	$('.comentarios .listcom .li').each(function(){
		$(this).mouseover(function(){
			$(this).find('.actions').fadeIn("fast");	
		}).mouseleave(function(){
			$(this).find('.actions').fadeOut("fast");
		});	
	});	
	
	//BACK
	$('a[href="#back"]').each(function(){
		$(this).click(function(){
			window.history.back();	
		});	
	});
	
	
	$('.j_adduser').click(function(){
		$('.dialog').fadeIn("fast", function(){
			$('.newuser').find('div').fadeIn("fast").find('img').hide(0,function(){
				$('.newuser').fadeIn("fast");
			});	
		});		
		return false;	
	});
	
	$('form[name="cadnewuser"]').submit(function(){
		$('.newuser').find('img').fadeIn('fast',function(){
			
		});	
		return false;
	});
});

