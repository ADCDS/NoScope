<?php
if(isLogged()){//Caso o Usuário esteja logado bloqueia a página
    $warning->addError(10005, "Esta página não pode ser acessada por você.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
}else{
$title .= " - Login";
$layout_header .= '<script type="text/javascript" src="js/login.js"></script>';

$main_content .='

            <div class="form-signin">
               <h1 id="titulo">Login</h1>
               <div id="notificacao" style="-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background:rgba(227,5,5,0.2);display: none;"><img width="32" height="32" src="'.URL.'/images/loadinghuge.gif"></div>
                <form id="form-signin">
                    <input type="text" class="input-block-level" placeholder="Username" id="username" name="UserName" value="'.@$_COOKIE['remember_me'].'">
                    <input type="password" class="input-block-level" placeholder="Password" id="password" name="Password">
                    <label for="renemberme-0">
                    <input type="checkbox" name="renemberme" id="renemberme-0" value="1"';
    if(isset($_COOKIE['remember_me'])) {
        $main_content .= 'checked="checked"';
    }
    $main_content .='>Lembrar de mim
                    </label>
                    <div id="btnLogOn" class="btn btn-large btn-primary btn-block ladda-button zoom-out">
                        <span class="ladda-label">Login</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </div>
                </form>

                <form id="form-register" style="display: none">
                    <input id="r-username" class="input-block-level" name="Username" type="text" placeholder="Username">
                    <input id="r-email" class="input-block-level" name="Email" type="text" placeholder="Email">
                    <input id="r-password" class="input-block-level" name="Password" type="text" placeholder="Password">
                    <input id="r-confirm" class="input-block-level" name="Confirm" type="text" placeholder="Confirm password">
                    <div id="btnRegisterCancel" class="btn btn-inverse" style="background-color: #C0C0C0;">Cancel</div>
                    <div id="btnRegisterSend" class="btn btn-success ladda-button zoom-out">
                        <span class="ladda-label">Register</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </div>
                </form>

                <form id="form-forgot" style="display: none">
                    <input id="rec-email" class="input-block-level" name="Email" type="text" placeholder="Email">
                    <div id="btnForgotCancel" class="btn btn-inverse" style="background-color: #C0C0C0;">Cancel</div>
                    <button id="btnForgotSend" class="btn btn-danger ladda-button zoom-out">
                        <span class="ladda-label">Recover</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>
                </form>
            </div>
            <div id="well" class="well well-small">
                <p style="margin: 0">Não tem uma conta? <strong><a id="btnRegister">Registre-se agora!</a></strong></p>
            </div>
            <div id="well" class="well well-small">
           <a id="btnForgot">Perdeu a sua senha?</a>
           </div>
';
}
?>