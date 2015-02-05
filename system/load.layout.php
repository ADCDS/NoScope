<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 4/30/14
 * Time: 8:27 AM
 */

if(!defined('INITIALIZED'))
    exit;
$layout_bottom = '<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/notifications.js"></script>
<script type="text/javascript" src="js/friends.js"></script>
<script type="text/javascript" src="js/pages.js"></script>
<script>
// Bind normal buttons
Ladda.bind( \'.ladda-button\', { timeout: 1000 } );
        </script>
        '. $layout_bottom;
$layout_header = '

<!-- JQUERY -->
<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
<script type=\'text/javascript\'>
var MAINURL = "'.URL.'";
var CURRENTURL = document.URL;
var DOMAIN = "'.DOMAIN.'";
</script>

<!-- BOOTSTRAP CORE -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<script type="text/javascript"  src="js/bootstrap.min.js"></script>

<link href="css/main.css" rel="stylesheet" type="text/css" />

<!-- SOCIAL BUTTONS -->
<link href="css/bootstrap-social.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome.css" rel="stylesheet" type="text/css" />

<!-- LADDA PLUGIN -->
<script type="text/javascript" src="js/ladda.js"></script>
<link href="css/ladda.css" rel="stylesheet" type="text/css" />
<!-- CKEDITOR -->
<script src="//cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
<!-- GROWL -->
<script src="js/jquery.growl.js" type="text/javascript"></script>

<!-- COOKIE -->
<script src="js/jquery.cookie.js" type="text/javascript"></script>

<!-- QTIP -->
<link href="css/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css" />
<script src="js/qtip/jquery.qtip.min.js" type="text/javascript"></script>
<script src="js/qtip/imagesloaded.pkg.min.js" type="text/javascript"></script>

<!-- BOOTSTRAP SWITCH -->
<link href="css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
<script src="js/bootstrap-switch.min.js" type="text/javascript"></script>
'.$layout_header;

if(!isLogged()){
    $layout_header .= '
     <link href="css/login.css" rel="stylesheet" type="text/css" />';
    $layout_bottom .= '
<script type="text/javascript" src="js/login_compact.js"></script>
        ';
}else{
    $layout_header .= '<script type="text/javascript">var UID = "'.$GLOBALS['acc']->getId().'";</script>';
    $layout_bottom .= '<script type="text/javascript">loggedIn=true;setInterval(function(){checkConection();}, 3000);</script>';
}
include(LAYOUT_PATH."layout.php");
?>
