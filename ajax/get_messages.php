<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 15/07/14
 * Time: 09:00
 */
require '../libs/ajax.include.php';
if(!isLogged()) exit;

if(@$_REQUEST['fid']){
    $array = array();
    $ord = array();
    if(@$_REQUEST['unseen']==1){//Buscando apenas mensagens não vistas
    $messages = getMessagesFrom($_SESSION['userId'],$_REQUEST['fid']);

    foreach($messages as $message){
        $array[] =$message[0]['messages'];
        $ord[] = strtotime($message[0]['messages']['data']);
        $mysql->update('accounts_has_messages', array('seen'=>1), 'messages_id='.$message[0]['messages']['id'].' AND to_account='.$_SESSION['userId']);
    }
    }else{//Pegando Histórico
        $messages = getMessagesFrom($_SESSION['userId'],$_REQUEST['fid'], true);
        $messages2 = getMessagesFrom($_REQUEST['fid'],$_SESSION['userId'], true);
        foreach($messages as $message){//De um para o outro usuario
            $array[] =$message[0]['messages'];
            $ord[] = strtotime($message[0]['messages']['data']);
        }
        foreach($messages2 as $message){//De um para o outro usuario
            $array[] =$message[0]['messages'];
            $ord[] = strtotime($message[0]['messages']['data']);
       }
    }
	
    array_multisort($ord, SORT_ASC, $array);
	
    echo  json_encode(array_filter($array));

}


