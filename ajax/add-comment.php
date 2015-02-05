<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 10/10/14
 * Time: 18:49
 *
 *
 BASEADO EM Comments System Using Php, jQuery & Ajax
http://www.webcodo.net/comments-system-using-php-ajax/
 */
extract($_POST);

include '../libs/ajax.include.php';
if(isset($_GET['deleta'])){
    if(is_numeric($_GET['deleta'])){
$mysql->delete('comments', 'id='.$_GET['deleta'].' AND accounts_id='.$_SESSION['userId']);
       die('2');
    }
}elseif($_POST['act'] == 'add-com'){
    $acc = new Account($_SESSION['userId']);
    $uid = $_SESSION['userId'];

    $comment = htmlentities($comment);
    // Get gravatar Image
    // https://fr.gravatar.com/site/implement/images/php/
    $default = "mm";
    $size = 35;
    $grav_url = getAccountAvatarURL($uid);

    $name = $acc->getName()!=''?$acc->getName():$acc->getNick();
    //insert the comment in the database
    mysql_query("INSERT INTO comments (comment, news_id, accounts_id)VALUES('$comment', '$id_post', '$uid')");

    if(!mysql_errno()){
        ?>

        <div id="cm_<?php echo mysql_insert_id();?>" class="cmt-cnt">
            <img src="<?php echo $grav_url; ?>" alt="" />
            <div class="thecom">
                <h5><?php echo $name; ?></h5>
                <?php
                if($uid==@$_SESSION['userId']){
                echo '<button type="button" class="close" style="    float: right;    margin: 9;" onclick="deletaComentario('.mysql_insert_id().');">&times;</button>';
                }
                ?>

                <span  class="com-dt"><?php echo date('d-m-Y H:i'); ?></span>
                <br/>
                <p><?php echo $comment; ?></p>
            </div>
        </div><!-- end "cmt-cnt" -->

    <?php }} ?>

