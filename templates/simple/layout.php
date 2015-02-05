<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 5/12/14
 * Time: 7:36 PM
 */

echo '<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
       '.$layout_header.'
        <title>'.$title.'</title>
		<link rel="shortcut icon" href="'.URL.'/images/scope.png" />
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->










        <!-- CSS code from Bootply.com editor -->

        <style type="text/css">
    body {
    padding-top: 50px;
}

        </style>
    </head>

    <!-- HTML code from Bootply.com editor -->

    <body  style="background-image:url(\'./images/background.jpg\');background-color:#C0C0C0;background-size:   cover;background-repeat: no-repeat; ">

    <div class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="'.URL.'"><img  style="max-width: 30px;margin-top: -5px;" src="'.URL.'/images/scope.png"/></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        '.NavigationBar::getLeftMenus().'
      </ul>

      <ul class="nav navbar-nav navbar-right">
       <form class="navbar-form navbar-left" method="post" action="'.urlToPage('search').'"role="search">
        <div class="form-group">
          <input type="text" name="search" id="search" class="form-control" placeholder="Procurar jogos ou usuários" size="32">
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
      </form>

          '.NavigationBar::getRightMenus().'

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</div>

<div class="container">

  <div class="text-center">
    <!--<h1>Bootstrap starter template</h1>-->
  <br/>
<div id="main" class="container">
<div class="row clearfix">
		<div class="col-md-12 column">
		'.$main_content.'
		</div>
		</div>
</div> <!-- /container -->
  </div>
</div><!-- /.container -->


        <!-- JavaScript jQuery code from Bootply.com editor -->

        <script type=\'text/javascript\'>

    $(document).ready(function() {

    '.printErrors($warning).'

    });

        </script>
        '.$layout_bottom.'
    </body>
</html>';
?>