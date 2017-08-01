<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 06/10/14
 * Time: 09:40
 BASEADO EM Ajax Rating System
http://www.99points.info/2010/05/ajax-rating-system-create-simple-ajax-rating-system-using-jquery-ajax-and-php/
 */
$gid = $_REQUEST['gid'];
include '../classes/mysql.php';
$mysql = new mysql();
$show = (isset($_GET['show'])?$_GET['show']:0);
if($show!=1){
?>
<script type="text/javascript" src="../js/jquery-2.1.1.min.js"></script>
<link href="../css/rating.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    // <![CDATA[
    $(document).ready(function(){
        $('#loader').hide();
        $('#inner').children().click(function(){
            var a = $(this).attr("id");
            $.post("../ajax/rating.php?value="+a+"&gid=<?=$gid;?>", {
            }, function(response){
                $('#inner').fadeOut();
                $('#inner').html(unescape(response));
                $('#inner').fadeIn();
                setTimeout("hideMesg();", 2000);
            });
        });
    });

    function hideMesg(){

        $('.rating_message').fadeOut();
        $.post("rating.php?show=1&gid=<?=$gid;?>", {
        }, function(response){
            $('#inner').html(unescape(response));
            $('#inner').fadeIn('slow');
        });
    }
    // ]]>
</script>
<?php
}
$result=mysql_query("select sum(rating) as ratings from ratings where gid='$gid'");
$row=mysql_fetch_array($result);

$rating=$row['ratings'];

$quer = mysql_query("select rating from ratings where gid='$gid'");
$all_result = mysql_fetch_assoc($quer);
$rows_num = mysql_num_rows($quer);

if($rows_num > 0){
    $get_rating = floor($rating/$rows_num);
    $rem =  5 - $get_rating;
}
else
{
    $rem = 5;
}

if($show!=1){
?>

<div  align="center" >
    <div id="container">
        <div id="outer">
            <div id="inner">
                <?php
                for($k=1;$k<=@$get_rating;$k++){?>
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
            </div>
        </div>

</div>
</div>

<?php
}else{
?>

            
                <?php
                for($k=1;$k<=@$get_rating;$k++){?>
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
           <script type="text/javascript">
		    $('#inner').children().click(function(){
            var a = $(this).attr("id");
            $.post("../ajax/rating.php?value="+a+"&gid=<?=$gid;?>", {
            }, function(response){
                $('#inner').fadeOut();
                $('#inner').html(unescape(response));
                $('#inner').fadeIn();
                setTimeout("hideMesg();", 2000);
            });
        });
		   </script>

<?php

}
?>

