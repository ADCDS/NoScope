<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 10/10/14
 * Time: 18:44
 */
include '../classes/mysql.php';
include '../classes/account.php';
include '../libs/functions_essentials.php';
include '../libs/constants.php';
session_start();
$mysql = new mysql();
$id_post = $_GET['nid'];
$sql = mysql_query("SELECT * FROM comments WHERE news_id = '$id_post'") or die(mysql_error());
?>
<link href="../css/comments-example.css" rel="stylesheet" type="text/css"/>
<link href="../css/comments-style.css" rel="stylesheet" type="text/css"/>
<script type="application/javascript" src="../js/jquery-2.1.1.min.js"></script>
<?php
while($affcom = mysql_fetch_assoc($sql)){
    $uid = $affcom['accounts_id'];
    $cacc = new Account($uid);
    $name = $cacc->getName()!=''?$cacc->getName():$cacc->getNick();
    $comment = $affcom['comment'];
    $date = $affcom['date'];
    $default = "mm";
    $size = 35;
    $grav_url = getAccountAvatarURL($uid);

    ?>
    <div id="cm_<?php echo $affcom['id']; ?>" class="cmt-cnt">
        <img src="<?php echo $grav_url; ?>" />
        <div class="thecom">
            <h5><?php echo $name; ?></h5>

            <?php
            if($uid==@$_SESSION['userId']){
                echo '<button type="button" class="close" style="    float: right;    margin: 9;" onclick="deletaComentario('.$affcom['id'].');">&times;</button>';
            }
            ?>

            <span data-utime="1371248446" class="com-dt"><?php echo format_interval($date); ?></span>
            <br/>
            <p>
                <?php echo $comment; ?>
            </p>
        </div>
    </div><!-- end "cmt-cnt" -->
<?php } if(isset($_SESSION['islogged'])){?>
<div id="newc" class="new-com-bt">
    <span>Escreva aqui...</span>
</div>
<div class="new-com-cnt">
    <textarea class="the-new-com"></textarea>
    <div class="bt-add-com backc">Postar</div>
    <div class="bt-cancel-com backc">Cancelar</div>
</div>
<div class="clear"></div>
</div><!-- end of comments container "cmt-container" -->
<?php } ?>

<script type="text/javascript">
    $(function(){
        //alert(event.timeStamp);
        $('.new-com-bt').click(function(event){
            $(this).hide();
            $('.new-com-cnt').show();
            $('#name-com').focus();
        });

        /* when start writing the comment activate the "add" button */
        $('.the-new-com').bind('input propertychange', function() {
            $(".bt-add-com").css({opacity:0.6});
            var checklength = $(this).val().length;
            if(checklength){ $(".bt-add-com").css({opacity:1}); }
        });

        /* on clic  on the cancel button */
        $('.bt-cancel-com').click(function(){
            $('.the-new-com').val('');
            $('.new-com-cnt').fadeOut('fast', function(){
                $('.new-com-bt').fadeIn('fast');
            });
        });

        // on post comment click
        $('.bt-add-com').click(function(){
            var theCom = $('.the-new-com');

            if( !theCom.val()){
                alert('Você precisa escrever um comentário!');
            }else{
                $.ajax({
                    type: "POST",
                    url: "../ajax/add-comment.php",
                    data: 'act=add-com&id_post=<?php echo $id_post; ?>&uid=<?php echo $_SESSION['userId']?>&comment='+theCom.val(),
                    success: function(html){
                        theCom.val('');
                        $('.new-com-cnt').hide('fast', function(){
                            $('.new-com-bt').show('fast');
                            $('.new-com-bt').before(html);
                        })
                    }
                });
            }
        });

    });

    function deletaComentario(id){
        jQuery.ajax({url:"../ajax/add-comment.php", data:{"deleta": id}, dataType:"html", success: function(msg) {
            if(msg=="2"){
                $('#cm_'+id).fadeOut();
            }else{
            alert(msg);
            }
        }
        });
    }
</script>
