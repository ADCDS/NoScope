/**
 * Created by Particular on 24/06/14.
 */
var unseen_id_array = new Array();
function CheckUpdates()
{
    jQuery.ajax({url:"ajax/notifications.php", data:"unseen=1", dataType:"html", success: function(msg) {
        if (msg != "") {
            unseen_id_array = msg;
        }
    }
    });
    jQuery.ajax({url:"ajax/notifications.php", data:"count=1", dataType:"html", success: function(msg) {
        if (msg != "") {
            var unseen = parseInt(msg);
        }
            if (unseen > 0) {
                $('#notification-badge').css("display", "inline");
                $('#notification-badge').html(unseen);
            }
    }
    });
    jQuery.ajax({url:"ajax/notifications.php", data:"not=1", dataType:"html", success: function(msg) {
        if (msg != "") {
            jQuery('#notifications').html(msg);
        } else {jQuery('#notifications').html("No notifications...");}
    }
    });
}
function DeleteNotification(id) {
    jQuery.ajax({url:"ajax/notification_update.php", data:"notification="+id+"&action=delete", dataType:"html", success: function(msg) {
        $("#notification_"+id).hide();
    }
    });
}
function SeenNotification() {
    jQuery.ajax({url:"ajax/notification_update.php", data:"notifications="+JSON.stringify(unseen_id_array)+"&action=seen", dataType:"html", success: function(msg) {
        setTimeout(function() {
            $('#notification-badge').css("display", "none");
            $('#notification-badge').html("");
        },1000);
    }
    });
}
$(document).ready(function() {
    $('.notifications').click(function() {
        //TODO: stop CheckUpdates interval and restart menu closes
         SeenNotification();
    });
    $('.dropdown-menu').click(function(event){
        event.stopPropagation();
    });
});
function CheckMessages(){
    jQuery.ajax({url:"ajax/messages.php", dataType:"html", success: function(msg) {
       if(parseInt(msg)>0)
        $('#message-badge').html(msg);
    }
    });
}
CheckUpdates();
CheckMessages();
var intervalId = setInterval(function(){CheckUpdates();CheckMessages();},10000);