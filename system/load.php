<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Particular
 * Date: 16/03/14
 * Time: 20:31
 * To change this template use File | Settings | File Templates.
 */
$main_content = '';
$layout_header = '';
$layout_bottom = '';

function autoLoadClass($className)
{

    if(!class_exists($className))
        if(file_exists(PATH.'/classes/' . strtolower($className) . '.php'))
            include_once(PATH.'/classes/' . strtolower($className) . '.php');
        else
            die('Cannot load class <b>' . $className . '</b>, file <b>' .PATH.'/classes/' . strtolower($className) . '.php. </b> doesn\'t exist');
}

spl_autoload_register('autoLoadClass');
$mysql = new mysql();
if(isset($_SERVER['HTTP_REFERER'])){//Se ja existe uma page, coloca ela no historico
    $GLOBALS['lastpage'] = $_SERVER['HTTP_REFERER'];
}else{
    $GLOBALS['lastpage'] = urlToPage('index');
}

//Carrega o GET da Page

if(isset($_GET['page'])){
    $GLOBALS['curpage'] = $_GET['page'];
}else{
    $GLOBALS['curpage'] = 'index';
}

//Inicia as notificações
$warning = new Warnings();


if(isLogged()){
    $GLOBALS['acc'] = new Account();
    $GLOBALS['acc']->loadById($_SESSION['userId']);

//Checa se o usuario está banido
    if($GLOBALS['acc']->isBanned()){
        $warning->addError(null, 'Você está banido', 'Desconectado');
        logout();
    }
}
NavigationBar::loadMenus();
?>