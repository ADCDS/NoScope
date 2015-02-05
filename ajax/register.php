<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 07/06/14
 * Time: 21:50
 */
require '../libs/ajax.include.php';
//Caso o Usuário esteja logado bloqueia a página
if((validateNick($_POST['Username']))&&(validateEmail($_POST['Email']))
    &&(validatePassword($_POST['Password']))&&($_POST['Confirm']==$_POST['Password'])){

    (accExists($_POST['Username'], false, 'nick') ? die('2') : true);
    (accExists($_POST['Email'], false, 'email') ? die('3') : true);
    $acc = new Account();
    $acc->setCreateAcc(true);
    $acc->setNick($_POST['Username']);
    $acc->setPassword($_POST['Password']);
    $acc->setEmail($_POST['Email']);
    $acc->setGroup(1);
    if($acc->save()){
        unset($acc);
        $acc = new Account();
        $acc->loadByNick($_POST['Username']);
        $_SESSION['islogged'] = true;
        $_SESSION['userId'] = $acc->getId();
        die("0");
    }else{
        die("1");
    }
}