<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Particular
 * Date: 16/03/14
 * Time: 19:40
 * To change this template use File | Settings | File Templates.
 */
	date_default_timezone_set ('America/Sao_Paulo');
	$date = new DateTime('now');
	$date = $date->format('Y-m-d H:i:s');
    DEFINE('INITIALIZED', true);
    DEFINE('PATH', '/var/www/github/NoScope');
    DEFINE('NAME', "NoScope");
    DEFINE('URL', 'http://github.adriel.eu/NoScope');
    DEFINE('LAYOUT_NAME', "simple");
    DEFINE('DOMAIN', "github.adriel.eu");
    DEFINE('ONLYPAGE', false);
    DEFINE('CURRENT_TIME', $date);
    DEFINE('LAYOUT_URL', URL.'/templates/'.LAYOUT_NAME.'/');
    DEFINE('LAYOUT_PATH', PATH.'/templates/'.LAYOUT_NAME.'/');
    $title = NAME;
?>
