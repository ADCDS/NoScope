<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 07/07/14
 * Time: 13:37
 */
if(!isLogged()){
    $warning->addWarning(10007, "Você precisa estar logado para acessar esta página, <a href='".urlToPage('login')."'>clique aqui</a> para fazer login.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
}else{
    if(!$GLOBALS['acc']->isValidated()){//Checa se o usuario é validado
        $warning->addWarning(10007, "Você precisa validar sua conta antes de acessar essa página, <a href='".urlToPage('validate')."'>clique aqui</a> para validar sua conta.");
        $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
    }else{
        $title .= ' - Chat';
$main_content .= '
<style type="text/css">
.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}
</style>
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Chat</h3>
      </div>
      <div class="panel-body">
	<div class="row clearfix">
		<div  class="col-md-3 column" style="overflow-y:scroll;height: 500px;">
		<table border="0" class="table table-hover table-bordered">';

        foreach(getFriends($_SESSION['userId']) as $amigo){
            $mensagens = getMessagesCountFrom($_SESSION['userId'],$amigo);
            $novas_mensages = "";
            if($mensagens > 0){
                $novas_mensages = $mensagens.' Novas Mensages';
            }
        $main_content .='
	<tr id="friend_'.$amigo.'" class="friend" data-name="'.getNick($amigo).'" data-id="'.$amigo.'" style="cursor: pointer;">
		<td width="1"><img src="'.getAccountAvatarURL($amigo).'" heigth="50" width="50"></img></td>
		<td>'.getNick($amigo).'<br/><span class="badgeid_'.$amigo.' badge badge-important" data-cont="'.$mensagens.'">'.$novas_mensages.'</span></td>
	</tr>
';
        }
		$main_content .= '
</table></div>
		<div class="col-md-9 column">
		<div id="chatbox">

		</div>
	</div>
	</div>
	</div>
	<script type="text/javascript" src="js/chat.js"></script>';
    }
}
?>