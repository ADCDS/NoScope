<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 5/9/14
 * Time: 11:08 PM
 */
if(!isLogged()){
    $warning->addWarning(10007, "Você precisa estar logado para acessar esta página, <a href='".urlToPage('login')."'>clique aqui</a> para fazer login.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
}else{
if(!$GLOBALS['acc']->isValidated()){//Checa se o usuario é validado
$warning->addWarning(10007, "Você precisa validar sua conta antes de acessar essa página, <a href='".urlToPage('validate')."'>clique aqui</a> para validar sua conta.");
$layout_bottom .= jsRedirect($GLOBALS['lastpage']);
}else{
    $title .= ' - Editar Perfil';
if(@$_FILES['avatar']['name']!=""){
    $allowed =  array('png');
    if (in_array(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION), $allowed)){
        if (!is_uploaded_file($_FILES['avatar']['tmp_name'])||!move_uploaded_file($_FILES['avatar']['tmp_name'], PATH.'/media/avatar/'.$_SESSION['userId'].'.png'))
        {
          $warning->addError(null, 'Ocorreu um erro ao salvar sua imagem', 'Erro no Upload');
        }
    }else{
        $warning->addError(null, 'Extensão inválida, ela precisa ser <b>png</b>', 'Erro no Upload');
    }
}
if(isset($_POST['nick'])){
if((isset($_POST['email']))&&(validateEmail($_POST['email']))){
    if(!accExists($_POST['email'],null,'email')){//Checa se ja não existe uma acc com esse email
    $GLOBALS['acc']->setEmail($_POST['email']);
    }else{
        if($_POST['email']!=$GLOBALS['acc']->getEmail())
        $warning->addError(null, 'O email '.$_POST['email'].' já está em uso', 'Erro');
    }
}
if((isset($_POST['name']))&&(validateName($_POST['name']))){
$GLOBALS['acc']->setName($_POST['name']);
}
    if($GLOBALS['acc']->save()){
        $warning->addSuccess(null, "Alterações salvas com sucesso");
    }else{
        $warning->addError(null,"Ocorreu um erro ao salvar as alterações");
    }


    if(isset($_POST['bio'])){
        $GLOBALS['acc']->setOption('bio', strip_tags($_POST['bio']));
    }
    if(isset($_POST['facebook'])){
        $GLOBALS['acc']->setOption('facebook', strip_tags($_POST['facebook']));
    }
    if(isset($_POST['twitter'])){
        $GLOBALS['acc']->setOption('twitter', strip_tags(ltrim($_POST['twitter'],"@")));
    }
    if(isset($_POST['website'])){
        $GLOBALS['acc']->setOption('website', strip_tags($_POST['website']));
    }
    if(isset($_POST['show_email'])){
        $GLOBALS['acc']->setOption('show_email', 1);
    }else{
        $GLOBALS['acc']->setOption('show_email', 0);
    }
    if(isset($_POST['show_friends'])){
        $GLOBALS['acc']->setOption('show_friends', 1);
    }else{
        $GLOBALS['acc']->setOption('show_friends', 0);
    }
    if(isset($_POST['show_games'])){
        $GLOBALS['acc']->setOption('show_games', 1);
    }else{
        $GLOBALS['acc']->setOption('show_games', 0);
    }
}

$main_content .= '<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Edite o seu Perfil</h3>
      </div>
      <div class="panel-body">
        <form id="update" name="update" action="" method="POST" enctype="multipart/form-data" class="form-horizontal">
<fieldset>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="avatar"><img border="0" src="'.getAccountAvatarURL($_SESSION['userId']).'" width="72" height="72"></label>
  <div class="col-md-4">
    <input id="avatar" name="avatar" class="input-file" type="file" style="margin-top: 30px;">
  </div>
  </div>
  <div class="form-group">
  <label class="col-md-4 control-label" for="nick">Nick:</label>
  <div class="col-md-4">
  <input id="nick" name="nick" type="text" placeholder="Nickname" class="form-control input-md" value="'.$GLOBALS['acc']->getNick().'" readonly>

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="name">Nome:</label>
  <div class="col-md-4">
  <input id="name" name="name" type="text" placeholder="Nome Completo" class="form-control input-md" value="'.$GLOBALS['acc']->getName().'">

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Email:</label>
  <div class="col-md-4">
  <input id="email" name="email" type="text" placeholder="Email" class="form-control input-md" value="'.$GLOBALS['acc']->getEmail().'">

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pass">Senha:</label>
  <div class="col-md-4">
  <h4 style="float: left;"><a href="'.urlToPage("change_password").'"><span class="label label-success">Trocar Senha</span></a></h4>
  </div>
    <label class="col-md-4 control-label" for="button1id"></label>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="bio">Bio</label>
  <div class="col-md-4">
    <textarea class="form-control" id="bio" name="bio">'.$GLOBALS['acc']->getOption('bio').'</textarea>
  </div>
</div>

  <button type="button" class="btn btn-warning" data-toggle="collapse" href="#opcoesavancadas">Opções Avançadas</button>

<div id="opcoesavancadas" class="panel-collapse collapse">
<!-- Prepended text-->
<br/>
<div class="form-group">
  <label class="col-md-4 control-label" for="facebook">Facebook:</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">http://facebok.com/</span>
      <input id="facebook" name="facebook" class="form-control" placeholder="andre.luiz" type="text" value='.$GLOBALS['acc']->getOption('facebook').'>
    </div>
    <p class="help-block">Link do seu Perfil no Facebook</p>
  </div>
</div>

<!-- Prepended text-->
<div class="form-group">
  <label class="col-md-4 control-label" for="twitter">Twitter:</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">@</span>
      <input id="twitter" name="twitter" class="form-control" placeholder="andreluiz" type="text"  value='.$GLOBALS['acc']->getOption('twitter').'>
    </div>
    <p class="help-block">Seu perfil no Twitter</p>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="website">Website:</label>
  <div class="col-md-4">
  <input id="website" name="website" type="text" placeholder="http://meusite.com" class="form-control input-md"  value='.$GLOBALS['acc']->getOption('website').'>
  <span class="help-block">URL do seu site ou Blog</span>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="checkboxes">Privacidade do Perfil:</label>
  <div class="col-md-4">
  <div class="checkbox">
    <label for="checkboxes-0" style=" float: left; ">
      <input type="checkbox" class="switch" name="show_email" value="1" '.$GLOBALS['acc']->getOption('show_email').'>
      Mostrar Email
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-1" style=" float: left; ">
      <input type="checkbox" class="switch" name="show_games" value="2" '.$GLOBALS['acc']->getOption('show_games').'>
      Mostrar Jogos
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-2" style=" float: left; ">
      <input type="checkbox" class="switch" name="show_friends" value="3" '.$GLOBALS['acc']->getOption('show_friends').'>
      Mostrar Amigos
    </label>
	</div>
  </div>
</div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="button1id"></label>
  <div class="col-md-4">
  <br/>
    <button id="button1id" name="button1id" type="submit" class="btn btn-success">Salvar</button>
    </form>
    <a href="'.urlToPage('profile').'&value1='.$GLOBALS['acc']->getId().'"><div id="btnRegisterCancel" class="btn btn-danger">Voltar</div></a>
  </div>
</div>

</fieldset>

 </div>
    </div>


';
}
}
?>
