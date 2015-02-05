$(document).ready(function () {

    /* On load focus username input login */
    $('#username').focus();

    /* Keys pressed event handlers */
    $(document).keydown(function (key) {
        // "Enter" = 13
        if (key.keyCode == '13') {
            if ($('#form-signin').is(":visible")) {
                $('#btnLogOn').click();
            } else if ($('#form-register').is(":visible")) {
                $('#btnRegisterSend').click();
            } else if ($('#form-forgot').is(":visible")) {
                $('#btnForgotSend').click();
            }
        } else if (key.keyCode == '27') {
            if ($('#form-register').is(":visible")) {
                $('#btnRegisterCancel').click();
            } else if ($('#form-forgot').is(":visible")) {
                $('#btnForgotCancel').click();
            }
        }
    });
    $('#btnLogOn').click(function () {
            if($('#form-signin').serializeArray()[0].value!=""){
                if($('#form-signin').serializeArray()[1].value!=""){
        $.ajax({
            url : MAINURL+"/ajax/login.php",
            type: "POST",
            data : $('#form-signin').serialize(),
            success: function(data, textStatus, jqXHR)
            {
                if(data=="0"){
                    $.growl('Conectado com sucesso!', options_success);
                    document.location=MAINURL;
                }else if(data=="1"){
                    $('#notificacao').text("Usuário ou senha incorreta!").fadeIn(500).delay(3000).fadeOut(500);
                }else if(data=="2"){
                    $('#notificacao').text("Sua conta está banida!").fadeIn(500).delay(3000).fadeOut(500);
                }


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $.growl('Ocorreu um erro, tente novamente mais tarde!', options_danger);
            }
        });
                }else{
                    $('#notificacao').text("Preencha o campo de Senha!").fadeIn(500).delay(3000).fadeOut(500);
                    $('#password').focus();
                }
            }else{
                $('#notificacao').text("Preencha o campo de Usuário!").fadeIn(500).delay(3000).fadeOut(500);
                $('#username').focus();
            }
    });
    $('#btnRegisterSend').click(function (){
            if($('#form-register').serializeArray()[0].value!=""){
                if($('#form-register').serializeArray()[1].value!=""){
                    if($('#form-register').serializeArray()[2].value!=""){
                        if($('#form-register').serializeArray()[3].value!=""){
                            if($('#form-register').serializeArray()[2].value==$('#form-register').serializeArray()[3].value){
                                if(($('#form-register').serializeArray()[0].value.length<=20)&&($('#form-register').serializeArray()[0].value.length>=3)){
                                    if(($('#form-register').serializeArray()[2].value.length<=16)&&($('#form-register').serializeArray()[3].value.length>=4)){


                                        $.ajax({
            url : MAINURL+"/ajax/register.php",
            type: "POST",
            data : $('#form-register').serialize(),
            success: function(data, textStatus, jqXHR)
            {
                       if(data=="0"){
                            $.growl('Conta criada com sucesso!',  options_success );
                            document.location=MAINURL
                        }else if(data=="1"){
                            $.growl('Ocorreu um erro na hora de criar sua conta, tente mais tarde!', options_danger);
                        }else if(data=="2"){
                            $.growl('Esse nome de usuário ja está em uso!', options_danger);
                        }else if(data=="3"){
                            $.growl('Esse email ja está em uso!', options_danger);
                        }else{
                            $.growl('Ocorreu um erro ao criar sua conta!', options_danger);
                        }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $.growl('Ocorreu um erro, tente novamente mais tarde!', options_danger);
            }
        });
                                    }else{
                                        $('#notificacao').text("A senha deve ter no mínimo 4 caractéries e no máximo 16 caractéries!").fadeIn(500).delay(3000).fadeOut(500);
                                    }
                                }else{
                                    $('#notificacao').text("O Nome de Usuário deve ter no mínimo 3 caractéries e no máximo 20 caractéries!").fadeIn(500).delay(3000).fadeOut(500);
                                }
                            }else{
                                $('#notificacao').text("As senhas devem ser iguais!").fadeIn(500).delay(3000).fadeOut(500);
                            }
                        }else{
                            $('#notificacao').text("Preencha o campo de Confirmação de Senha!").fadeIn(500).delay(3000).fadeOut(500);
                            $('#r-confirm').focus();
                        }
                    }else{
                        $('#notificacao').text("Preencha o campo de Senha!").fadeIn(500).delay(3000).fadeOut(500);
                        $('#r-password').focus();
                    }
                }else{
                    $('#notificacao').text("Preencha o campo de Email!").fadeIn(500).delay(3000).fadeOut(500);
                    $('#r-email').focus();
                }
            }else{
                $('#notificacao').text("Preencha o campo de Usuário!").fadeIn(500).delay(3000).fadeOut(500);
                $('#r-username').focus();
            }
    });
    $('#btnRegister').click(function () {
        $('#titulo').text("Registre-se");
        $('#form-signin').hide();
        $('#form-forgot').hide();
        $('#form-register').fadeIn(500);
        $('#r-username').focus();

        // clear inputs
        $('#username').val("");
        $('#password').val("");
    });

    $('#btnRegisterCancel').click(function () {
        $('#titulo').text("Login");
        $('#form-register').hide();
        $('#form-signin').fadeIn(500);
        $('#username').focus();

        // clear inputs
        $('#r-username').val("");
        $('#r-email').val("");
        $('#r-password').val("");
        $('#r-confirm').val("");
    });

    $('#btnForgot').click(function () {
        $('#titulo').text("Recuperar senha");
        $('#form-signin').hide();
        $('#form-register').hide();
        $('#form-forgot').fadeIn(500);
        $('#rec-email').focus();

        // clear inputs
        $('#username').val("");
        $('#password').val("");
    });

    $('#btnForgotCancel').click(function () {
        $('#titulo').text("Login");
        $('#form-forgot').hide();
        $('#form-signin').fadeIn(500);
        $('#username').focus();

        // clear inputs
        $('#rec-email').val("");
    });

    $('#btnForgotSend').click(function() {
        $.ajax({
            url : MAINURL+"/ajax/send_recover.php",
            type: "POST",
            data : $('#form-forgot').serialize(),
            success: function(data, textStatus, jqXHR)
            {
                if(data=="0"){
                    $.growl('Um email de verificação foi enviado!',  options_success );
                    document.location=MAINURL;
               }else{
                    alert(data);
                    $.growl('Ocorreu um erro, tente mais tarde!', options_danger);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $.growl('Ocorreu um erro, tente novamente mais tarde!', options_danger);
            }
        });
    });
});