/**
 * Created by Particular on 19/06/14.
 */

$('#login_com').click(function(){

    /* On load focus username input login */
    $('#username_com').focus();

    /* Keys pressed event handlers */
    $(document).keydown(function (key) {
        // "Enter" = 13
        if (key.keyCode == '13') {
            if ($('#form-signin_com').is(":visible")) {
                $('#btnLogOn_com').click();
            }
        }
    });
    $('#btnLogOn_com').click(function () {
        if($('#form-signin_com').serializeArray()[0].value!=""){
            if($('#form-signin_com').serializeArray()[1].value!=""){
                $.ajax({
                    url : MAINURL+"/ajax/login.php",
                    type: "POST",
                    data : $('#form-signin_com').serialize(),
                    success: function(data, textStatus, jqXHR)
                    {

                        if(data=="0"){

                            document.location=CURRENTURL;
                        }else if(data=="1"){
                            $('#notificacao_com').text("Usuário ou senha incorreta!").fadeIn(500).delay(3000).fadeOut(500);
                        }else if(data=="2"){
                            $('#notificacao_com').text("Sua conta está banida!").fadeIn(500).delay(3000).fadeOut(500);
                        }


                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        $.growl('Ocorreu um erro, tente novamente mais tarde!', options_danger);
                    }
                });
            }else{
                $('#notificacao_com').text("Preencha o campo de Senha!").fadeIn(500).delay(3000).fadeOut(500);
                $('#password_com').focus();
            }
        }else{
            $('#notificacao_com').text("Preencha o campo de Usuário!").fadeIn(500).delay(3000).fadeOut(500);
            $('#username_com').focus();
        }
    });
    });