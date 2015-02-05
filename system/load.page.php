<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 3/21/14
 * Time: 9:52 AM
 */
if(file_exists('./pages/' . strtolower($GLOBALS['curpage']) . '.php'))
    include_once('./pages/' . strtolower($GLOBALS['curpage']) . '.php');
else
    new Error_Critic('404', 'Cannot load page <b>' . $GLOBALS['curpage'] . '</b>, file <b>./pages/' . strtolower($GLOBALS['curpage']) . '.php</b> doesn\'t exist');

?>