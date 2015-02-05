<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 27/05/14
 * Time: 01:39
 */
session_start();
include 'constants.php';
include 'functions_essentials.php';
function autoLoadClass($className)
{

    if(!class_exists($className))
        if(file_exists(PATH.'/classes/' . strtolower($className) . '.php'))
            include_once(PATH.'/classes/' . strtolower($className) . '.php');
        else
            die('Cannot load class <b>' . $className . '</b>, file <b>./classes/class.' . strtolower($className) . '.php</b> doesn\'t exist');
}
spl_autoload_register('autoLoadClass');
$mysql = new mysql();
?>