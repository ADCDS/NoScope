<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Particular
 * Date: 16/03/14
 * Time: 20:37
 * To change this template use File | Settings | File Templates.
 */
ob_start("ob_gzhandler");

error_reporting(0);
ini_set('display_errors', '0');
session_start();
require 'libs/constants.php';
require 'libs/functions_essentials.php';
require 'system/load.php';
require 'libs/functions.php';
require 'system/load.page.php';


if(!ONLYPAGE){
    //CARREGA LAYOUT
    require 'system/load.layout.php';

}else{//IMPRIME APENAS A PAGINA NUA, $main_content
    printErrors($warning);

    echo $main_content;
}
?>
