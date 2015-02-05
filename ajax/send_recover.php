<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 21/10/14
 * Time: 12:54
 */

include('../libs/ajax.include.php');

if(isset($_REQUEST['Email'])){
    $acc = new Account();
    $acc->loadByEmail($_REQUEST['Email']);
    if($mysql->insert('recover',array('uid' => $acc->getId(),'code' => $r=generateValidationCode(8)))){
        // mail($this->getEmail(),'Recuperação de Senha na '.NAME,'Aqui está seu código: '.$r.', acesse http://localhost/GameSite/?page=recover&c='.$r);
         die('0');
    }
}