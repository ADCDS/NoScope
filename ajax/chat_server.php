<?php
//include 'C:/xampp/htdocs/GameSite/libs/functions_essentials.php';
include '../classes/mysql.php';
date_default_timezone_set ('America/Sao_Paulo');
error_reporting(~E_ALL);
ini_set('display_errors', '0');

$host = '172.28.1.145'; //host
$port = '9000'; //port
$null = NULL; //null var
$mysql = new mysql();
//Create TCP/IP sream socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//reuseable port
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

//bind socket to specified host
socket_bind($socket, 0, $port);

//listen to port
socket_listen($socket);

//create & add listning socket to the list
$clients = array($socket);
$id = "";
//start endless loop, so that our script doesn't stop
while (true) {
	//manage multipal connections
	$changed = $clients;
	//returns the socket resources in $changed array
	socket_select($changed, $null, $null, 0, 10);
	
	//check for new socket
	if (in_array($socket, $changed)) {
		$socket_new = socket_accept($socket); //accpet new socket
		$header = socket_read($socket_new, 1024); //read data sent by the socket
		perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake
        $clients[$id] = $socket_new; //add socket to client array
		socket_getpeername($socket_new, $ip); //get ip address of connected socket
		$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected'))); //prepare json data
		send_message($response); //notify all users about new connection
		
		//make room for new socket
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
	}
	
	//loop through all connected sockets
	foreach ($changed as $changed_socket) {	
		
		//check for any incomming data
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			$received_text = unmask($buf); //unmask data
			$tst_msg = json_decode($received_text); //json decode
            //print_r($tst_msg);
			$user_message = strip_tags($tst_msg->message); //message text
            $from_id = $tst_msg->from_id;
            $to_id = $tst_msg->to_id;
			//prepare data to be sent to client
            $now = date('Y-m-d H:i:s');
            $mysql->insert('accounts_has_messages', array('to_account'=>$to_id,'messages_id'=>$mid=$mysql->insert('messages', array('data'=>$now,'message'=>$user_message,'sender_account'=>$from_id))));
            $response_text = mask(json_encode(array('mid'=>$mid,'type'=>'usermsg', 'message'=>$user_message, 'from_id'=>$from_id, 'timestamp'=>$now)));

            send_message($response_text, $from_id, $to_id); //send data
			break 2; //exist this loop
		}
		
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);

			socket_getpeername($changed_socket, $ip);
            echo "ID: ".$found_socket." se desconectou\n";
			unset($clients[$found_socket]);

			//notify all users about disconnected connection
			//$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
			send_message($response);
		}
	}
    flush();
}
// close the listening socket
$mysql->__destruct();
socket_close($sock);

function send_message($msg, $from_id=null, $to_id=null)
{
	global $clients;

	foreach($clients as $cliente_id=>$changed_socket)
	{
        if(($cliente_id==$from_id||$cliente_id==$to_id))
            @socket_write($changed_socket,$msg,strlen($msg));
    }

	return true;
}


//Unmask incoming framed message
function unmask($text) {
	$length = ord($text[1]) & 127;
	if($length == 126) {
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	}
	elseif($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	}
	else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
		$text .= $data[$i] ^ $masks[$i%4];
	}
	return $text;
}

//Encode message for transfer to client.
function mask($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);
	
	if($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif($length > 125 && $length < 65536)
		$header = pack('CCn', $b1, 126, $length);
	elseif($length >= 65536)
		$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}

//handshake new client.
function perform_handshaking($receved_header,$client_conn, $host, $port)
{
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);

   /* if(preg_match("/PHPSESSID=(.*?)(?:;|\r\n)/", $receved_header, $matches)){
        //$sessID = $matches[1];
        //@session_destroy();
        //session_id($sessID);
        //@session_start();
        global $id;
        $id = 18;
        //$id = $_SESSION['userId'];
    }else{
        print('No SESSID');
        return false;
    }*/

	foreach($lines as $line)
	{
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
		{
			$headers[$matches[1]] = $matches[2];
		}
	}
    preg_match('/\b(uid=)(?:\d*)?\b/',$headers['Cookie'],$matches);

    global $id;
    $id =str_replace('uid=', '',$matches[0]);
    echo "ID: ".$id." se conectou\n";
	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	//hand shaking header
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
	"Upgrade: websocket\r\n" .
	"Connection: Upgrade\r\n" .
	"WebSocket-Origin: $host\r\n" .
	"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
	"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($client_conn,$upgrade,strlen($upgrade));
}
