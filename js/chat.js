/**
 * Created by Particular on 07/07/14.
 */
var to_friend = null;
var to_friendname;
var me = $.cookie('uid');
var cont = [];
//create a new WebSocket object.

var wsUri = "ws://"+DOMAIN+":9000/GameSite/ajax/chat_server.php";
websocket = new WebSocket(wsUri);
websocket.onopen = function (ev) { // connection is open
}

$('.friend').each(function () {
    cont[$(this).data('id')] = parseInt($('.badgeid_' + $(this).data('id')).data('cont'));
    $(this).click(function () {
        if (to_friend != null) { //Ta selecionando outro
            $('#chatarea' + to_friend).hide();
        }
        to_friend = $(this).data('id');
        to_friendname = $(this).data('name');
        if (!createChatBox(to_friend)) {
            if ($('#chatarea' + to_friend).is(':hidden')) {
                $('#chatarea' + to_friend).show();
                $('#message'+to_friend).focus();
            }
        }
        cont[$(this).data('id')] = 0;
        $('.badgeid_' + to_friend).html("");
        $('#btn-chat'+to_friend).click(function () { //use clicks message send button
sendMessage();
        });



    });
});
//#### Message received from server?
websocket.onmessage = function (ev) {
    var msg = JSON.parse(ev.data); //PHP sends Json data
    var mid = msg.mid;
    var type = msg.type; //message type
    var umsg = msg.message; //message text
    var from_id = msg.from_id;
    var timestamp = msg.timestamp;
    if (type == 'usermsg') {
        cont[from_id]++;
        if (from_id != me && from_id != to_friend) {
            $('.friend').each(function () {
                if ($(this).data('id') == from_id) {
                    createChatBox(from_id);//Cria chatbox se recebeber mensagem e nao houver chatboxcriada
                    to_friend = from_id;//Define as variaveis globais necessarias para continuar o programa
                    me = $.cookie('uid');
                    $('#message_box' + from_id).append("<li class=\"left clearfix\"><span class=\"chat-img pull-left\"><img src=\"" + MAINURL + "/ajax/avatar.php?id=" + from_id + "\" alt=\"User Avatar\" width=\"50px\" heigth=\"50px\" class=\"img-circle\"> </span><div class=\"chat-body clearfix\"><div class=\"header\"></div> <p style=\" text-align: left; \">" + umsg + "</p><small class=\"pull-left text-muted\"><span class=\"glyphicon glyphicon-time\"></span>" + timestamp + "</small></div></li>");
                    
					$('.badgeid_' + from_id).html(cont[from_id] + ' Novas Mensagens');
                    $('#message'+from_id).focus();
		
                }
            });
        } else if (from_id != me) {
            $('#message_box' + from_id).append("<li class=\"message left clearfix\"><span class=\"chat-img pull-left\"><img src=\"" + MAINURL + "/ajax/avatar.php?id=" + from_id + "\" alt=\"User Avatar\" width=\"50px\" heigth=\"50px\" class=\"img-circle\"> </span><div class=\"chat-body clearfix\"><div class=\"header\"></div> <p style=\" text-align: left; \">" + umsg + "</p><small class=\"pull-left text-muted\"><span class=\"glyphicon glyphicon-time\"></span>" + timestamp + "</small></div></li>");
            jQuery.ajax({url:"ajax/seen_message.php", data:"mid="+mid});
        } else {
		$('#message' + to_friend).val(''); //reset text
            $('#message_box' + to_friend).append("<li class=\"message right clearfix\"><span class=\"chat-img pull-right\"><img src=\"" + MAINURL + "/ajax/avatar.php?id=" + from_id + "\" alt=\"User Avatar\" width=\"50px\" heigth=\"50px\" class=\"img-circle\">                       </span>                     <div class=\"chat-body clearfix\">                         <div class=\"header\">                                             </div>                         <p style=\"text-align: right;\">              " + umsg + " 			 </p>             <small class=\"pull-right text-muted\">                 <span class=\"glyphicon glyphicon-time\"></span>" + timestamp + "</small></div></li>");
        }
	 }
    if (type == 'system') {
        $('#message_box').append("<div class=\"system_msg\">" + umsg + "</div>");
    }
	$('#caixachat'+to_friend).scrollTop($('#caixachat'+to_friend).prop("scrollHeight"));
    
};

websocket.onerror = function (ev) {
    $('#message_box' + to_friend).append("<div class=\"system_error\">Error Occurred - " + ev.data + "</div>");
};
websocket.onclose = function (ev) {
    $('#message_box' + to_friend).append("<div class=\"system_msg\">Connection Closed</div>");
};

$(document).keydown(function (key) {
    // "Enter" = 13
    if (key.keyCode == '13') {
       sendMessage();
        }
});

function createChatBox(uid) {
    if (!$('#chatarea' + uid).length > 0) { //Se não existe nenhuma chatbox desse usuario
        var uid_name = $('#friend_' + uid).data('name');
        $('#chatbox').append("<div id=\"chatarea"+uid+"\"><div class=\"row\">       <div class=\"btn-group pull-right\" style=\"margin: 5px;\">             <button type=\"button\" class=\"btn btn-default btn-xs dropdown-toggle\" data-toggle=\"dropdown\">                 <span class=\"glyphicon glyphicon-cog\">         </span>               </button>             <ul class=\"dropdown-menu slidedown\">                 <li>           <a onclick=\"loadHistory()\" style=\"cursor: pointer\">             <span class=\"glyphicon glyphicon-time\">                           </span>             Ver Histórico           </a>         </li>       <li class=\"divider\">         </li>                 <li>           <a onclick=\"$('#chatbox').html('')\" style=\"cursor: pointer\">             <span class=\"glyphicon glyphicon-off\">             </span>             Fechar Conversa          </a>         </li>               </ul>            </div>     <div class=\"panel panel-default\">     <div class=\"panel-heading\">       <h3 class=\"panel-title\">         Chat com " + uid_name + "       </h3>           </div>         <div id=\"caixachat"+uid+"\" class=\"panel-body\" style=\" overflow-y: scroll; height: 400px; \">             <ul id=\"message_box" + uid + "\" class=\"chat\"></ul>           </div>         <div class=\"panel-footer\">             <div class=\"input-group\">    <input id=\"message"+uid+"\" type=\"text\" class=\"form-control input-sm\" placeholder=\"Digite sua mensagem aqui...\">               <span class=\"input-group-btn\">                     <button class=\"btn btn-warning btn-sm\" id=\"btn-chat"+uid+"\">             Send           </button>                   </span>               </div>           </div>       </div> </div>  ");
        if(cont[uid]>0){//Se existem mensagens armazenadas
            jQuery.ajax({url:MAINURL+"/ajax/get_messages.php", data:"fid="+uid+"&unseen=1", dataType:"json", success: function(msg) {//Pega as mensagens armazenadas
 
				$.each(msg, function(){
                  $('#message_box' + uid).append("<li class=\"left clearfix\"><span class=\"chat-img pull-left\"><img src=\"" + MAINURL + "/ajax/avatar.php?id=" + uid + "\" alt=\"User Avatar\" width=\"50px\" heigth=\"50px\" class=\"img-circle\"> </span><div class=\"chat-body clearfix\"><div class=\"header\"></div> <p style=\" text-align: left; \">" + this.message + "</p><small class=\"pull-left text-muted\"><span class=\"glyphicon glyphicon-time\"></span>" + this.data + "</small></div></li>");
              });
            }});
          }
        return true;
    } else {
        return false;
    }
}
function sendMessage(){
    var mymessage = $('#message'+to_friend).val(); //get message text
    if(mymessage==""){
        return false;
    }

    //prepare json data
    var msg = {
        message: mymessage,
        to_id: to_friend,
        from_id: me
    };
    //convert and send data to server
    websocket.send(JSON.stringify(msg));
}
function loadHistory(){
    jQuery.ajax({url:"ajax/get_messages.php", data:"fid="+to_friend, dataType:"json", success: function(msg) {//Pega as mensagens armazenadas
        $('#message_box' + to_friend).html('');//Deleta as mensages de historico antigas
        
        $.each(msg, function(){
		
           if (this.sender_account != me) {
			
                 $('#message_box' + to_friend).append("<li class=\"message left clearfix\"><span class=\"chat-img pull-left\"><img src=\"" + MAINURL + "/ajax/avatar.php?id=" + this.sender_account + "\" alt=\"User Avatar\" width=\"50px\" heigth=\"50px\" class=\"img-circle\"> </span><div class=\"chat-body clearfix\"><div class=\"header\"></div> <p style=\" text-align: left; \">" + this.message + "</p><small class=\"pull-left text-muted\"><span class=\"glyphicon glyphicon-time\"></span>" + this.data + "</small></div></li>");
                 //jQuery.ajax({url:"ajax/seen_message.php", data:"mid="+mid});
            } else {
                $('#message_box' + to_friend).append("<li class=\"message right clearfix\"><span class=\"chat-img pull-right\"><img src=\"" + MAINURL + "/ajax/avatar.php?id=" + this.sender_account + "\" alt=\"User Avatar\" width=\"50px\" heigth=\"50px\" class=\"img-circle\">                       </span>                     <div class=\"chat-body clearfix\">                         <div class=\"header\">                                             </div>                         <p style=\"text-align: right;\">              " + this.message + " 			 </p>             <small class=\"pull-right text-muted\">                 <span class=\"glyphicon glyphicon-time\"></span>" + this.data + "</small></div></li>");
            }
        });
    }});
	$('#caixachat'+to_friend).scrollTop($('#caixachat'+to_friend).prop("scrollHeight"));
}
