<?php
/**
 * Created by PhpStorm.
 * User: HAL-9000
 * Date: 23/06/14
 * Time: 12:48
 */
if (isset($_GET['value1'])) {
    if (accExists($_GET['value1'])) {
        $account_profile = new Account($_GET['value1']);
        $title .= ' - ' . $account_profile->getNick();
        $main_content .= '<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Perfil de ' . $account_profile->getNick() . '</h3>
      </div>
       '.($account_profile->isBanned()?'<div class="banned" style="pointer-events: none; background-color: #CCC; z-index: 50; opacity: 0.5; "> <div class="alert alert-danger" role="alert"><h2><span class="glyphicon glyphicon-ban-circle" style="float: left"></span>Usuário Banido<span class="glyphicon glyphicon-ban-circle" style="float: right"></h2></div>':'').'
      <div class="panel-body">
      <div class="row clearfix">
		<div class="col-md-3 column">
		<img src="' . getAccountAvatarURL($account_profile->getId()) . '" width="250px" height="250px" alt="' . $account_profile->getNick() . '"><br/>
		<center><h3>' . (($account_profile->getName()) ? $account_profile->getName() : $account_profile->getNick()) . '</h3></center>
		';
        if($account_profile->getOption('facebook')!=""){
        $main_content.= '<a target="_blank" href="http://facebook.com/'.$account_profile->getOption('facebook').'" class="btn btn-block btn-social btn-facebook">
            <i class="fa fa-facebook"></i>Meu Facebook
          </a>';
        }
        if($account_profile->getOption('twitter')!=""){
        $main_content.= '<a target="_blank" href="http://twitter.com/'.$account_profile->getOption('twitter').'" class="btn btn-block btn-social btn-twitter">
            <i class="fa fa-twitter"></i>@'.$account_profile->getOption('twitter').'
          </a>';
        }
         if($account_profile->getOption('website')!=""){
             $main_content.= '<a target="_blank" href="'.$account_profile->getOption('website').'" class="btn btn-block btn-social btn-reddit">
            <span class="glyphicon glyphicon-globe"></span>Meu Site
          </a>';
         }
        $main_content .='
		</div>
		<div class="col-md-6 column">
		<dl class="dl-horizontal">';

		if($account_profile->getOption('show_email')=='checked'){
            $main_content .= '
				<dt>
					Email:
				</dt>
				<dd>
					' . $account_profile->getEmail() . '
				</dd>';
        }
        if($bio = $account_profile->getOption('bio')){
            $main_content .='
                            <dt>
                                Bio:
                            </dt>
                            <dd>
                                '.$bio.'
                            </dd>
';
        }
            $main_content .=    '
            <hr>
                    <dt>
					Grupo:
				</dt>
				<dd>
					' . getGroupName($account_profile->getGroup()) . '
				</dd>
                        </dl>
                    </div>

           <div class="col-md-3 column"><div id="buttonAreaFriend">';

        if (isLogged()) {
            if ($_GET['value1'] == $_SESSION['userId']) {
                $main_content .= '<a class="edit_profile" data-href="' . urlToPage('edit_profile') . '"><button type="button" class="btn btn-primary btn-block">Editar Perfil</button></a>';
            } elseif (checkFriendRequest($_SESSION['userId'], $account_profile->getId(), true)) {
                $main_content .= '<button id="buttonFriend" type="button" onclick="removeFriend(' . $account_profile->getId() . ')"class="btn btn-danger btn-block">Remover amigo</button>';
            } elseif (checkFriendRequest($_SESSION['userId'], $account_profile->getId(), false, true)) {
                $main_content .= '<button id="buttonFriend" type="button" onclick="addFriend(' . $account_profile->getId() . ')"class="btn btn-primary btn-block">Aceitar pedido de amizade</button>';
            } elseif (checkFriendRequest($account_profile->getId(), $_SESSION['userId'], false, true)) {
                $main_content .= '<button id="buttonFriend" type="button" class="btn btn-warning btn-block">Solicitação Enviada</button>';
            } else {
                $main_content .= '<button id="buttonFriend" type="button" onclick="addFriend(' . $account_profile->getId() . ')"class="btn btn-success btn-block">Adicionar como amigo</button>';
            }
        }
        $main_content .= '</div>';
    if($account_profile->getOption('show_friends')){
        $main_content .= '
        <hr>
        <div class="col-md-12 column">
		<h5>Amigos de ' . $account_profile->getNick() . '('.getFriendsCount($account_profile->getId()).')</h5>
		<div class="row">
		';
        foreach(getFriends($account_profile->getId(), 4) as $id_amigo){
           $main_content .= '
  <div class="col-sm-4 col-md-6">
    <a href="'.urlToPage('profile').'&value1='.$id_amigo.'"><div class="thumbnail">
      <img src="'.getAccountAvatarURL($id_amigo).'" alt="'.getNick($id_amigo).'">
      <div class="caption">
		'.getNick($id_amigo).'</a>
      </div>
    </div>
  </div>
';
        }

        $main_content .='</div></div>';
    }
     $main_content .=   '</div>
	</div>
	<div class="row clearfix">';
        if($account_profile->getOption('show_games')){
		$main_content .='<div class="col-md-12 column">
		<h4>Jogos que ' . $account_profile->getNick() . ' possui</h4>
		</div>
		<div class="btn-group btn-group-justified">';
            foreach($account_profile->getGames() as $game){
                $jogo = new Game($game);
            $main_content .= '<div class="btn-group">
    <a href="'.urlToPage('game').'&id='.$game.'"><button type="button" class="btn btn-default"><img height="80" weigth="80" src="'.URL.'/media/games/'.$game.'/icon"><br><b>'.$jogo->getNome().'</b></button></a>
  </div>';
            }
            $main_content .= '</div>';
    }
	$main_content .='</div>

	</div>
	</div>';
        $main_content .= ''.($account_profile->isBanned()?'</div>':'').'';
    } else {
        $main_content .= '<div class="panel panel-danger"><div class="panel-heading">
        <h3 class="panel-title">Erro</h3>
      </div>
      <div class="panel-body">
        <center>Essa conta não existe</center>
      </div></div>';
    }
} else {
    $main_content .= '<div class="panel panel-danger"><div class="panel-heading">
        <h3 class="panel-title">Erro</h3>
      </div>
      <div class="panel-body">
        <center>Página inválida</center>
      </div></div>';
}


?>