<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 3/26/14
 * Time: 10:07 AM
 */


function printErrors($warning){
$data = '';
    if($warning->getErrorsCount(Warnings::TYPE_ALL)>0){//Há uma notificação na Página
        foreach($warning->getErrorsList() as $erro){
            $data .=  $erro->getFormattedText();
        }
    return $data;
    }else{
        return false;
    }
}


?>