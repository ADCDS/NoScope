<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 24/06/14
 * Time: 16:47
 */
require '../libs/ajax.include.php';
$acc = new Account($_SESSION['userId']);
if($acc->temGame(23)){
    echo "tem";
}else{
    echo "não tem";
}
