<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 24/06/14
 * Time: 13:19
 */
require '../libs/ajax.include.php';
error_reporting(0);
if(!isLogged()) exit;
$notification = new Notification();
$notification->to_user = $_SESSION['userId'];
if($notification->getCount()>0){
    if($_REQUEST['count']){
        echo $notification->getUnSeenCount();
    }elseif($_REQUEST['unseen']){
        $unseen_ids = array();
        foreach($notification->getAllNotificationsFromUser() as $notificacao){
         $notificacao = $notificacao['notifications'];
         if($notificacao['seen']==0) $unseen_ids[] = $notificacao['id'];
        }
        echo json_encode($unseen_ids);
    }elseif($_REQUEST['not']){

    foreach($notification->getAllNotificationsFromUser() as $notificacao){
        $notificacao = $notificacao['notifications'];

        $from_acc = new Account($notificacao['from_acc']);
        switch($notificacao['type']) {
            case "custom":
                ?>
                <li id="notification_<?=$notificacao['id'];?>">
                    <div>
                        <span class="glyphicon glyphicon-envelope" style="font-size: 3.5em;vertical-align: middle;float:left; display:inline-block;"></span><?=$notificacao['reference'];?></b>!
                        <a href="javascript:void(0)" onclick="DeleteNotification(<?=$notificacao['id']?>)"><span class="glyphicon glyphicon-trash" style="font-size: 1.5em;float: right;"></span></a>
                        <br/>
                        <br />
                        <div id="notification_text" style="float: right;font-size: 0.8em;color: #C0C0C0"><?=format_interval($notificacao['data'])?></div>
                        <hr size=3>
                    </div>
                </li>
                <?php
                break;
            case "friend_request":
                ?>
                <li id="notification_<?=$notificacao['id'];?>">
                    <div style="width:350px;padding:5px;">
                        <a href="<?=urlToPage('profile')?>&value1=<?=$from_acc->getId();?>"><img src="<?=getAccountAvatarURL($from_acc->getId());?>" width="50px" height="50px" style="vertical-align: top;float:left; display:inline-block;"/></a>&nbsp;<a href="<?=urlToPage('profile')?>&value1=<?=$from_acc->getId();?>"><?=$from_acc->getNick();?></a> gostaria de ser seu amigo!<br />
                        &nbsp;<a href="#" onclick="addFriend(<?=$from_acc->getId();?>, <?=$notificacao['id']?>, true);"><button type="button" class="btn-small btn btn-success">Aceitar</button></a>&nbsp;&nbsp;<a href="#" onclick="denyFriend('<?=$from_acc->getId();?>', <?=$notificacao['id']?>, true);"><button type="button" class="btn-small btn btn-danger">Recusar</button></a>
                        <div id="notification_text" style="float: right;font-size: 0.8em;color: #C0C0C0"><?=format_interval($notificacao['data'])?></div>
                        <hr size=3>
                    </div>
                </li>
                <?php
                break;
            case "win_points":
                ?>
                <li id="notification_<?=$notificacao['id'];?>">
                    <div>
                        <span class="glyphicon glyphicon-euro" style="font-size: 3.5em;vertical-align: middle;float:left; display:inline-block;"></span>Você ganhou <b><?=$notificacao['reference'];?> Copes</b>!
                        <a href="javascript:void(0)" onclick="DeleteNotification(<?=$notificacao['id']?>)"><span class="glyphicon glyphicon-trash" style="font-size: 1.5em;float: right;"></span></a>
                        <br/>
                        <br />
                        <div id="notification_text" style="float: right;font-size: 0.8em;color: #C0C0C0"><?=format_interval($notificacao['data'])?></div>
                        <hr size=3>
                    </div>
                </li>
                <?php
                break;
                case "buy_points":
                ?>
                    <li id="notification_<?=$notificacao['id'];?>">
                        <div>
                            <span class="glyphicon glyphicon-euro" style="font-size: 1.5em;"></span>Você comprou <?=$notificacao['reference'];?> Copes!
                            <a href="javascript:void(0)" onclick="DeleteNotification(<?=$notificacao['id']?>)"><span class="glyphicon glyphicon-trash" style="font-size: 1.5em;float: right;"></span></a>
                            <br />
                            <br />
                            <div id="notification_text" style="float: right;font-size: 0.8em;color: #C0C0C0"><?=format_interval($notificacao['data'])?></div>
                            <hr size=3>
                        </div>
                    </li>
               <?php
                break;
            //TODO: add cases for other notifications
        }
    }
    }
}