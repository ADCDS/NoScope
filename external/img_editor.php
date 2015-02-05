<?php

include "../libs/functions_essentials.php";
include "../libs/constants.php";
include "../classes/game.php";
include "../classes/mysql.php";
$mysql = new mysql();
$gameid = $_GET['gid'];
$game = new  Game($gameid);
session_start();
if(isLogged()){
if($game->getDesenvolvedor()==$_SESSION['userId']&&$game->isAprovado()) {
$dir = PATH."/media/games/$gameid/images/";
$uploadedfile = @basename($_FILES['uploadedfile']['name']);
if($uploadedfile){
	$target_path = $dir.getNextImageName($dir);
	if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
		$messagecolor = "#990000";
		$message = "Error: Imagem (".$uploadedfile.") não foi salva, ela é grande demais?";
	}else{
		$messagecolor = "#009900";
		$message = "Imagem (".$uploadedfile.") salva.";
	}
}
$deletefile = @$_POST['deletefile'];
if($deletefile!=null){
    unlink($dir.$deletefile);
    renameImages($dir);
	$messagecolor = "#009900";
	$message = "Imagem ".$deletefile." excluida!";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript" type="text/javascript">
function upload(){
	document.getElementById("button").style.display = "none";
	document.getElementById("loading").style.display = "block";
	document.getElementById("uploadForm").submit();
}
function deletefile(f){
	var answer = confirm ("Excluir imagem "+f+"?");
	if (answer){
		document.getElementById("deletefile").value = f;
		document.getElementById("uploadForm").submit();
	}
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
body {
	font: 100% Verdana, Arial, Helvetica, sans-serif;
	margin: 0; /* it's good practice to zero the margin and padding of the body element to account for differing browser defaults */
	padding: 0;
	text-align: center; /* this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
}
.oneColFixCtr #container {
	text-align: left; /* this overrides the text-align: center on the body element. */
}
.oneColFixCtr #mainContent {
	padding: 0 20px; /* remember that padding is the space inside the div box and margin is the space outside the div box */
}
#smallfont{
	font-size:12px;
}
-->
</style></head>

<body class="oneColFixCtr">

<div id="container">
  <div id="mainContent">
  <table border="0" cellspacing="10" cellpadding="0" width="100%">
  <tr bgcolor="#FF3300"><td><div style="color:#FFF;margin:10px;font-size:18px"><img src="../images/file-manager.png" width="32" height="32" style="margin-right:10px"/>Edição da Galeria</div></td></tr>
  <tr bgcolor="#CCCCCC"><td>
  	<table border="0" cellspacing="0" cellpadding="10">
    <tr><td>
     <form id="uploadForm" name="uploadForm" method="post" enctype="multipart/form-data" />
     <input type="hidden" name="deletefile" id="deletefile" value="" />
    <input name="uploadedfile" id="uploadedfile" type="file" size="80"/>
    </td><td align="right">
    <div id="loading" style="display:none"><img src="../images/loading.gif" /></div>
    <input id="button" type="button" value="Upload" style='width:105px' onclick="upload()"/>
    </form>
    </td></tr></table>
</td></tr>
<?php
if(isset($message)){
	echo "<tr bgcolor='".$messagecolor."'><td><div style='color:#FFF;margin:10px'>".$message."</div></td></tr>";
}
?>
<tr><td>
<div id="smallfont">
<table border="0" cellspacing="0" width="100%">
<?php
function byte_convert($bytes){
	$symbol = array('bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb');
	$exp = 0;
	$converted_value = 0;
	if( $bytes > 0 )
	{
	  $exp = floor( log($bytes)/log(1024) );
	  $converted_value = ( $bytes/pow(1024,floor($exp)) );
	}
	return sprintf( '%.2f '.$symbol[$exp], $converted_value );
}
$filearray = array();
$colors = array("#EEEEEE","#CCCCCC");
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
			if ($file != "." && $file != "..") {
           		array_push($filearray, $file);
			}
        }
        closedir($dh);
    }
}
sort($filearray);
for ( $c = 0; $c <= count($filearray)-1; $c++)
{
	$filename = $filearray[$c];
	if ($filename != "")
	{
		echo "<tr bgcolor='".$colors[$c%2]."'><td><a href='".URL."/media/games/".$gameid."/images/".$filename."' target='_blank'><img src='".URL."/media/games/".$gameid."/images/".$filename."' heigth='100px' width='100px'/></a></td><td>".byte_convert(filesize($dir.$filename))."</td><td>Imagem ".$filename."</td><td><a href='javascript:deletefile(\"".$filename."\")'><img src='../images/delete-entry.gif' border='0'/></a></td></tr>";
	}
}
?>
</table>
</div>
</td></tr>
</table>
   </div>
</div>
</body>
</html>
<?php
}
}
    ?>