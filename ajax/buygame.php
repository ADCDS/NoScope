<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 26/07/14
 * Time: 17:37
 */
require '../libs/ajax.include.php';
if(!isLogged()) exit;
if(!gameExists($_REQUEST['game'], true)) exit;
$acc = new Account($_SESSION['userId']);
$game = new Game($_REQUEST['game']);
if($game->getDesenvolvedor()!=$_SESSION['userId']){
if(!$acc->temGame($_REQUEST['game'])){
if($acc->comprarGame($_REQUEST['game'])){
    $game->addCope();
    die("0");
}else{
    die("1");
}
}
}