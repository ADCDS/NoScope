<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Particular
 * Date: 16/03/14
 * Time: 19:40
 * To change this template use File | Settings | File Templates.
 */
	date_default_timezone_set ('America/Sao_Paulo');//Fuso Horário
	$date = new DateTime('now');
	$date = $date->format('Y-m-d H:i:s');
    DEFINE('INITIALIZED', true);
    DEFINE('PATH', '/var/www/github/NoScope');//Caminho absoluto do diretório do site
    DEFINE('NAME', "NoScope");//Nome do Site
    DEFINE('URL', 'http://github.adriel.eu/NoScope');//URL Base do sistema
    DEFINE('LAYOUT_NAME', "simple");//Template
    DEFINE('DOMAIN', "github.adriel.eu");//Domínio
    DEFINE('ONLYPAGE', false);//MODO DE DEBUG
    DEFINE('CURRENT_TIME', $date);//Pega o tempo na hora que o script é excecutado
    DEFINE('LAYOUT_URL', URL.'/templates/'.LAYOUT_NAME.'/');//URL do template
    DEFINE('LAYOUT_PATH', PATH.'/templates/'.LAYOUT_NAME.'/');//Caminho do template
    $title = NAME;
?>
