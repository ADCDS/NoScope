/**
 * Created by Particular on 28/06/14.
 *
 * I'LL BE THE FOR YOU!!!
 */

function addFriend(uid, nid, notification_box){
    $.ajax({url:"ajax/friends.php", data:"add_friend="+uid, dataType:"html", success: function(msg) {
        console.log(msg);
    if(msg == 0){
      $.growl('Vocês são amigos agora!', options_success);
        $('#buttonFriend').remove();
        if(notification_box==null){
        $('#buttonAreaFriend').append('<button id="buttonFriend" type="button" onclick="removeFriend('+uid+')"class="btn btn-danger btn-block">Remover amigo</button>');
        }
    }else if(msg == 1){
      $.growl('Pedido de amizade enviado!', options_success);
        $('#buttonFriend').remove();
        if(notification_box==null){
        $('#buttonAreaFriend').append('<button id="buttonFriend" type="button" class="btn btn-warning btn-block">Solicitação Enviada</button>');
        }
        }else{
      $.growl('Ocorreu um erro ao adicionar esse amigo, tente novamente mais tarde!', options_danger);
        }
        if(nid){
            DeleteNotification(nid);
        }
    }
    });
}
function removeFriend(uid){
    $.ajax({url:"ajax/friends.php", data:"remove_friend="+uid, dataType:"html", success: function(msg) {
        if(msg == 2){
        $.growl('A amizade foi desfeita!', options_success);
            $('#buttonFriend').remove();
            $('#buttonAreaFriend').append('<button id="buttonFriend" type="button" onclick="addFriend('+uid+')"class="btn btn-success btn-block">Adicionar como amigo</button>');
        }else{
        $.growl('Ocorreu um erro ao remover esse amigo, tente novamente mais tarde!', options_danger);
        }
    }
    });
}
function denyFriend(uid, nid, notification_box){
    $.ajax({url:"ajax/friends.php", data:"deny_friend="+uid, dataType:"html", success: function(msg) {
       if(msg == 3) {
           $.growl('O pedido de amizade foi negado!', options_success);
           $('#buttonFriend').remove();
           if(notification_box==null){
           $('#buttonAreaFriend').append('<button id="buttonFriend" type="button" onclick="denyFriend('+uid+')" class="btn btn-success btn-block">Adicionar como amigo</button>');
           }
       }else{
           $.growl('Ocorreu um erro ao negar este pedido de amizade!', options_danger);
       }
        if(nid){
            DeleteNotification(nid);
        }
    }
    });
}
