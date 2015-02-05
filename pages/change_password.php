<?php
/**
 * Created by PhpStorm.
 * User: HAL-9000
 * Date: 23/06/14
 * Time: 12:25
 */
if(!isLogged()){
    $warning->addError(10006, "Você precisa estar logado para entrar nesta página.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
}elseif(!$GLOBALS['acc']->isValidated()){//Checa se o usuario é validado
    $warning->addWarning(10007, "Você precisa validar sua conta antes de acessar essa página, <a href='".urlToPage('validate')."'>clique aqui</a> para validar sua conta.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
    }else{
if(isset($_POST['pass1'])){
    if(validatePassword($_POST['pass1'])){
        if($_POST['pass1']==$_POST['pass2']){
        if($GLOBALS['acc']->setPassword($_POST['pass1'])){
            if($GLOBALS['acc']->save()){
            $warning->addSuccess(null, 'Senha alterada com sucesso', 'Alteração de Senha');
            }else{
                $warning->addError(null, 'Ocorreu um erro ao salvar sua senha.', 'Alteração de Senha');
            }
        }else{
            $warning->addError(null, 'Ocorreu um erro alterar sua senha, tente mais tarde.', 'Alteração de Senha');
        }
        }else{
            $warning->addError(null, 'As senhas são diferentes', 'Alteração de Senha');
        }
    }else{
        $warning->addError(null, 'A senha deve ter no mínimo 4 caractéries e no máximo 16 caractéries', 'Alteração de senha');
    }
}
$main_content .='<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Trocar Senha</h3>
      </div>
      <div class="panel-body">
      <form class="form-horizontal" action="" method="post">
<fieldset>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pass1">Senha</label>
  <div class="col-md-4">
    <input id="pass1" name="pass1" type="password" placeholder="" class="form-control input-md" required="">

  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pass2">Confirmar Senha</label>
  <div class="col-md-4">
    <input id="pass2" name="pass2" type="password" placeholder="" class="form-control input-md" required="">

  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-success">Enviar</button>
  </div>
</div>

</fieldset>
</form>
        </div>
        </div>

';

}