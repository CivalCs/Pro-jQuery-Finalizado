<?php
    session_start();
    require_once('../../dts/configs.php');
    if(!function_exists(myAut)):
        header('Location: dashboard.php');
        die;
    endif;

    myAut();
    header("Content-Type: text/javascript;charset=utf-8");

?>

//Carregamento da modal carregando (loadsistem) e fechamento da modal
$(window).load(function(){
   $('.loadsistem').fadeOut("fast",function(){
       $('.dialog').fadeOut("fast");
   });
});

 $(function() {

     //VARIAVEIS GERAIS

     var url = 'swith/painel.php';


     //GERAIS

     //VOLTAR PAGINA

     $('.j_back').click(function(){
         window.history.back();
         return false;
     });

     //EFEITOS DO MENU PRINCIPAL

     //Slide dos submenus
     $('.controle .li').mouseenter(function () {
         $(this).find('.submenu').slideDown("fast");
     }).mouseleave(function () {
         $(this).find('.submenu').slideUp("fast");
     });


    //CONTROLE DE PAGINAÇÃO VIA AJAX
    function autPaginator(div){
        var div = $(div);
        var url = $(location).attr('href');
        var ind = url.indexOf('&page=');
        var pag = url.substr(ind + 6) - 1;
        var newUrl = url.substr(0,ind);

        var licom   = div.find('.li').length;
        var backurl = newUrl + '&page=' + pag;

        if(licom <= 1){
            window.setInterval(function(){
                $(location).attr('href',backurl);
            },500);
        }
    }

     //Efeito hover sobre os botões
     $('.j_hover li').mouseover(function(){
         $(this).find('span').fadeIn("fast");
     }).mouseleave(function(){
         $(this).find('span').fadeOut("fast");
     })

     //Ativar botão de envio de capa

     $('.j_send').click(function(){
         $('.j_capa').click().change(function(){ //Inserir a imagem selecionada
             $('.j_false').text($(this).val().replace('C:\\fakepath\\',"")); //Replace serve pra tirar o caminho do fakepath
         }).trigger('change'); //Forçar com o trigger a ação do change (prols no IE)
         return false;
     });

     //PLACEHOLDER DO SISTEMA

     $('.j_placeholder').each(function(){
            var place = $(this).attr('title');
            $(this).val(place).click(function(){
                if($(this).val() == place){
                    $(this).val('');
                }
            }).blur(function(){
                if($(this).val() == '') {
                    $(this).val(place);
                }
            });
     });


     //CONTROLA HOME

     //Gera Usuarios Online
     $('.j_useronline').click(function(){
        $.post(url,{acao:'home_usuariosonline'},function(resposta){

            $('.dialog').fadeIn('fast',function(){
                $('.modaluseronline .content').html(resposta);
                $('.modaluseronline').queue(function(){
                    $('.modaluseronline').fadeIn('slow');
                });
                    $('.modaluseronline').dequeue();
            });
        });
        return false;
    });


    //Conta em Tempo Real
    if($('.j_useronlinerealtime').is(':visible')){
        setInterval(function(){
            $.post(url,{acao:'home_userreal'},function(resposta){
                $('.j_useronlinerealtime').text(resposta);
            });
        },10000);
    }


    //Fecha Usuarios Online

    $('.j_closeuseronline').click(function(){
        $('.modaluseronline').fadeOut("slow",function(){
            $('.dialog').fadeOut("fast");
        });
        return false;
    });





     //Gera trafego
     $('form[name="geradados"]').submit(function(){
         var forma = $(this);
         var dados = $(this).serialize() + '&acao=home_estatisticas';

         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',
             beforeSend:    function () {
                 forma.find('.load').fadeIn("fast");
             },
             success:       function (datas) {
                 if(datas == 'errempty'){
                     myDial('home_errempty','error', 'Para gerar o relatório é preciso informar as duas Datas.</p><p><strong>Obrigado!</strong>');
                 }
                 else if(datas == 'notfound'){
                     myDial('home_notfound','alert','A pesquisa não encontrou resultados para a data especificada.</p><p><strong>Por favor tente outra data!</strong>');
                 }
                 else{
                     $('.j_relatorio').fadeTo(500,'0.2',function(){
                         $(this).html(datas);
                         $(this).queue(function(){
                               $(this).fadeTo(500,'1');
                            });
                         $(this).dequeue();
                     });
                 }

             },
             complete: function () {
                 forma.find('.load').fadeOut("fast");
             }
         });

            return false;
     });



     //Abre gerador de trafegos
     $('.j_gerastats').click(function(){
         $('.dialog').fadeIn("fast",function(){
             $('.modaltrafego').fadeIn("slow");
         });
         return false;
     });
     //Fecha gerador de dados
     $('.j_closetrafic').click(function(){
         $('.modaltrafego').fadeOut("slow",function(){
             $('.dialog').fadeOut("fast");
         });
         return false;
     });


     //CONTROLA POSTS
     //Adicionar abertura do POST (Post > New Post)
     $('.j_addpost').click(function () {
         $.post(url,'acao=post_categoria_read',function( resposta ){
             $('form[name="cadnewpost"]').find('select').html(resposta);
         });
         $('.dialog').fadeIn("fast", function(){
             $('.newpost').fadeIn("fast");
         });
         return false;
     });

     //CADASTRAR POSTS

     $('form[name="cadnewpost"]').submit(function(){
         var forma = $(this);
         var dados = $(this).serialize() + '&acao=posts_cadastro';

         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',
             beforeSend:    function () {
                 forma.find('.load').fadeIn("fast");
             },
             success:       function (datas) {
                 //alert(datas);
                 if(datas == 'errempty'){
                     myDial('categoria_errempty','alert','<strong>Erro!</strong> Preenchimento de <strong>titulo</strong> ou <strong>categoria</strong> obrigatorios.</p><p><strong>Obrigado!</strong>');
                 }
                 else{

                     $('.ajaxmsg').addClass('posts_accept accept').html(
                         '<strong class="tt">Pronto!</strong>'+
                         '<p>Post cadastrado com sucesso! <br/> Redirecionando para pagina de gestão...</p><p><strong>Porfavor Aguarde!</strong>'
                     ).fadeIn("slow");
                     //Redireciona
                     window.setTimeout(function(){
                        forma.find('input[name="titulo"]').val('');
                         $(location).attr('href','dashboard.php?exe=posts/edit&id=' + datas);
                     }, 2000);
                 }
             },
             complete: function () {
                 forma.find('.load').fadeOut("fast");
             }
         });
         return false;
     });

     //Fechar a modal new post
     $('.closemodal').click(function () {
         var closemodal = $(this).attr('id');
         $('.' + closemodal).fadeOut("slow", function () {
             $('.dialog').fadeOut("fast");
         });
         return false;
     });

     //Fechar ajaxmodal

     $('.ajaxmsg').on('click','.j_ajaxclose',function () {
         var ajaxmodal = $(this).attr('id');
         $('.' + ajaxmodal).fadeOut("slow", function () {
             $('.dialog').fadeOut("fast");
             $(this).attr('class','ajaxmsg msg');
         });
         return false;
     });

     //Fechar ajaxdial

     $('.ajaxmsg').on('click','.j_dialclose',function () {
         var ajaxmodal = $(this).attr('id');
         $('.' + ajaxmodal).fadeOut("slow", function () {
             $(this).attr('class','ajaxmsg msg');
         });
         return false;
     });

     //Abre mensagem - EMAIL
     function myModal(id, tipo, content){
         var title = (tipo == 'accept' ? 'Pronto!' : (tipo == 'error' ? 'Ops, Erro!' : (tipo == 'alert' ? 'Atenção' : 'null')));
         if(title == 'null'){
             alert('Tipo deve ser: accept | error | alert');
         }
         else{
             $('.dialog').fadeIn("fast",function(){
                 $('.ajaxmsg').addClass(id).addClass(tipo).html(
                     '<strong class="tt">' +title+ '</strong>'+
                     '<p>' +content+ '</p>'+
                     '<a href="#" class="closedial j_ajaxclose" id="'+ id +'">FECHAR</a>'
                 ).fadeIn("slow");
             });
         }
     }

     //Abre dialogo

     function myDial(id, tipo, content){
         var title = (tipo == 'accept' ? 'Pronto!' : (tipo == 'error' ? 'Ops, Erro!' : (tipo == 'alert' ? 'Atenção' : 'null')));
         if(title == 'null'){
             alert('Tipo deve ser: accept | error | alert');
         }
         else{
             $('.ajaxmsg').addClass(id).addClass(tipo).html(
                 '<strong class="tt">' +title+ '</strong>'+
                 '<p>' +content+ '</p>'+
                 '<a href="#" class="closedial j_dialclose" id="'+ id +'">FECHAR</a>'
             ).fadeIn("slow");
         }
     }


     //ATUALIZAÇÃO DOS POSTS

     $('form[name="editpost"]').submit(function(){
            tinyMCE.triggerSave();

             var form  = $(this);
             var bar   = $('.j_editposts .progress');
             var per   = $('.j_editposts .progress .bar');

             $(this).ajaxSubmit({
                 url: url,
                 data: {acao: 'posts_update'},
                 beforeSubmit: function () {
                     $('.accept').fadeOut("fast");
                     $('.j_editpostimgload').fadeIn("fast");
                     $('.j_editposts .title img').fadeIn("fast");
                 },
                 uploadProgress: function (evento, posicao, total, completo) {

                     var capa = form.find('.j_capa');
                     var gbs  = form.find('.j_gallery');

                     if( capa.val() || gbs.val()) {

                         var porcento = completo + '%';
                         $('.dialog').fadeIn("fast", function () {
                             $('.j_editposts').fadeIn("slow", function () {
                                 bar.fadeIn("fast",function(){
                                     per.width(porcento).text(porcento);
                                 });
                             });
                         });

                     }
                 },
                 success: function (resposta) {
                         //alert(resposta);

                        var postid = form.find('input[name="postid"]').val();

                        if(resposta.search('sendcapa') >= '1'){
                            $.post(url,{acao: 'posts_getcapa', thispost: postid},function(thiscapa){
                                $('.viewcapa').fadeTo(500,'0.2',function(){
                                    $(this).find('img').fadeOut("fast",function(){
                                        $(this).attr('src','tim.php?src=../uploads/'+ thiscapa +'&h=42').fadeIn("fast");
                                    });
                                    $(this).find('a').attr('href','../uploads/'+ thiscapa);
                                    $(this).queue(function(){
                                        $(this).fadeTo(500,'1');
                                    });
                                    $(this).dequeue();
                                    form.find('.j_false').text('');
                                    form.find('.j_capa').val('');
                                });
                            });
                        }

                        if(resposta.search('sendgb') >= '1'){
                            //alert('galeria');
                            $.post(url,{acao: 'posts_getgallery', thisgb: postid},function(thisgallery){

                            $('.galerry ul').fadeTo(500,'0.3',function(){
                                    $(this).html(thisgallery);
                                    $(this).queue(function(){
                                        $(this).fadeTo(500,'1');
                                    });
                                    $(this).dequeue();
                                });
                            });
                            $('.j_gfalse').animate({width: '220px'},500,function(){
                                    $(this).html('Envio realizado com sucesso!');
                            });
                            form.find('.j_gallery').val('');
                        }
                 },
                 complete: function () {
                     $('.j_editpostimgload').fadeOut("fast");
                     $('.j_editposts .title img').fadeOut("fast");
                     bar.fadeOut("fast",function(){
                         $('.accept').fadeIn("slow");
                     });
                 }
             });
             return false;
     });


     $('.galerry ul').on('click','.galerrydel',function(){
            var delid = $(this).attr('id');
            $('.galerry ul li[id="'+ delid +'"]').css('background','red');
            $.post(url,{acao:'posts_gbdel',imagem:delid},function(retorna){
             //alert(retorna);
             window.setTimeout(function(){
                 $('.galerry ul li[id="'+ delid +'"]').fadeOut("slow");
             },500);
         });
            return false;
     });


     $('.galerry').on('click','.gb_open',function(){
         Shadowbox.open(this);
         return false;
     })


     $('.j_closeloadposts').click(function(){
            $('.j_editposts').fadeOut("fast",function(){
                $('.dialog').fadeOut("fast");
                $('.accept').fadeOut("fast");
                $('.progress').fadeIn("fast",function(){
                    $(this).find('.bar').css('width', '0%').text('0%');
                });

            });

            return false;
     });

     //Seleção de imagens - POST
     $('.j_gsend').click(function(){
         $('.j_gallery').click().change(function(){
             var numFiles = $(this)[0].files.length;
             $('.j_gfalse').animate({width: '480px'},500,function(){
                 $(this).html('Você selecionou <strong>' + numFiles + '</strong> arquivos. Clique em <strong>atualizar</strong> para realizar o envio!')
             });
         });
     });

     //Marca o checkbox

     $('form[name="editpost"] .check').click(function(){
         if($(this).find('input').is(':checked')){
             $(this).css('background','#0C0');
         }
         else{
             $(this).css('background','#999');
         }
     });

     if($('form[name="editpost"] .check input').is(':checked')){
         $('form[name="editpost"] .check').css('background','#0C0');
     }
     else{
         $('form[name="editpost"] .check').css('background','#999');
     }

     //Compartilhar POST NO FACEBOOK

     $('.j_postshare').click(function(){
            var urlshare = $(this).attr('href');
            window.open('http://www.facebook.com/sharer.php?u=' + urlshare, 'WebPi',"width=500,height=400,status=yes,toolbar=no,menubar=no,location=no");
            return false;
     });

     //Deletar/Excluir o POST


     $('.j_postsdel').click(function(){
            var postid = $(this).attr('id');
            var r = confirm('Deseja realmente deletar este post ?');
             if(r == true){
                 $.post(url,{id: postid, acao: 'posts_deleta'},function(retorna){

                        var lidel = $('.content li[id="'+postid+'"]');
                        lidel.css('background','red');
                        lidel.queue(function(){
                            window.setTimeout(function(){
                                lidel.fadeOut("slow",function(){ $(this).remove() });
                                autPaginator('.postsul');
                            },1000);
                        });
                        lidel.dequeue();
                 });
             }
            return false;
     });


        //COMENTARIOS

        $('.comentarios .li').mouseover(function(){
            $(this).find('.actions').fadeIn('fast');
            }).mouseleave(function(){
                $(this).find('.actions').fadeOut('fast');
            });



        //DELETA NA LISTA DE COMENTARIOS
        $('.actions .delete').click(function(){
            var id = $(this).attr('id');
            var li = $('.listcom li[id="'+id+'"]');

            var c = confirm("ATENÇÃO: Tem certeza que deseja deletar este comentário?\nAVISO: Está ação é irreversível!");
            if(c) {
                li.css("background", "#FF8080");
                $.post(url, {acao: 'mod_dellist', comid: id}, function () {
                    window.setTimeout(function () {
                    li.fadeOut("slow",function(){ $(this).remove() });
                    autPaginator('.listcom');
                }, 1000);
            });
        }
            return false;
        });


        //REMOVE O AVISO DE DELETE
        $('.dellcom span').click(function(){
            $.post(url,{acao:'mode_reses'},function(){
                $('.dellcom').delay(500).slideUp('slow');
            });
        });


        //ABRE MODAL DE EDIÇÃO
        $('.commentmanage').on('click','.editar',function(){
            var comid = $(this).attr('id');
            $.post(url,{acao:'com_loadmodal',com:comid},function(modal_edit){
                var modal = $('.ajaxmoderate');
                modal.fadeIn('fast',function(){
                    $(this).html(modal_edit);
                    $(".formDate").mask("99/99/9999 99:99:99", {placeholder: " "});
                    $(this).queue(function(){
                        $(this).find('.contentcom').fadeIn("slow");
                    });
                    $(this).dequeue();
                });
            });
                return false;
        });


        //EDITA COMENTARIO
        $('.ajaxmoderate').on('submit','form[name="editcomment"]',function(){
            var forma = $(this);
            var dados = forma.serialize() + '&acao=com_editar';

            $.ajax({
            url:        url,
            data:       dados,
            type:       'POST',
            dataType:   'json',
            beforeSend: function(){
            forma.find('.loadcom').fadeIn("fast");
            },
            success:    function(retorno){

            if(retorno.erro){
            myModal('mod_errempty','alert','Para atualizar o comentário certifique-se de que todos os campos estão preenchidos!</p><p><strong>Obrigado!</strong>');
                }else{
                    var atualiza = (retorno.tipo == '1' ? '.commentmanage .principal' : '.respostas li[id="'+retorno.id+'"]');
                    var atualiza = $(atualiza);

                    atualiza.find('.text').fadeTo(500,'0.2',function(){ $(this).html(retorno.comentario) });
                    atualiza.find('.data').fadeTo(500,'0.2',function(){ $(this).html(retorno.data) });
                    atualiza.queue(function(){
                        $(this).find('.text, .data').fadeTo(500,'1');
                        });
                    atualiza.dequeue();
                    myModal('mod_errempty','accept','O Comentário foi atualizado com sucesso, você já pode visualiza-lo no sistema!</p><p><strong>Obrigado!</strong>');
                    $('.dialog').one('mouseover','.j_ajaxclose',function(){
                        var modal = $('.ajaxmoderate');

                        modal.find('.contentcom').fadeOut("slow",function(){
                            modal.fadeOut("fast",function(){modal.empty();})
                        });
                    });
                }
                },
                complete:   function(){
                    forma.find('.loadcom').fadeOut("fast");
                }
                });

                return false;
            });

    //MODERAR COMENTARIOS
    $('.commentmanage').on('click','.moderate a',function(){
    var action = $(this).attr('class');
    var comids = $(this).attr('id');

    if(action == 'aceitar' || action == 'ocultar'){
    $('.commentmanage .text[id="'+comids+'"]').addClass('loadthis');
    }

    if(action != 'editar'){

    if(action == 'deletar'){
    var c = confirm("ATENÇÃO: Tem certeza que deseja deletar este comentário?\nAVISO: Está ação é irreversível!");
    }else{
    var c = true;
    }
    if(c && c == true){
    $.post(url,{acao:'com_moderate',id:comids,subaction:action},function(moderate){

    var thisdiv = (moderate == '1' ? '.commentmanage .principal' : '.respostas li[id="'+comids+'"]');
    var thisdiv = $(thisdiv);

    if(action == 'ocultar'){
    thisdiv.addClass('pendente');
    thisdiv.find('.ocultar').fadeOut('fast',function(){
    thisdiv.find('.aceitar').fadeIn("fast");
    });
    $('.commentmanage .text[id="'+comids+'"]').removeClass('loadthis');
    }else if(action == 'aceitar'){
    thisdiv.removeClass('pendente');
    thisdiv.find('.aceitar').fadeOut('fast',function(){
    thisdiv.find('.ocultar').fadeIn("fast");
    });
    $('.commentmanage .text[id="'+comids+'"]').removeClass('loadthis');
    }else if(action == 'deletar'){
    thisdiv.css('background','red').delay("slow").fadeOut("slow",function(){
    $(this).remove();
    });
    if(moderate == '1'){ window.setTimeout(function(){ window.history.back(); },1000);}
    }
    });
    }
    }

    return false;
    });


    //ADICIONA ADMIN RESP
    $('form[name="addresposta"]').submit(function(){
    var forma = $(this);
    var dados = forma.serialize() + '&acao=mod_addadmin';
    $.ajax({
    url:            url,
    data:           dados,
    type:           'POST',
    beforeSend:     function(){
    forma.find('.load').fadeIn("fast");
    },
    success:        function(liadmin){
    if(liadmin == 'errempty'){
    myModal('mod_errempty','alert','Você deixou o campo comentário em branco, está ação não é permitida pelo sistema!</p><p><strong>Por favor, verifique e tente novamente!</strong>');
    }else if(liadmin == 'errmod'){
    myModal('mod_errempty','error','Antes de enviar uma resposta, certifique-se de moderar primeiro todos os comentário deste topico!</p><p><strong>Após liberação dos comentários, tente novamente!</strong>');
    }else{
    var ul = $('.respostas');
    ul.append(liadmin);
    ul.queue(function () {
    ul.find('.li').fadeIn("slow");
    });
    ul.dequeue();
    forma.find('textarea').val('');
    }
    },
    complete:       function(){
    forma.find('.load').fadeOut("slow");
    }
    })
    return false;
    })


    //FECHA MODAL DE EDIÇÃO
    $('.ajaxmoderate').on('click','.j_closemoderal',function(){
    var modal = $('.ajaxmoderate');

    modal.find('.contentcom').fadeOut("slow",function(){
    modal.fadeOut("fast",function(){modal.empty();})
    });

    return false;
    });


     //CONTROLA CATEGORIAS


     //Abre modal

     $('.j_addcat').click(function () {
         $.post(url,'acao=categoria_read',function( resposta ){
             $('form[name="cadnewcat"]').find('select').html(resposta);
         });
         $('.dialog').fadeIn("fast", function () {
             $('.newcat').fadeIn("slow");
         });
         return false;
     });


     //Cadastrar Categorias
     $('form[name="cadnewcat"]').submit(function(){
         var forma = $(this);
         var dados = $(this).serialize() + '&acao=categoria_cadastro';

         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',
             beforeSend:    function () {
                 forma.find('.load').fadeIn("fast");
             },
             success:       function (datas) {
                 //alert(datas);
                 if(datas == 'errempty'){
                     myDial('categoria_errempty','alert','Preencha o campo <strong>categoria</strong>.</p><p><strong>Obrigado!</strong>');
                 }
                 else if(datas == 'errisset'){
                     myDial('categoria_errisset','error','O <strong>nome</strong> da Sessão ou Categoria já está existe!</p><p><strong>Utilize outro nome!</strong>');
                 }
                 else{

                     $('.ajaxmsg').addClass('categoria_accept accept').html(
                         '<strong class="tt">Pronto!</strong>'+
                         '<p>Sua Sessão ou Categoria foi cadastrada com sucesso! Redirecionando para pagina de gestão...</p><p><strong>Porfavor Aguarde!</strong>'
                     ).fadeIn("slow");


                    //Redireciona
                    forma.find('input[name="categoria"]').val('');
                     window.setTimeout(function(){
                         $(location).attr('href','dashboard.php?exe=categorias/edit&catid='+ datas);
                     }, 2000);
                 }
             },
             complete: function () {
                 forma.find('.load').fadeOut("fast");
             }
         });
         return false;
     });

     //Atualizar/Update Categoria

     $('form[name="editcat"]').submit(function(){
            //alert('ok');
            var form = $(this);
            var bar   = form.find('.progress');
            var per   = form.find('.bar');

            $(this).ajaxSubmit({
                url:    url,
                data:   {acao: 'categoria_update'},
                beforeSubmit:   function(){
                    form.find('.load').fadeIn("fast");
                },
                uploadProgress: function(evento, posicao, total, completo) {
                    var porcento = completo + '%';
                    bar.fadeIn("fast", function () {
                        per.width(porcento).text(porcento);
                    });
                },
                success:        function(resposta){
                    if(resposta == 'errempty'){
                        myModal('catupdate_errempty','alert','Para atualizar a categoria preencha todos os campos!</p><p><strong>Obrigado!</strong>')
                    }
                    else if(resposta == 'errisset'){
                        myModal('catupdate_errisset','error','<strong>Erro!</strong> O Nome desta categoria já existe, por favor mude o nome</p><p><strong>Obrigado!</strong>')
                    }
                    else if(resposta == 'errext'){
                        myModal('catupdate_errext','alert','A Capa que você está tentando enviar nao tem uma extenção válida!</p><p>Extensões validas: <strong>JPEG, PNG e GIF!</strong>')
                    }
                    else if(resposta == 'Error'){
                        myModal('catupdate_other','error','Ops, Não foi possivel processar a operação, arquivo muito grande!</p><p><strong>Desculpe!</strong>')
                    }
                    else{
                        myModal('catupdate_accept','accept','Sua categoria foi atualizada com sucesso!</p><p><strong>Parabéns!</strong>')
                        //TIRAR A CAPA ASSIM QUE DER RELOAD NA PAGINA
                        $('.viewcapa').fadeOut("slow",function(){
                            $(this).find('img').attr('src','tim.php?src=../uploads/' + resposta + '&h=42'); //Acrescentar as infos da img na capa
                            $(this).find('a').attr('href','../uploads/' + resposta);

                            $('.viewcapa').fadeIn("slow");
                        });
                    }
                },
                complete:       function(){
                    bar.fadeOut("slow",function(){
                        $('.j_capa').val('');
                        form.find('.load').fadeOut("fast");
                        per.width('0%').text('0%');
                    });
                }
            });
            return false;
     });


     //EXIBIR A SHADOWBOX (Abrir a imagem de capa) NA TELA DE EDIÇÃO MANIPULANDO-A PELO DOM ABAIXO
     $('.viewcapa').on('click','a',function(){
         Shadowbox.open(this);
         return false;
     })


     //Deletar categoria

    $('.j_catdelete').click(function(){
        var catid = $(this).attr('id');
        var dados = 'acao=categoria_deleta&catid=' + catid;
        //alert(dados);
                $.ajax({
                url:           url,
                data:          dados,
                type:          'POST',
                beforeSend:    function () {
                    $('.catli li[id="'+ catid +'"]').css('background','red');
                },
                success:       function (datas) {
                    //alert(datas);
                    if(datas == 'errisset'){
                        myModal('categoria_delete_errisset','alert','<strong>Erro!</strong> Não é possivel deletar uma sessão ou categoria que contém conteúdo. </p><p>Para isto: <strong>Limpe primeiro a SESSÃO ou CATEGORIA</strong>.');
                        window.setTimeout(function(){
                            $('.catli li[id="'+ catid +'"]').css('background','#fff');
                        },1000);
                    }
                    else{
                        window.setTimeout(function(){
                            $('.catli li[id="'+ catid +'"]').fadeOut("slow",function(){ $(this).remove() });
                            autPaginator('.catli');
                        },1000);
                    }

                }
            });
                return false;
        });





     //USUARIOS

     //Abrir modal

     $('.j_adduser').click(function(){
         $('.dialog').fadeIn("fast",function(){
             $('.newuser').fadeIn("slow");
         });
         return false;
     });

     /*
     //Fechar e atualizar - CADASTRO USUARIO

     $('.j_closenewuser').click(function(){
         window.location.reload();
         return false;
     });
     */

     //Cadastrar usuarios

     $('form[name="cadnewuser"]').submit(function(){
         var forma = $(this);
         var dados = $(this).serialize() + '&acao=usuarios_manage&exe=cadastro';

         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',
             beforeSend:    function () {
                 forma.find('.load').fadeIn("fast");
             },
             success:       function (datas) {
                 //alert(datas);
                 if(datas == 'errempty'){
                     myDial('usuarios_errempty','alert','Preencha todos os campos.</p><p><strong>Obrigado!</strong>');
                 }
                 else if(datas == 'errmail'){
                     myDial('usuarios_errmail','error','Formato do <strong>e-mail</strong> inválido, favor corrigir.</p><p><strong>Obrigado!</strong>');
                 }
                 else if(datas == 'errisset'){
                     myDial('usuarios_errisset','error','O <strong>e-mail</strong> ou <strong>login </strong>já está sendo utilizado por outro usuário.</p><p><strong>Utilize outro e-mail ou login!</strong>');
                 }
                 else{
                     myDial('usuarios_accept','accept','Usuário <strong>'+datas+'</strong> cadastrado com sucesso!</p><p><strong>Parabéns!</strong>');
                     $('#usuarios_accept').click(function(){
                         window.location.reload();
                         return false;
                     });
                 }
             },
             complete: function () {
                 forma.find('.load').fadeOut("fast");
             }
         });
         return false;
     });

     //Abrir edição de usuarios

     $('.j_useredit').click(function(){
            var userid = $(this).attr('id');
            var dados  = 'acao=usuarios_consulta&userid='+userid;

         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',

             success:       function (datas) {
                    $('.dialog').fadeIn("fast",function(){
                        $('.edituser').html(datas).fadeIn("slow");
                    });
             }
         });
            return false;
     });

     //Fechar edição de usuarios

     $('.edituser').on('click','.j_formclose',function(){
            $('.edituser').fadeOut("slow",function(){
                $('.dialog').fadeOut("fast");
                $(this).html('');
            });
            return false;
     })

     //Editar e validar usuario

     $('.edituser').on('submit','form[name="edituser"]',function(){
         var forma = $(this);
         var dados = $(this).serialize() + '&acao=usuarios_manage&exe=atualiza';

         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',
             beforeSend:    function () {
                 forma.find('.load').fadeIn("fast");
             },
             success:       function (datas) {
                 //alert(datas);
                 if(datas == 'errempty'){
                     myDial('usuarios_errempty','alert','Preencha todos os campos.</p><p><strong>Obrigado!</strong>');
                 }
                 else if(datas == 'errmail'){
                     myDial('usuarios_errmail','error','Formato do <strong>e-mail</strong> inválido, favor corrigir.</p><p><strong>Obrigado!</strong>');
                 }
                 else if(datas == 'errisset'){
                     myDial('usuarios_errisset','error','O <strong>e-mail</strong> ou <strong>login </strong>já está sendo utilizado por outro usuário.</p><p><strong>Utilize outro e-mail ou login!</strong>');
                 }
                 else{
                     myDial('usuarios_accept','accept','Usuário <strong>'+datas+'</strong> atualizado com sucesso!</p><p><strong>Parabéns!</strong>');
                     $('#usuarios_accept').click(function(){
                         window.location.reload();
                         return false;
                     });
                 }
             },
             complete: function () {
                 forma.find('.load').fadeOut("fast");
             }
         });
            return false;
     });

     //Deletar usuario

     $('.j_userdelete').click(function(){
         var userid = $(this).attr('id');
         var dados  = 'acao=usuarios_deleta&userid='+ userid;
         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',
             beforeSend:    function () {
                 $('.usuarios .users li[id="' + userid + '"]').css('background','red');
             },
             success:       function (datas) {
                    //alert(datas);
                 if(datas == 'errsuper'){
                     myModal('usuarios_errsuper','alert','<strong>Erro!</strong> Não é permitido deletar o único <strong>SUPER ADMIN</strong> cadastrado no sistema.</p><p><strong>Desculpe!</strong>');
                     window.setTimeout(function(){
                         $('.usuarios .users li[id="' + userid + '"]').css('background','#fff');
                     },3000);
                 }
                 else{
                     $('.usuarios .users li[id="' + userid + '"]').fadeOut("slow");
                 }
             }
         });
            return false;
     });

     //CONFIGURAÇÕES (CONFIGURAR)

     //Navegação em abas

     $('.configs .abas_config li a').click(function () {
         $('.configs .abas_config li a').removeClass('active');
         $(this).addClass('active');

         var formgo = $(this).attr('href');
         $('.configs form[name!="' + formgo + '"]').fadeOut("fast", function () {
             $('form[name="' + formgo + '"]').delay("fast").fadeIn("fast");
         });
         //alert(formgo);

         return false;
     });

     //MANUTENÇÃO

     $('form[name="config_manutencao"]').submit(function () {

         return false;
     });

     //DESATIVA A MANUTENÇÃO - evita envio
     $('.j_config_manutencao_no').click(function () {

         $.ajax({
                url:        url,
                data:       'acao=manutencao_desativa',
                type:       'POST',
                beforeSend: function(){
                    $('.configs form .main .load').fadeIn("fast");
                },
                complete:   function(){
                    $('.configs form .main .load').fadeOut("fast",function(){
                        $('.j_manutencao_desativa').fadeOut("slow",function() {
                            $('.j_manutencao_ativa').fadeIn("slow");
                        });
                    });
                }
         });
     });

     //ATIVA A MANUTENÇÃO - habilita envio

     $('.j_config_manutencao_yes').click(function () {

         $.ajax({
             url:        url,
             data:       'acao=manutencao_ativa',
             type:       'POST',
             beforeSend: function(){
                 $('.configs form .main .load').fadeIn("fast");
             },
             complete:   function(){
                 $('.configs form .main .load').fadeOut("fast",function(){
                     $('.j_manutencao_ativa').fadeOut("slow",function() {
                         $('.j_manutencao_desativa').fadeIn("slow");
                     });
                 });
             }
         });
     });



    //CONFIGURA SERVIDOR DE EMAIL

     //Atualiza dados do Email
    $('form[name="config_email"]').submit(function(){
        var forma = $(this);
        var dados = $(this).serialize() + '&acao=mailserver_atualiza';
        $.ajax({
            url: url,
            data: dados,
            type: 'POST',
            beforeSend: function () {
                forma.find('.load').fadeIn("fast");
            },
            success: function (datas) {
                if(datas == 'errempty'){
                    myModal('config_mailserver_errempty','alert','Preencha todos os campos.</p><p><strong>Obrigado!</strong>');
                }
                else if(datas == 'errmail'){
                    myModal('config_mailserver_errmail','error','Formato do <strong>e-mail</strong> inválido, favor corrigir.</p><p><strong>Obrigado!</strong>');
                }
                else{
                    myModal('config_mailserver_accept','accept','Dados atualizados com sucesso, para garantir que suas informações estejam corretas por favor: </p><p><strong>Teste o Envio!</strong>');
                }
            },
            complete: function () {
                forma.find('.load').fadeOut("fast");
            }
        });
        return false;
    });

     //Testa dados do Email
     $('.j_config_email_teste').click(function(){
         var forma = $('form[name="config_email"]');
         $.ajax({
             url:           url,
             data:          'acao=mailserver_teste',
             type:          'POST',
             beforeSend:    function () {
                 forma.find('.load').fadeIn("fast");
             },
             success:       function (datas) {
                 if(datas.indexOf('error') <= 0){
                     myModal('teste_mailserver_accept','accept','E-mail enviado com sucesso. Favor checar o recebimento da mensagem em seu e-mail abaixo: </p><p><strong>' + datas + '</strong>');
                 }
                 else{
                     myModal('teste_mailserver_error', 'error', 'Falha ao enviar o e-mail. Favor confira os dados e envie novamente!</p><p><strong>Obrigado!</strong>');
                 }
                 },
             complete:      function () {
                 forma.find('.load').fadeOut("fast");
             }
         });
         return false;
     });

     //CONFIGURA ENDEREÇO E TELEFONE

     $('form[name="config_dados"]').submit(function(){
         var forma = $(this);
         var dados = $(this).serialize() + '&acao=endtel_atualiza';
         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',
             beforeSend:    function () {
                 forma.find('.load').fadeIn("fast");
             },
             success:       function (datas) {
                 if(datas == 'errempty'){
                     myModal('config_endtel_errempty','alert','Preencha todos os campos.</p><p><strong>Obrigado!</strong>');
                 }
                 else{
                     myModal('config_endtel_accept','accept','Endereço e Telefone atualizados com <strong>sucesso</strong>! </p><p><strong>Obrigado!</strong>');
                 }
             },
             complete:      function () {
                 forma.find('.load').fadeOut("fast");
             }
         });
            return false;
     });



     //CONFIGURAR SEO/SOCIAL

     $('form[name="config_seo"]').submit(function(){
         var forma = $(this);
         var dados = $(this).serialize() + '&acao=seosocial_atualiza';
         $.ajax({
             url:           url,
             data:          dados,
             type:          'POST',
             beforeSend:    function () {
                 forma.find('.load').fadeIn("fast");
             },
             success:       function (datas) {
                 if(datas == 'errempty'){
                     myModal('config_seosocial_errempty','alert','Preencha todos os campos.</p><p><strong>Obrigado!</strong>');
                 }
                 else{
                     myModal('config_seosocial_accept','accept','Dados <strong>atualizados</strong>, o site foi otimizado com sucesso!<br/>As configurações já estão <strong>ativas</strong>! </p><p><strong>Obrigado!</strong>');
                 }
             },
             complete:      function () {
                 forma.find('.load').fadeOut("fast");
             }
         });
         return false;
     });




    //CONFIGURAÇÕES - TEMA

    //LER TEMAS

    function lerTemas(){
        $.post(url,{acao:'theme_read'},function(retorno){
            $('.themes').fadeTo(500,'0.2',function(){
                $(this).html(retorno);
                $(this).queue(function(){
                    $(this).fadeTo(500,'1');
                });
                $(this).dequeue();
            });
          });
        };


    //CADASTRAR TEMAS

    $('form[name="config_theme"]').submit(function() {
        var forma = $(this);
        var dados = $(this).serialize() + '&acao=theme_cadastra';
        $.ajax({
            url: url,
            data: dados,
            type: 'POST',
            beforeSend: function () {
                forma.find('.load').fadeIn("fast");
            },
            success: function (datas) {
                if (datas == 'errempty') {
                    myModal('config_theme_errempty', 'alert', 'Para cadastrar um <strong>novo tema</strong>, informe o nome e a pasta que ele se encontra dentro do diretorio <strong>Temas</strong>.</p><p><strong>Obrigado!</strong>');
                }
                else {
                    myModal('config_theme_accept', 'accept', 'O tema '+datas+' foi criado com <strong>sucesso</strong>! </p><p><strong>Parabéns!</strong>');
                    lerTemas();
                }
            },
            complete: function () {
                forma.find('.load').fadeOut("fast");
            }
        });
        return false;
    });


     //Ativar Temas

     $('.themes').on('click','.j_themeactive',function(){
         var themeid = $(this).attr('id');
         $.post(url,{acao:'theme_ativa',id:themeid},function(dados){
             lerTemas();
         });
         return false;
     });

     //Deleta Temas

     $('.themes').on('click','.j_themedelete',function(){
         var themeid = $(this).attr('id');
         $.post(url,{acao:'theme_deleta',id:themeid},function(dados){
             if(dados == 'erractive'){
                 myModal('deleta_theme_error', 'error', 'O Tema que você está tentando deletar está em uso!</p><p><strong>Ative outro tema para deletar este!</strong>');
             }
             else{
                 $('.themes li[id="'+ themeid +'"]').css('background','red');
                 window.setTimeout(function(){
                     $('.themes li[id="'+ themeid +'"]').fadeOut("slow");
                 },1000);
             }
         });
         return false;
     });


 });




