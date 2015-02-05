<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 28/06/14
 * Time: 11:21
 */
require '../libs/ajax.include.php';
if(!isLogged()) exit;
$acc = new Account();
$acc->loadById($_SESSION['userId']);
$mysql = new mysql();
if(@$_REQUEST['add_friend']){
if(checkFriendRequest($_SESSION['userId'], $_REQUEST['add_friend'], false, true)){//Existe um pedido de amizade ou uma amizade ja feita
    $acc->acceptFriendRequest($_REQUEST['add_friend']);//Aceita o pedido de amizade da outra conta
    die('0');
}else{//Não há amizade nem pedido
    $acc->sendFriendRequest($_REQUEST['add_friend']);//Manda um novo pedido de amizade
    die('1');
}
}elseif(@$_REQUEST['remove_friend']){
if(checkFriendRequest($_SESSION['userId'], $_REQUEST['remove_friend'], true)){//Checa se existe uma amizade aceita entre as contas
    $acc->removeFriend($_REQUEST['remove_friend']);
    die('2');
}
}elseif(@$_REQUEST['deny_friend']){
    if(checkFriendRequest($_SESSION['userId'], $_REQUEST['deny_friend'], false, true)){//Existe um pedido de amizade ou uma amizade ja feita
        $acc->denyFriendRequest($_REQUEST['deny_friend']);
      die('3');
    }
}
die('4');