<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 26/07/14
 * Time: 19:26
 */
require '../libs/ajax.include.php';

if(!isLogged()) exit;
if(!gameExists(@$_REQUEST['game'], true)) exit;
$acc = new Account($_SESSION['userId']);
if($acc->temGame($_REQUEST['game'])){
$game = new Game($_REQUEST['game']);
    $game->addDownload();
    $myFile = $game->getDirectDownloadPath();
    header("Cache-Control: public, must-revalidate"); // Avoid this line
    header("Pragma: public"); // Add this line
    header("Pragma: hack"); // Avoid this line
    header("Content-Type: audio/mp3");
    header("Content-Length: " .(string)(filesize($myFile)) ); // Avoid this line
    header('Content-Disposition: attachment; filename="'.$game->getNome().' - '.$mysql->get('versions', 'versao', 'id='.$game->getUltimaVersao()).'.'.$game->getExtension().'"');
    header('Content-Length: ' . filesize($myFile)); // Add this line
    header("Content-Transfer-Encoding: binary\n");
    ob_clean(); // Add this line

    readfile($myFile);
}