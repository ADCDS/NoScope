<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 5/9/14
 * Time: 11:47 PM
 */
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
function format_uri( $string, $separator = '-' )
{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and');
    $string = mb_strtolower( trim( $string ), 'UTF-8' );
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
}
function getCategories(){
    global $mysql;
    return $mysql->query('SELECT * FROM category');
}
function gameExists($id, $on = false){
    global $mysql;
    if(!$on){
    $result = $mysql->get('games','id','id="'.$id.'"');
    }else{
        $result = $mysql->get('games','id','aprovado=1 AND publicado=1 AND id="'.$id.'"');
    }
    if($result!=""){
        return true;
    }else{
        return false;
    }
}
function newsExists($id){
    global $mysql;
    $result = $mysql->get('news','id','id="'.$id.'"');
    if($result!=""){
        return true;
    }else{
        return false;
    }
}
function getNewsUrl($id){
    return urlToPage('news').'&nid='.$id;
}
function maxDotDotDot($string, $max){
    return trim(substr($string, 0, $max)).'...';
}
function getDeveloperGameList($desenvolvedor = null){
    global $mysql;
    if($desenvolvedor){
        return $mysql->query('SELECT * FROM games WHERE aprovado=1 AND desenvolvedor='.$desenvolvedor);
    }else{
        return $mysql->query('SELECT * FROM games WHERE aprovado=1');
    }
}
function getGroupName($group){
    switch($group){
        case 1:
            return 'Membros';
        break;
        case 2:
            return 'Desenvolvedores';
        break;
        case 3:
            return 'Moderadores';
        break;
        case 4:
            return 'Administradores';
        break;
    }
}
function getMessagesFrom($to, $from, $seen = false){
    global $mysql;
    $return  = array();
    if(!$seen){
        foreach($mysql->query('SELECT messages_id FROM accounts_has_messages WHERE seen=0 AND to_account='.$to) as $id){
            $return[] =  $mysql->query('SELECT * FROM messages WHERE id='.$id['accounts_has_messages']['messages_id'].' AND sender_account ='.$from.' ORDER BY data ASC');
        }
    }else{
        foreach($mysql->query('SELECT messages_id FROM accounts_has_messages WHERE to_account='.$to) as $id){
            $return[] = $mysql->query('SELECT * FROM messages WHERE id='.$id['accounts_has_messages']['messages_id'].' AND sender_account ='.$from.' ORDER BY data ASC');
        }
    }
    return $return;
}
function getMessagesCountFrom($to, $from = false, $seen = false){
    global $mysql;
    $return = 0;
    if(!$seen){
        foreach($mysql->query('SELECT messages_id FROM accounts_has_messages WHERE seen=0 AND to_account='.$to) as $id){
            if($from){
			if(@$mysql->query('SELECT id FROM messages WHERE id='.$id['accounts_has_messages']['messages_id'].' AND sender_account ='.$from)[0]['messages']['id']
			==$id['accounts_has_messages']['messages_id']){
			$return++;
			}
			}else{
			if($mysql->query('SELECT id FROM messages WHERE id='.$id['accounts_has_messages']['messages_id'])[0]['messages']['id']
			==$id['accounts_has_messages']['messages_id']){
            $return++;
			}
        }
		}
    }else{
        foreach($mysql->query('SELECT messages_id FROM accounts_has_messages WHERE seen=1 AND to_account='.$to) as $id){
            if($from){
			if(@$mysql->query('SELECT id FROM messages WHERE id='.$id['accounts_has_messages']['messages_id'].' AND sender_account ='.$from)[0]['messages']['id']==$id['accounts_has_messages']['messages_id']){
            $return++;
			}
			}else{
			if($mysql->query('SELECT id FROM messages WHERE id='.$id['accounts_has_messages']['messages_id'])[0]['messages']['id']==$id['accounts_has_messages']['messages_id']){
			 $return++;
			}
        }
		}
    }
    return $return;
}
function getNick($uid){
   global $mysql;
    if(accExists($uid)){
        return $mysql->get('accounts','nick', 'id = '.$uid);
    }
}
function getName($uid){
   global $mysql;
    if(accExists($uid)){
        return $mysql->get('accounts', 'name', 'id = '.$uid);
    }
}
function getEmail($uid){
   global $mysql;
    if(accExists($uid)){
        return $mysql->get('accounts', 'email', 'id = '.$uid);
    }
}
function getFriendsCount($uid){
    if(accExists($uid)){
       global $mysql;
        return $mysql->query('SELECT count(*) from friends WHERE accepted=1 AND (from_acc ='.$uid.' OR to_acc='.$uid.')')[0][0]['count(*)'];
    }
}
function getFriends($uid, $max = false){

    if(accExists($uid)){
    $friends = array();
   global $mysql;
        ($max)?$result = $mysql->query('SELECT from_acc, to_acc FROM friends WHERE accepted=1 AND (from_acc ='.$uid.' OR to_acc='.$uid.') LIMIT '.$max):$result = $mysql->query('SELECT from_acc, to_acc FROM friends WHERE accepted=1 AND (from_acc ='.$uid.' OR to_acc='.$uid.')');
        foreach($result as $row){
           foreach ($row as $rew){
               if($rew['from_acc']!=$uid) array_push($friends, $rew['from_acc']);
               else
            array_push($friends, $rew['to_acc']);
           }
        }
    return $friends;
    }else{
        return false;
    }
}
function jsRedirect($url){
    if($url==@$_SERVER['HTTP_REFERER'])
        $url = URL;
   return '<script type="text/javascript">
setTimeout(function(){window.location.replace("'.$url.'")}, 5000);
        </script>';
}
function generateValidationCode($size=false){
    if($size){
        return generateRandomString($size,true);
    }
    return generateRandomString(5,true);
}
function generateRandomString($length, $uppercase = null, $lowercase = null){
    if($uppercase)
     return   strtoupper(substr(str_shuffle(md5(time())),0,$length));
    elseif($lowercase)
   return   strtolower(substr(str_shuffle(md5(time())),0,$length));
    else
   return  substr(str_shuffle(md5(time())),0,$length);
}
function checkFriendRequest($to_acc,$from_acc, $accepted = false, $must_be_exact = false){
   global $mysql;
    if($must_be_exact){
     if(!$accepted){
        $result = $mysql->get('friends', 'id', '(to_acc = '.$to_acc.' AND from_acc = '.$from_acc.')');
     }else{
         $result = $mysql->get('friends', 'id', 'accepted=1 AND ((to_acc = '.$to_acc.' AND from_acc = '.$from_acc.'))');
     }
     }else{

    if(!$accepted){
        $result = $mysql->get('friends', 'id', '(to_acc = '.$to_acc.' AND from_acc = '.$from_acc.') OR (to_acc = '.$from_acc.' AND from_acc = '.$to_acc.')');
    }else{
        $result = $mysql->get('friends', 'id', 'accepted=1 AND ((to_acc = '.$to_acc.' AND from_acc = '.$from_acc.') OR (to_acc = '.$from_acc.' AND from_acc = '.$to_acc.'))');
    }
    }
    if($result)
        return $result;
    return false;
}

function format_interval($timestamp, $granularity = 2) {
    $date = strtotime($timestamp);
	
    $timestamp = strtotime(CURRENT_TIME) - $date;
    $units = array('1 ano|@count anos' => 31536000, '1 semana|@count semanas' => 604800, '1 dia|@count dias' => 86400, '1 hora|@count horas' => 3600, '1 min|@count min' => 60, '1 sec|@count sec' => 1);
    $output = '';
    foreach ($units as $key => $value) {
        $key = explode('|', $key);
        if ($timestamp >= $value) {
            $floor = floor($timestamp / $value);
            $output .= ($output ? ' ' : '') . ($floor == 1 ? $key[0] : str_replace('@count', $floor, $key[1]));
            $timestamp %= $value;
            $granularity--;
        }

        if ($granularity == 0) {
            break;
        }
    }

    return $output ? "há ".$output : date("d/m/Y G:i:s", $date);
}
function notificationBelongToUser($notificationId, $userId){
   global $mysql;
    $result = $mysql->select(array (
        'table' => 'notifications',
        'fields' => 'id',
        'condition' => 'id ='.$notificationId.' AND to_acc ='.$userId,
        'order' => '1',
        'limit' => 50
    ));
    if($result)
        return true;
    return false;

}
function validateString($s){
    if (preg_match("[\W]", $s))
       return true;
    return false;
}
function validateEmail($s){
if((filter_var($s, FILTER_VALIDATE_EMAIL))&&(strlen($s)<=50))
        return true;
    return false;
}
function validateNick($s){
    if((!validateString($s))&&(strlen($s)<=20))
        return true;
    return false;
}
function validateName($s){
    if(strlen($s)<=50)
        return true;
    return false;

}
function validatePassword($s){
    if(strlen($s)<=16&&strlen($s)>=4)
        return true;
    return false;

}
function arrayToJson($array){
    return '&af='.base64_encode(json_encode($array));
}
/*function arrayToURLPOST($posts){
    $return = '';
    $nomes = array_keys($posts);

    for($a=0; $a<count($nomes); $a++){
    $return .= '&'.$nomes[$a].'='.$posts[$nomes[$a]];
    }
    return '&af='.base64_encode(substr($return, 1));
}*/
function getAccountAvatarURL($id){
    if(accExists($id))
    return URL.'/ajax/avatar.php?id='.$id;
  return false;
}
function pageExists($page){
    if(file_exists(PATH.'/pages/'.$page.'.php'))
        return true;
    return false;
}
function urlToPage($page){
if(pageExists($page))
    return URL.'/?page='.$page;
return '#';
}
function accExists($s, $data = false, $cond = 'id'){
    global $mysql;
    if(!$data){
        $result = $mysql->get('accounts','id',$cond.'="'.$s.'"');
        if($result!=""){
            return true;
        }else{
            return false;
        }
    }else{
        $result = $mysql->get('accounts',$data,$cond.'="'.$s.'"');
        if($result!=""){
            return $result;
        }else{
            return false;
        }
    }
}
function concatenateUrl($string){
    return curPageURL().$string;
}

function curPageURL() {
    $pageURL = 'http';
    if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function isLogged($getId = false){
    if(@$_SESSION['islogged']){
        return true;
    }else{
        return false;
    }
}
function logout(){
    session_destroy();

}
function doublemax($mylist){
    $maxvalue=max($mylist);
    while(list($key,$value)=each($mylist)){
        if($value==$maxvalue)$maxindex=$key;
    }
    return array("m"=>$maxvalue,"i"=>$maxindex);
}
function getNextImageName($path){
    $dir = new DirectoryIterator($path);
    $x=0;
    foreach($dir as $file){
      
if($file->isFile()){
      $x++;
        }
	 
    }
    return $x;
}
function renameImages($path){
    $dir = new DirectoryIterator($path);
	$x=0;
    foreach($dir as $file){
     if($file->isFile()){
      rename($path.$file->getFilename(), $path.$x);
	  $x++;
        }
		
    }
}
?>