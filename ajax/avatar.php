<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 19/06/14
 * Time: 15:39
 */
if(isset($_GET['id'])){
$name = '../media/avatar/'.$_GET['id'].'.png';
if(!file_exists($name))
    $name = '../media/avatar/default.gif';


$fp = fopen($name, 'rb');
header("Content-Type: image/png");
header("Content-Length: " . filesize($name));
fpassthru($fp);
}