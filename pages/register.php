<?php
if(isLogged()){//Caso o Usuário esteja logado bloqueia a página
    $warning->addError(10005, "Esta página não pode ser acessada por você.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
}else{
$title .= " - Registro";
$layout_header .= '
    <script type="text/javascript" src="js/login.js"></script>';

$main_content .='
            <div class="form-signin">
               <h1 id="titulo">Registro</h1>
               <div id="notificacao" style="-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background:rgba(227,5,5,0.2);display: none;"><img width="32" height="32" src="'.URL.'/images/loadinghuge.gif"></div>

                <form id="form-register">
                    <input id="r-username" class="input-block-level" name="Username" type="text" placeholder="Username">
                    <input id="r-email" class="input-block-level" name="Email" type="text" placeholder="Email">
                    <input id="r-password" class="input-block-level" name="Password" type="password" placeholder="Password">
                    <input id="r-confirm" class="input-block-level" name="Confirm" type="password" placeholder="Confirm password">
                   <p>
<div id="btnRegisterSend" class="btn btn-success ladda-button zoom-out" style="float: none;">
                        <span class="ladda-label">Register</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </div>
                </form>


</div>
';
}
?>