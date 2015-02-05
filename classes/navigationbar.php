<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 08/06/14
 * Time: 12:27
 */

class NavigationBar {
const TYPEPAGE = 0;
const TYPEEXTERNAL = 1;
const TYPECLASS = 2;
const ONLYLOGGED = 0;
const ONLYGUEST = 1;
const ONLYALL = -1;

static public function loadMenus(){
   global $mysql;
    if(isLogged()){
    $menus = $mysql->query('SELECT id FROM menus WHERE visibility<='.$GLOBALS['acc']->getGroup().' AND (onlyGuest != 1 OR onlyGuest = -1)');
    }else{
    $menus = $mysql->query('SELECT id FROM menus WHERE onlyGuest != 0 OR onlyGuest = -1');
    }
    foreach($menus as $menu){
       $GLOBALS['menus'][] = new Menu($menu["menus"]["id"]);
    }
}
static public function getRightMenus(){
    if(!isLogged()){
        $return = '<li><a href="'.urlToPage('register').'">Registre-se</a></li>
          <li class="divider-vertical"></li>
          <li class="dropdown">
            <a id="login_com" class="dropdown-toggle" href="#" data-toggle="dropdown">Login<strong class="caret"></strong></a>
            <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
             <div id="notificacao_com" style="-webkit-border-radius: 20px;-moz-border-radius: 20px;border-radius: 5px;background:rgba(227,5,5,0.2);display: none;"><img width="32" height="32" src="http://localhost/GameSite/images/loadinghuge.gif"></div>
             <form id="form-signin_com">
                    <input type="text" class="input-block-level" placeholder="Username" id="username_com" name="UserName" value="'.@$_COOKIE['remember_me'].'">
                    <input type="password" class="input-block-level" placeholder="Password" id="password_com" name="Password">
                    <label for="renemberme-0">
                    <input type="checkbox" name="renemberme" id="renemberme-0" value="1"';
        if(isset($_COOKIE['remember_me'])) {
            $return .= 'checked="checked"';
        }
        $return .='>Lembrar de mim
                    </label>
                    <button id="btnLogOn_com" class="btn btn-large btn-primary btn-block ladda-button zoom-out" style="margin-bottom: 10px;">
                        <span class="ladda-label">Login</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    <span class="ladda-spinner"></span></button>
                </form>
            </div>
          </li>';
        return $return;
    }else{
       return '<li><a id="copes" href="'.urlToPage('points').'"><span class="label label-default"><span class="glyphicon glyphicon-euro"></span>'.$GLOBALS['acc']->getPoints().'</span></a></li>
        <li class="dropdown">
        <a href="'.urlToPage('chat').'"><span class="glyphicon glyphicon-comment"></span><span id="message-badge" class="badge badge-important"></span></a>



        </li>
        <li class="dropdown">
        <a class="dropdown-toggle notifications" data-toggle="dropdown" href="#" ><span class="glyphicon glyphicon-th"></span> <span id="notification-badge" class="badge badge-important"></span></a>
         <ul style="width: 300px;" id="notifications" class="dropdown-menu" role="menu" aria-labelledby="dLabel">
         <center><img src="images/loadinghuge.gif" alt="Loading..." height="16" weight="16"/></center>
         </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="'.getAccountAvatarURL($_SESSION['userId']).'" width="36px" heigth="36px" />  '.$GLOBALS['acc']->getNick().'<b class="caret"></b></a>
          <ul class="dropdown-menu">
           '.($GLOBALS['acc']->getGroup()==2?'<li><a href="'.urlToPage('developer_panel').'"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;Painel do Desenvolvedor</a></li>':'').'

            '.($GLOBALS['acc']->isAdmin()?'<li><a href="'.urlToPage('admin_panel').'"><span class="glyphicon glyphicon-glass"></span>&nbsp;Administração</a></li>':'').'
            <li><a href="'.urlToPage('profile').'&value1='.$_SESSION['userId'].'"><span class="glyphicon glyphicon-user"></span>&nbsp;Meu Perfil</a></li>
            <li><a href="'.urlToPage('edit_profile').'"><span class="glyphicon glyphicon-edit"></span>&nbsp;Editar Perfil</a></li>
            <li class="divider"></li>
            <li><a href="#" class="logout"><span class="glyphicon glyphicon-off"></span>&nbsp;Logout</a></li>
          </ul>
        </li>';
    }
}
static public function getLeftMenus(){
    $return = '';
    foreach($GLOBALS['menus'] as $menu){

                switch($menu->getType()){
                    case NavigationBar::TYPEPAGE:
                        $return .= '<li '.($GLOBALS['curpage']==$menu->getValue() ? 'class="active"' : '').'><a href="'.urlToPage($menu->getValue()).'"><img style="max-width: 16px;margin-top: -5px;" src="'.URL.'/images/'.$menu->getValue().'.png"/><b>  '.$menu->getName().'</b></a></li>';
                        break;
                    case NavigationBar::TYPEEXTERNAL:
                        $return .= '<li><a href="'.$menu->getValue().'">'.$menu->getName().'</a></li>';
                        break;
                    case NavigationBar::TYPECLASS:
                        $return .= '<li><a class="'.$menu->getValue().'" href="#">'.$menu->getName().'</a></li>';
                        break;
        }
}
    return $return;
}
}