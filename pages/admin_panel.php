<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 15/07/14
 * Time: 22:47
 */
if(!$GLOBALS['acc']->isAdmin()){    $warning->addCriticError('404', 'Cannot load page sadas, file ./pages/admin_panel.php doesn\'t exist');}else{

$title .= ' - Administração';
    if(isset($_GET['pedido'])){
        if(isset($_GET['confirm'])){
            $mysql->update('games', array('aprovado'=>1), 'id='.$_GET['confirm']);
            new Notification(null,$mysql->get('games', 'desenvolvedor', 'id='.$_GET['confirm']),'Seu pedido de para o Game '.$mysql->get('games', 'nome', 'id='.$_GET['confirm']).' foi aprovado',CURRENT_TIME,'custom');
            $developer=new Account($mysql->get('games', 'desenvolvedor', 'id='.$_GET['confirm']));
            if($developer->getGroup()<2){
                $developer->setGroup(2);
                $developer->save();
                unset($developer);
            }
        }elseif(isset($_GET['deny'])){
            $mysql->update('games', array('aprovado'=>0), 'id='.$_GET['deny']);
            new Notification(null,$mysql->get('games', 'desenvolvedor', 'id='.$_GET['deny']),'Seu pedido de para o Game '.$mysql->get('games', 'nome', 'id='.$_GET['deny']).' foi negado',CURRENT_TIME,'custom');
        }
    }
if(isset($_GET['saque'])){

    if(isset($_GET['rmconfirmrecv'])){//Remove
    $mysql->update('points_transfer', array('received'=>0), 'id ='.$_GET['rmconfirmrecv']);
    }elseif(isset($_GET['confirmrecv'])){//Confirma Saque
    $mysql->update('points_transfer', array('received'=>1), 'id ='.$_GET['confirmrecv']);
        new Notification(null, $mysql->get('points_transfer', 'accounts_id', 'id = '.$_GET['confirmrecv']), 'Você recebeu o saque na sua conta '.$mysql->get('points_transfer', 'payment_type', 'id = '.$_GET['confirmrecv']).'.', CURRENT_TIME,'custom');
    }elseif(isset($_GET['accept'])){//Aceita o Saque
    $mysql->update('points_transfer', array('aprovado'=>1), 'id ='.$_GET['accept']);
        new Notification(null, $mysql->get('points_transfer', 'accounts_id', 'id = '.$_GET['accept']), 'Seu saque foi aprovado por um administrador, você receberá seu pagamento em breve.', CURRENT_TIME,'custom');
    }elseif(isset($_GET['deny'])){//Nega o saque
    $mysql->update('points_transfer', array('aprovado'=>0), 'id ='.$_GET['deny']);
    $tmp_acc = new Account($mysql->get('points_transfer', 'accounts_id', 'id = '.$_GET['deny']));
     $tmp_acc->addPoints($mysql->get('points_transfer', 'points', 'id = '.$_GET['deny']));
        new Notification(null, $tmp_acc->getId(), 'Seu saque foi negado por um administrador.', CURRENT_TIME,'custom');
    }

}
 if (isset($_POST['featured'])){
        if($mysql->update('adm_options', array(
            'valor' =>$_POST['featured'],
        ), 'opcao="featured"')){
            $warning->addSuccess(null, 'O jogo promovido foi alterado!', 'Sucesso');
        }else{
            $warning->addError(null, 'Ocorreu um erro ao tentar alterar o jogo promovido','Erro');
        }
 }
if(isset($_GET['delete_user'])){
    if($mysql->delete('accounts', 'id ='.$_GET['delete_user']))
        $warning->addSuccess(null,'Usuário deletado com sucesso');
    else
        $warning->addError(null, 'Não foi possível deletar o usuário');
}elseif(isset($_GET['ban_user'])){
    $acc2 = new Account($_GET['ban_user']);
    if($acc2->banAccount())
        $warning->addSuccess(null, 'Usuário banido com sucesso');
    else
        $warning->addError(null, 'Ocorreu um erro ao banir o usuário');
}elseif(isset($_GET['unban_user'])){
$acc2 = new Account($_GET['unban_user']);
        if($acc2->unbanAccount())
            $warning->addSuccess(null, 'Usuário desbanido com sucesso');
        else
            $warning->addError(null, 'Ocorreu um erro ao desbanir o usuário');
}elseif((isset($_GET['addcopes']))&&(isset($_GET['acc']))){
    $acc2 = new Account($_GET['acc']);
    if($acc2->addPoints($_GET['addcopes'])){
        $warning->addSuccess(null, 'Foi adicionado '.$_GET['addcopes'].' Copes para a conta de ID: <b>'.$_GET['acc'].'</b>');
    }else{
        $warning->addError(null, 'Ocorreu um erro ao adicionar os Copes');
    }
}elseif((isset($_GET['validate']))&&(isset($_GET['acc']))){
    $acc2 = new Account($_GET['acc']);
    $code = $mysql->query('SELECT code FROM validations WHERE accounts_id = '.$acc2->getId())[0]['validations']['code'];
    if($acc2->validarConta($code)){
        $warning->addSuccess(null, 'Conta de ID: <b>'.$_GET['acc'].'</b> foi validado');
    }else{
        $warning->addError(null, 'Ocorreu um erro ao tentar validar');
    }
}

if(isset($_GET['edit_user'])){
$editing_acc = new Account($_GET['edit_user']);
if(@$_FILES['avatar']['name']!=""){
    $allowed =  array('png');
    if (in_array(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION), $allowed)){
        if (!is_uploaded_file($_FILES['avatar']['tmp_name'])||!move_uploaded_file($_FILES['avatar']['tmp_name'], PATH.'/media/avatar/'.$editing_acc->getId().'.png'))
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
            $editing_acc->setEmail($_POST['email']);
        }else{
            if($_POST['email']!=$editing_acc->getEmail())
                $warning->addError(null, 'O email '.$_POST['email'].' já está em uso', 'Erro');
        }
    }
    if((isset($_POST['name']))&&(validateName($_POST['name']))){
        $editing_acc->setName($_POST['name']);
    }
    if($editing_acc->save()){
        $warning->addSuccess(null, "Alterações salvas com sucesso");
    }else{
        $warning->addError(null,"Ocorreu um erro ao salvar as alterações");
    }


    if(isset($_POST['bio'])){
        $editing_acc->setOption('bio', strip_tags($_POST['bio']));
    }
    if(isset($_POST['facebook'])){
        $editing_acc->setOption('facebook', strip_tags($_POST['facebook']));
    }
    if(isset($_POST['twitter'])){
        $editing_acc->setOption('twitter', strip_tags(ltrim($_POST['twitter'],"@")));
    }
    if(isset($_POST['website'])){
        $editing_acc->setOption('website', strip_tags($_POST['website']));
    }
    if(isset($_POST['show_email'])){
        $editing_acc->setOption('show_email', 1);
    }else{
        $editing_acc->setOption('show_email', 0);
    }
    if(isset($_POST['show_friends'])){
        $editing_acc->setOption('show_friends', 1);
    }else{
        $editing_acc->setOption('show_friends', 0);
    }
    if(isset($_POST['show_games'])){
        $editing_acc->setOption('show_games', 1);
    }else{
        $editing_acc->setOption('show_games', 0);
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
  <label class="col-md-4 control-label" for="avatar"><img border="0" src="'.getAccountAvatarURL($editing_acc->getId()).'" width="72" height="72"></label>
  <div class="col-md-4">
    <input id="avatar" name="avatar" class="input-file" type="file" style="margin-top: 30px;">
  </div>
  </div>
  <div class="form-group">
  <label class="col-md-4 control-label" for="nick">Nick:</label>
  <div class="col-md-4">
  <input id="nick" name="nick" type="text" placeholder="Nickname" class="form-control input-md" value="'.$editing_acc->getNick().'" readonly>

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="name">Nome:</label>
  <div class="col-md-4">
  <input id="name" name="name" type="text" placeholder="Nome Completo" class="form-control input-md" value="'.$editing_acc->getName().'">

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Email:</label>
  <div class="col-md-4">
  <input id="email" name="email" type="text" placeholder="Email" class="form-control input-md" value="'.$editing_acc->getEmail().'">

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pass">Senha:</label>
  <div class="col-md-4">
  <h4 style="float: left;"><a href="'.urlToPage("admin_panel").'&change_password='.$editing_acc->getId().'"><span class="label label-success">Trocar Senha</span></a></h4>
  </div>
    <label class="col-md-4 control-label" for="button1id"></label>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="bio">Bio</label>
  <div class="col-md-4">
    <textarea class="form-control" id="bio" name="bio">'.$editing_acc->getOption('bio').'</textarea>
  </div>
</div>

  <button type="button" class="btn btn-warning" data-toggle="collapse" href="#opcoesavancadas">Opções Avançadas</button>

<div id="opcoesavancadas" class="panel-collapse collapse">
<!-- Prepended text-->
<p>
<div class="form-group">
  <label class="col-md-4 control-label" for="facebook">Facebook:</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">http://facebok.com/</span>
      <input id="facebook" name="facebook" class="form-control" placeholder="andre.luiz" type="text" value='.$editing_acc->getOption('facebook').'>
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
      <input id="twitter" name="twitter" class="form-control" placeholder="andreluiz" type="text"  value='.$editing_acc->getOption('twitter').'>
    </div>
    <p class="help-block">Seu perfil no Twitter</p>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="website">Website:</label>
  <div class="col-md-4">
  <input id="website" name="website" type="text" placeholder="http://meusite.com" class="form-control input-md"  value='.$editing_acc->getOption('website').'>
  <span class="help-block">URL do seu site ou Blog</span>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="checkboxes">Privacidade do Perfil:</label>
  <div class="col-md-4">
  <div class="checkbox">
    <label for="checkboxes-0" style=" float: left; ">
      <input type="checkbox" class="switch" name="show_email" value="1" '.$editing_acc->getOption('show_email').'>
      Mostrar Email
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-1" style=" float: left; ">
      <input type="checkbox" class="switch" name="show_games" value="2" '.$editing_acc->getOption('show_games').'>
      Mostrar Jogos
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-2" style=" float: left; ">
      <input type="checkbox" class="switch" name="show_friends" value="3" '.$editing_acc->getOption('show_friends').'>
      Mostrar Amigos
    </label>
	</div>
  </div>
</div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="button1id"></label>
  <div class="col-md-4">
  <p>
    <button id="button1id" name="button1id" type="submit" class="btn btn-success">Salvar</button>
    </form>
    <a href="'.urlToPage('admin_panel').'"><div id="btnRegisterCancel" class="btn btn-danger">Voltar</div></a>
  </div>
</div>

</fieldset>

 </div>
    </div>


';
}elseif(isset($_GET['change_password'])){
    $editing_acc = new Account($_GET['change_password']);
    if(isset($_POST['pass1'])){
        if(validatePassword($_POST['pass1'])){
            if($_POST['pass1']==$_POST['pass2']){
                if($editing_acc->setPassword($_POST['pass1'])){
                    if($editing_acc->save()){
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

}else{
$main_content .= '
<script type=\'text/javascript\'>$(document).ready(function () {if($_GET[\'saque\']==1){$(\'#tab3\').click();}if($_GET[\'pedido\']==1){$(\'#tab4\').click();}if($_GET[\'opa\']==1){$(\'#tab5\').click();}
$(\'#myTable\').pageMe({pagerSelector:\'#myPager\',showPrevNext:true,hidePageNumbers:false,perPage:4});
$(\'#myTable2\').pageMe({pagerSelector:\'#myPager2\',showPrevNext:true,hidePageNumbers:false,perPage:4});
$(\'#myTable3\').pageMe({pagerSelector:\'#myPager3\',showPrevNext:true,hidePageNumbers:false,perPage:4});
$(\'#myTable4\').pageMe({pagerSelector:\'#myPager4\',showPrevNext:true,hidePageNumbers:false,perPage:4});
});</script>
 <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Adminsitração</h3>
      </div>
      <div class="panel-body">
	<div class="row clearfix">
			<div class="tabbable" id="tabs-172718">
				<ul class="nav nav-tabs">
					<li class="active">
						<a id="tab1" href="#panel-1" data-toggle="tab">Usuários</a>
					</li>
					<li>
						<a id="tab2" href="#panel-2" data-toggle="tab">Games</a>
					</li>
					<li>
						<a id="tab3" href="#panel-3" data-toggle="tab">Saques</a>
					</li>
					<li>
						<a id="tab4" href="#panel-4" data-toggle="tab">Pedidos de Games</a>
					</li>
					<li>
						<a id="tab5" href="#panel-5" data-toggle="tab">Opções</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="panel-1">
					<div class="input-group"> <span class="input-group-addon">Filtro</span>

    <input id="filter" type="text" class="filter form-control" placeholder="Digite aqui...">
</div>

					<div class="table-responsive"><table class="table table-hover">
				<thead>
					<tr>
						<th>
							<center>ID</center>
						</th>
						<th>
							<center>Nick</center>
						</th>
						<th>
							<center>Email</center>
						</th>
						<th>
							<center>Grupo</center>
						</th>
						<th>
							<center>Copes</center>
						</th>
						<th>
							<center>Status</center>
						</th>
						<th>
							<center>Ação</center>
						</th>
					</tr>
				</thead>
				<tbody id="myTable" class="searchable">';

                foreach($mysql->select(array('table'=>'accounts')) as $account){
                    $main_content.= '<tr class="'.($account['accounts']['banned']==1?'danger':'').'">
						<td>
							<a href="'.urlToPage('profile').'&value1='.$account['accounts']['id'].'">'.$account['accounts']['id'].'</a>
						</td>
						<td>
							'.$account['accounts']['nick'].'
						</td>
						<td>
							'.$account['accounts']['email'].'
						</td>
						<td>
							'.getGroupName($account['accounts']['group']).'
						</td>
						<td>
							<span class="glyphicon glyphicon-euro"></span>'.$account['accounts']['points'].'<a href="#" onclick="var copes = prompt(\'Quantos Copes deseja dar para esse usuário?\', \'\'); if(copes!=null){document.location.href=\''.urlToPage('admin_panel').'&acc='.$account['accounts']['id'].'&addcopes=\'+copes+\'\'}"><span class="glyphicon glyphicon-plus-sign"></span></a>
						</td>
						<td>
							'.($account['accounts']['banned']==1?'Banido<p>':'').($account['accounts']['validado']==1?'Validado':'Não Validado <a href="'.urlToPage('admin_panel').'&validate&acc='.$account['accounts']['id'].'"><span class="glyphicon glyphicon-ok-circle"></span></a>').'
						</td>
						<td>
						<a href="'.urlToPage('admin_panel').'&edit_user='.$account['accounts']['id'].'"><span class="glyphicon glyphicon-edit"></span></a>
						'.($account['accounts']['banned']==0?'<a href="'.urlToPage('admin_panel').'&ban_user='.$account['accounts']['id'].'" onclick="return confirm(\'Tem certeza que deseja banir essa conta?\');"><span class="glyphicon glyphicon-ban-circle"></span></a>
						':'<a href="'.urlToPage('admin_panel').'&unban_user='.$account['accounts']['id'].'" onclick="return confirm(\'Tem certeza que deseja desbanir essa conta?\');"><span class="glyphicon glyphicon-ok-circle"></span></a>
						').'
						<a href="'.urlToPage('admin_panel').'&delete_user='.$account['accounts']['id'].'" onclick="return confirm(\'Tem certeza que deseja deletar essa conta?\');"><span class="glyphicon glyphicon-trash"></span></a>
						</td>
					</tr>';

                }

				$main_content.='

				</tbody>
			</table></div>
		
			 <div class="col-md-12 text-center">
      <ul class="pagination pagination-lg pager" id="myPager"></ul>
      </div>
					</div>
					<div class="tab-pane" id="panel-2">
						<div class="table-responsive">
						<table class="table table-hover">
  <thead>
  <tr>
  <th>ID</th>
  <th>Nome</th>
  <th>Valor</th>
  <th>Descrição</th>
  <th>Data</th>
  <th>Aprovado?</th>
  <th>Publicado?</th>
  </tr>
  </thead>
  <tbody id="myTable3" class="searchable">
  ';
    foreach (getDeveloperGameList() as $row) {
        $main_content .= '<tr class="' . ($row['games']['aprovado'] == -1 ? '' : ($row['games']['aprovado'] == 0 ? 'danger' : 'success')) . '">
  <td>' . $row['games']['id'] . '</td>
  <td>' . $row['games']['nome'] . '</td>
  <td>RS$' . $row['games']['value'] . ',00</td>
  <td>' . $row['games']['desc'] . '</td>
  <td>' . $row['games']['data'] . '</td>
  <td>' . ($row['games']['aprovado'] == -1 ? 'Em Espera' : ($row['games']['aprovado'] == 0 ? 'Negado' : 'Aprovado')) . '<p>
  <a href="'.urlToPage('admin_panel').'&pedido=1&confirm='.$row['games']['id'].'"><span class="glyphicon glyphicon-ok"></span></a>
  <a href="'.urlToPage('admin_panel').'&pedido=1&deny='.$row['games']['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>
    <td>' . ($row['games']['publicado'] == 0 ? 'Não' : 'Sim') . '</td><p>
  </tr>';
    }
    $main_content .= ' </tbody>
  </table></div>
    <div class="col-md-12 text-center">
      <ul class="pagination pagination-lg pager" id="myPager3"></ul>
      </div>
					</div>
					<div class="tab-pane" id="panel-3">

 <div class="input-group"> <span class="input-group-addon">Filtro</span>

    <input id="filter" type="text" class="filter form-control" placeholder="Digite aqui...">
</div>
					<div class="table-responsive"><table class="table table-hover">
  <thead>
  <tr>
  <th>ID</th>
  <th>Desenvolvedor</th>
  <th>Quantidade (Copes)</th>
  <th>Valor a receber</th>
  <th>Método de Pagamento</th>
  <th>Email de Recebimento</th>
  <th>Data</th>
  <th>Recebido?</th>
  <th>Aprovado?</th>
  </tr>
  </thead>
  <tbody id="myTable2" class="searchable">
  ';
    foreach($mysql->query('SELECT * FROM points_transfer ORDER BY data DESC') as $row){
        $main_content .='<tr class="'.($row['points_transfer']['aprovado']==-1?'':($row['points_transfer']['aprovado']==0?'danger':'success')).'">
  <td>'.$row['points_transfer']['id'].'</td>
  <td><a target="_blank" href="'.urlToPage('profile').'&value1='.$row['points_transfer']['accounts_id'].'">'.getNick($row['points_transfer']['accounts_id']).'</a></td>
  <td>'.$row['points_transfer']['points'].'</td>
  <td>R$'.$row['points_transfer']['value'].',00</td>
  <td>'.$row['points_transfer']['payment_type'].'</td>
  <td>'.$row['points_transfer']['email'].'</td>
  <td>'.$row['points_transfer']['data'].'</td>
  <td>'.($row['points_transfer']['received']==1?'Recebido':'Não recebido').'<p><a href="'.urlToPage('admin_panel').'&saque=1&confirmrecv='.$row['points_transfer']['id'].'"><span class="glyphicon glyphicon-ok"></span></a><a href="'.urlToPage('admin_panel').'&saque=1&rmconfirmrecv='.$row['points_transfer']['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>
  <td>'.($row['points_transfer']['aprovado']==-1?'Em Espera':($row['points_transfer']['aprovado']==0?'Negado':'Aprovado')).'<p><a href="'.urlToPage('admin_panel').'&saque=1&accept='.$row['points_transfer']['id'].'"><span class="glyphicon glyphicon-ok"></span></a><a href="'.urlToPage('admin_panel').'&saque=1&deny='.$row['points_transfer']['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>

  </tr>';
    }
    $main_content.=' </tbody>
  </table></div>
   <div class="col-md-12 text-center">
      <ul class="pagination pagination-lg pager" id="myPager2"></ul>
      </div>
      </div>
      <div class="tab-pane" id="panel-4">
<div class="table-responsive"><table class="table table-hover">
  <thead>
  <tr>
  <th>ID</th>
  <th>Nome</th>
  <th>Descrição</th>
  <th>Valor</th>
  <th>Data</th>
  <th>Aprovado?</th>
  <th>Demonstração</th>
  </tr>
  </thead>
  <tbody id="myTable4" class="searchable">
  ';
    foreach ($mysql->query('SELECT * FROM games WHERE aprovado=-1 OR aprovado=0') as $row) {
        $main_content .= '<tr class="' . ($row['games']['aprovado'] == -1 ? '' : ($row['games']['aprovado'] == 0 ? 'danger' : 'success')) . '">
  <td>' . $row['games']['id'] . '</td>
  <td>' . $row['games']['nome'] . '</td>
  <td>RS$' . $row['games']['value'] . ',00</td>
  <td>' . $row['games']['desc'] . '</td>
  <td>' . $row['games']['data'] . '</td>
  <td>' . ($row['games']['aprovado'] == -1 ? 'Em Espera' : ($row['games']['aprovado'] == 0 ? 'Negado' : 'Aprovado')) . '<p>
  <a href="'.urlToPage('admin_panel').'&pedido=1&confirm='.$row['games']['id'].'"><span class="glyphicon glyphicon-ok"></span></a>
  <a href="'.urlToPage('admin_panel').'&pedido=1&deny='.$row['games']['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>
  <td><a href="'.URL.'/media/games/'.$row['games']['id'].'/'.@end(explode('/',shell_exec("find ".PATH."/media/games/".$row['games']['id']."/ -name game* &2>1"))).'"><span class="glyphicon glyphicon-cloud-download"></span></a></td>

  </tr>';
    }
    $main_content .= ' </tbody>
  </table></div>
    <div class="col-md-12 text-center">
      <ul class="pagination pagination-lg pager" id="myPager4"></ul>
      </div>

					</div>
					      <div class="tab-pane" id="panel-5">
<form class="form-horizontal" method="post" action="'.urlToPage('admin_panel').'&opa=1">
<fieldset>

<!-- Form Name -->
<legend>Opções Administrativas</legend>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="featured">Jogo Promovido</label>
  <div class="col-md-4">
    <select id="featured" name="featured" class="form-control">
    ';
    foreach(getDeveloperGameList() as $games){
        $tmp_game = new Game($games['games']['id']);
        $main_content .= ' <option value="'.$tmp_game->getId().'">'.$tmp_game->getNome().'</option>';
    }
    $main_content .='
    </select>
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
	</div>
	</div>
</div>
';
}
    //LIMPA OS CAMPOS DO GET PARA TER MAIS SEGURANÇA
    if(isset($_GET['saque'])&&(isset($_GET['confirmrecv'])||isset($_GET['rmconfirmrecv'])||isset($_GET['confirm'])||isset($_GET['deny']))){
    header('Location:'.urlToPage('admin_panel').'&saque=1');
        }elseif(isset($_GET['pedido'])&&(isset($_GET['confirm'])||isset($_GET['deny']))){
        header('Location:'.urlToPage('admin_panel').'&pedido=1');
    }elseif(isset($_GET['opa'])&&isset($_POST['featured'])){
        header('Location:'.urlToPage('admin_panel').'&opa=1');
    }
}