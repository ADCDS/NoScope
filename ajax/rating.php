<?php

$users_ip = $_SERVER['REMOTE_ADDR'];
require '../libs/ajax.include.php';
if($_REQUEST['value']&&$_REQUEST['gid'])
{
    $value = $_REQUEST['value'];
	$id = $_REQUEST['value'];
    $gid = $_REQUEST['gid'];
    $result = mysql_query("select users_ip from ratings where users_ip='$users_ip' and gid='$gid'");
    $num = mysql_num_rows($result);
    $gid2=str_replace('/game/', '', $gid);
	if($num==0)
	{
        $acc = new Account(@$_SESSION['userId']);
        if($acc->temGame($gid2)){
		$query = "insert into ratings (rating,users_ip,gid) values ('$value','$users_ip','$gid')";
		mysql_query( $query);
		
		$result=mysql_query("select sum(rating) as rating from ratings where gid='$gid'");
		$row=mysql_fetch_array($result);
		
		$rating=$row['rating'];
		
		$quer = mysql_query("select rating from ratings where gid='$gid'");
		$all_result = mysql_fetch_assoc($quer);
		$rows_num = mysql_num_rows($quer);
		if($rows_num > 0){
		$get_rating = floor($rating/$rows_num);
		$rem =  5 - $get_rating;
		}?>
		
		<?php
		for($k=1;$k<=$get_rating;$k++){?>
		<div class="rating_enb" id="<?php echo $k?>">&nbsp;</div>
		<?php
		}?>
		<?php
		for($i=$rem;$i>=1;$i--){?>
		<div class="rating_dis" id="<?php echo $k?>">&nbsp;</div>
		<?php
		$k++;
		}?>	
		<div class="rating_value"><?php echo ((@$get_rating) ? @$get_rating : '0')?> / 5</div>
		<div class="user_message"><?php echo $rows_num?> votos</div>
	<?php
        }else{
            echo '<div class="rating_message">Você não tem esse jogo!</div>';
        }
        }else{
		echo '<div class="rating_message">Você já votou</div>';
	}
}
if(@$_REQUEST['show']) // show rating again after showing message
{
   $gid = $_REQUEST['show'];
	$result=mysql_query("select sum(rating) as rating from ratings where gid='$gid'");
	$row=mysql_fetch_array($result);
	
	$rating=$row['rating'];
	
	$quer = mysql_query("select rating from ratings gid='$gid'");
	$all_result = mysql_fetch_assoc($quer);
	$rows_num = mysql_num_rows($quer);
	if($rows_num > 0){
	$get_rating = floor($rating/$rows_num);
	$rem =  5 - $get_rating;
	}?>
	<?php
	for($k=1;$k<=$get_rating;$k++){?>
	<div class="rating_enb" id="<?php echo $k?>">&nbsp;</div>
	<?php
	}?>
	<?php
	for($i=$rem;$i>=1;$i--){?>
	<div class="rating_dis" id="<?php echo $k?>">&nbsp;</div>
	<?php
	$k++;
	}?>	
	<div class="rating_value"><?php echo ((@$get_rating) ? @$get_rating : '0')?> / 5</div>
	<div class="user_message"><?php echo $rows_num?> votos</div>
<?php
}?>
