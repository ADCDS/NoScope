<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 11/06/14
 * Time: 21:42
 */
error_reporting(0);
require '../libs/ajax.include.php';
if(isset($_POST['page'])){
    $page = $_POST['page'];
    if(include('../pages/'.$page.'.php')){
        echo $layout_header.$main_content;
    }
}

?>