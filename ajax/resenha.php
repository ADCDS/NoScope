<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 22/10/14
 * Time: 09:53
 */
include '../libs/ajax.include.php';
if(!isLogged()) exit;
if(isset($_REQUEST['resenha'])&&isset($_REQUEST['gid'])){
$gid = mysql_escape_string($_REQUEST['gid']);
$data = mysql_escape_string($_REQUEST['resenha']);
if($data=="") exit;
$mysql->insert('reviews', array(
'gid' => $gid,
'uid' =>$_SESSION['userId'],
'data' => $data
));

}elseif(isset($_REQUEST['rresenha'])){
    if(is_numeric($_REQUEST['rresenha'])){
        $mysql->delete('reviews', 'id='.$_REQUEST['rresenha'].' AND uid='.$_SESSION['userId']);
    }
}
$resenhas = null;
foreach($mysql->query('SELECT * FROM reviews WHERE gid='.$gid.' ORDER BY date DESC') as $resenha){
    $resenha = $resenha['reviews'];
    $resenhas .= '<li>
                <div class="commenterImage">
                  <img src="'.getAccountAvatarURL($resenha['uid']).'" />
                </div>
                <div class="commentText">
                 ';
    if($resenha['uid']==@$_SESSION['userId']){
        $resenhas .= ' <button type="button" class="close pull-right" onclick="deletaResenha('.$resenha['id'].')">&times;</button>';
    }
    $resenhas .='
                     <p class="">'.$resenha['data'].'</p> <span class="date sub-text"><a href="'.urlToPage('profile').'&value1='.$resenha['uid'].'">'.getNick($resenha['gid']).'</a> '.format_interval($resenha['date']).'</span>

                </div>
            </li>';
}
if($resenhas){
    die($resenhas);
}else{
    die("Não há comentários :(");
}