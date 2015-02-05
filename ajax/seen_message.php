<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 15/07/14
 * Time: 10:58
 */
require '../libs/ajax.include.php';
if(!isLogged()) exit;

if(@$_REQUEST['mid']){
    $mysql->update('accounts_has_messages',array('seen'=>1), 'messages_id='.$_REQUEST['mid'].' AND to_account='.$_SESSION['userId']);
}