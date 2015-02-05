<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 27/05/14
 * Time: 00:57
 */
require '../libs/ajax.include.php';

if(isset($_POST['UserName'])){//Nego está tentando fazer Login
    $acc = new Account();
    $acc->loadByNick($_POST['UserName']);
    if((accExists($_POST['UserName'], false, 'nick'))&&($acc->checkPassword($_POST['Password']))){
    if(!$acc->isBanned()){
    $_SESSION['islogged'] = true;
    $_SESSION['userId'] = $acc->getId();
    $acc->setLastLogin();
        setcookie('uid', $acc->getId(), null, "/");
        setcookie('group', $acc->getGroup(), null, "/");
        setcookie('copes', $acc->getPoints(), null, "/");
        setcookie('validated', ($acc->isValidated())?'1':'0', null, "/");
        $year = time() + 31536000;
        if(isset($_POST['renemberme'])) {
            setcookie('remember_me', $_POST['UserName'], $year, "/");
        }
        else{
            if(isset($_COOKIE['remember_me'])) {
                $past = time() - 100;
                setcookie('remember_me', null, $past, "/");
            }
        }
    }else{
     die("2");
    }
    die("0");
    }else{
     die("1");
    }
}
?>