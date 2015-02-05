<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 30/06/14
 * Time: 21:25
 */
$readonly = '';
if(isset($_POST['code'])){
    if($GLOBALS['acc']->validarConta($_POST['code'])){
        $warning->addSuccess(null, 'Sua conta foi validada com sucesso', 'Validação da Conta');
    }else{
        $warning->addError(null, 'Código de validação incorreto', 'Validação da Conta');
    }
}
if ($GLOBALS['acc']->isValidated()){
    $readonly = 'readonly';
    //$warning->addError('','Sua conta ja está validada', 'Validação da Conta');
}
$main_content .= '<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Valide a sua Conta</h3>
      </div>
      <div class="panel-body">
     <form id="validation" action="" method="POST" class="form-horizontal">
<fieldset>

<!-- Form Name -->

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="code">Código de Validação</label>
  <div class="col-md-6">
  <input id="code" name="code" type="text" placeholder="EX: C56EG" class="form-control input-md" required="" '.$readonly.'>
  <span class="help-block">O código de validação foi enviado para o seu email: <b>'.$GLOBALS['acc']->getEmail().'</b></span>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for=""></label>
  <div class="col-md-4">
    <button id="" name="" type="submit" class="btn btn-success">Enviar</button>
  </div>
</div>

</fieldset>
</form>
      </div>
    </div>
';
?>