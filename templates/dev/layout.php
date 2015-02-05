<?php
echo '
<html>

<head>
  '.$layout_header.'
<title>'.$title.'</title>

</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginheight="0" marginwidth="0" bgcolor="#FFFFFF">


<table border="0" width="100%" cellspacing="0" cellpadding="0" background="'.LAYOUT_URL.'img/topbkg.gif">
  <tr>
    <td width="50%"><img border="0" src="'.LAYOUT_URL.'img/toplogo.gif" width="142" height="66"></td>
    <td width="50%">
      <p align="right"><img border="0" src="'.LAYOUT_URL.'img/topright.gif" width="327" height="66"></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0" background="'.LAYOUT_URL.'img/blackline.gif">
  <tr>
    <td width="100%"><font color="#B8C0F0" face="Arial" size="2"><b>&nbsp;&nbsp;
      your link&nbsp;&nbsp; |&nbsp;&nbsp; your link&nbsp;&nbsp; |&nbsp;&nbsp;
      your link&nbsp;&nbsp; |&nbsp;&nbsp; your link&nbsp;&nbsp; |&nbsp;&nbsp;
      your link&nbsp;&nb

                sp; |&nbsp;&nbsp; your link&nbsp;&nbsp; |&nbsp;&nbsp;
      your link</b></font></td>
  </tr>
</table>
<!-- ÁREA DE NOTIFICAÇÕES -->
<p style="margin-left: 20"><font face="Arial" size="2" color="#000000">&nbsp;</font>
'.printErrors($warning).'
</p>
<!-- ÁREA DE NOTIFICAÇÕES -->
'.$main_content.'
<p style="margin-left: 20"><font face="Arial" size="2" color="#000000">&nbsp;</font></p>

<p style="margin-left: 20" align="center"><font face="Arial" color="#000000" size="1">�
COPYRIGHT 2001 ALL RIGHTS RESERVED YOURDOMAIN.COM</font></p>
<table border="0" width="100%" cellspacing="0" cellpadding="0" background="'.LAYOUT_URL.'img/botline.gif">
  <tr>
    <td width="100%"><img border="0" src="'.LAYOUT_URL.'img/botline.gif" width="41" height="12"></td>
  </tr>
</table>

</body>

</html>';
?>