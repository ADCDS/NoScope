<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 07/06/14
 * Time: 23:39
 */
require '../libs/ajax.include.php';
if(isLogged()){
    session_regenerate_id();
logout();
   if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            if($name != 'remember_me'){
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
              //  echo $name."</br>";
            }
        }
    }
die("0");
}
die("1");
?>