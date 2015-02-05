<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 22/06/14
 * Time: 02:00
 */
require '../libs/ajax.include.php';
if(isLogged()){
    $acc = new Account($_SESSION['userId']);
    setcookie('uid', $acc->getId(), null, "/");
    setcookie('group', $acc->getGroup(), null, "/");
    setcookie('copes', $acc->getPoints(), null, "/");
    setcookie('validated', ($acc->isValidated())?'1':'0', null, "/");
    die('0');
}else{
            die('1');
}