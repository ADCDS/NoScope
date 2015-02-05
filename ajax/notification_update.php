<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 24/06/14
 * Time: 14:46
 */
require '../libs/ajax.include.php';
 if (!isLogged()) exit;
 $id = $_SESSION['userId'];
 $action = $_REQUEST['action'];

 switch($action) {
     case "seen":
         if (isset($_REQUEST['notifications'])) {
             $notifications = json_decode(json_decode($_REQUEST['notifications']));
             foreach ($notifications as $notification) {
                 if ((is_numeric($notification))&&(notificationBelongToUser($notification,$id))) Notification::Seen($notification);
             }
         }
         break;
     case "delete":
         $notification = $_REQUEST['notification'];
         if((is_numeric($notification))&&(notificationBelongToUser($notification,$id))) Notification::deleteNotification($notification);
         break;
 }
 ?>