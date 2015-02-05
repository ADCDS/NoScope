<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 19/06/14
 * Time: 12:40
 */
$title .= " - Recuperar senha";
$layout_header .= '
    <script type="text/javascript" src="js/login.js"></script>';


$main_content .='
            <div class="form-signin">
               <h1 id="titulo">Recuperar Senha</h1>
               <div id="notificacao" style="-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background:rgba(227,5,5,0.2);display: none;"><img width="32" height="32" src="'.URL.'/images/loadinghuge.gif"></div>

                <form id="form-forgot">
                    <input id="rec-email" class="input-block-level" name="Email" type="text" placeholder="Email">
                    <p>
                    <button id="btnForgotSend" class="btn btn-danger ladda-button zoom-out" style="float: none;">
                        <span class="ladda-label">Recover</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>
                </form>

</div>
';
?>