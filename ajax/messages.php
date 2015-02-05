<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 12/07/14
 * Time: 15:35
 */
require '../libs/ajax.include.php';
if(!isLogged()) exit;
echo getMessagesCountFrom($_SESSION['userId']);
//echo $mysql->query('SELECT COUNT(*) as num FROM accounts_has_messages WHERE seen=0 and to_account='.$_SESSION['userId'].'')[0][0]['num'];
