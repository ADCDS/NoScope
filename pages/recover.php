<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 21/10/14
 * Time: 12:01
 */

$title .= '- Recuperação de Senha';

if(!isLogged()){
    if(isset($_GET['c'])&&($code=$mysql->query('SELECT * FROM recover WHERE code ="'.$_GET['c'].'"'))){
        $a = strtotime($code[0]['recover']['data']);
        $b = strtotime(CURRENT_TIME);
        $c= ($b-$a)/(60 * 60 * 24);
        if($c<=7){
       $acc = new Account($code['0']['recover']['uid']);
        if(isset($_POST['ps'])&&isset($_POST['nps'])){
            if($_POST['ps']==$_POST['nps']){
                if(validatePassword($_POST['nps'])){
            $acc->setPassword($_POST['nps']);
            if($acc->save()){
                $warning->addSuccess(null, 'Senhas alteradas com sucesso', 'Sucesso');
                if(!$acc->isBanned()){
                    $_SESSION['islogged'] = true;
                    $_SESSION['userId'] = $acc->getId();
                    $acc->setLastLogin();
                    setcookie('uid', $acc->getId(), null, "/");
                    setcookie('group', $acc->getGroup(), null, "/");
                    setcookie('copes', $acc->getPoints(), null, "/");
                    setcookie('validated', ($acc->isValidated())?'1':'0', null, "/");
                    $year = time() + 31536000;
                }
                $layout_bottom .= jsRedirect('index.php');
                $mysql->delete('recover','id='.$code['0']['recover']['id']);
            }
                }else{
                    $warning->addError(null, 'Essa não é uma senha válida', 'Erro');
                }
            }else{
                $warning->addError(null, 'As senhas não são iguais', 'Erro');
            }
        }



            $main_content .= '<div class="panel panel-warning"><div class="panel-heading">
        <h3 class="panel-title">Recupere sua Senha</h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal" method="post" action="">
<fieldset>

<!-- Form Name -->
<legend>Alterando a senha para o Usuário '.$acc->getNick().'</legend>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="ps">Nova Senha</label>
  <div class="col-md-4">
    <input id="ps" name="ps" type="password" placeholder="" class="form-control input-md" required="">

  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="nps">Confirma Nova Senha</label>
  <div class="col-md-4">
    <input id="nps" name="nps" type="password" placeholder="" class="form-control input-md" required="">
    <span class="help-block">Repita aqui a senha do campo acima</span>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-success">Alterar</button>
  </div>
</div>

</fieldset>
</form>

      </div></div>';




        }else{
            $main_content .= '<div class="panel panel-danger"><div class="panel-heading">
        <h3 class="panel-title">Erro</h3>
      </div>
      <div class="panel-body">
        <center>Esse código expirou!</center>
      </div></div>';
            $mysql->delete('recover','id='.$code['0']['recover']['id']);
        }
    }else{
        $main_content .= '<div class="panel panel-danger"><div class="panel-heading">
        <h3 class="panel-title">Erro</h3>
      </div>
      <div class="panel-body">
        <center>Código inválido!</center>
      </div></div>';
    }

}else{

    //Caso o Usuário esteja logado bloqueia a página
        $warning->addError(10005, "Esta página não pode ser acessada por você.");
        $layout_bottom .= jsRedirect($GLOBALS['lastpage']);

}