<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 21/07/14
 * Time: 00:19
 */

if (!isLogged()) {
    $warning->addWarning(10007, "Você precisa estar logado para acessar esta página, <a href='" . urlToPage('login') . "'>clique aqui</a> para fazer login.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
} else {
    if (!$GLOBALS['acc']->isValidated()) { //Checa se o usuario é validado
        $warning->addWarning(10007, "Você precisa validar sua conta antes de acessar essa página, <a href='" . urlToPage('validate') . "'>clique aqui</a> para validar sua conta.");
        $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
    } else {
        $title .= '- Enviar Game';
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['nome'])) {
            if (is_numeric($_POST['value'])) {
                if (isset($_POST['desc'])) {
                    $allowed_logo = array('png', 'jpg', 'gif');
                    if (in_array(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION), $allowed_logo)&&$_FILES['logo']['size']<=5242880) {
                        $allowed_icon = array('png', 'jpg', 'gif', 'ico');
                        if (in_array(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION), $allowed_icon)&&$_FILES['icon']['size']<=5242880) {
                            if (is_array($_FILES['game'])&&$_FILES['game']['size']<=1073741824) {
                                if ($id = $mysql->insert('games', array('nome' => strip_tags($_POST['nome']), 'desenvolvedor' => $_SESSION['userId'], 'desc' => strip_tags($_POST['desc']), 'value' => $_POST['value']))) {
                                    if (mkdir(PATH . '/media/games/' . $id)&&mkdir(PATH . '/media/games/' . $id.'/images/')&&mkdir(PATH . '/media/games/' . $id . '/releases/')) {
                                        if (is_uploaded_file($_FILES['logo']['tmp_name']) && move_uploaded_file($_FILES['logo']['tmp_name'], PATH . '/media/games/' . $id . '/logo')) {
                                            if (is_uploaded_file($_FILES['icon']['tmp_name']) && move_uploaded_file($_FILES['icon']['tmp_name'], PATH . '/media/games/' . $id . '/icon')) {
                                                if (is_uploaded_file($_FILES['game']['tmp_name']) && move_uploaded_file($_FILES['game']['tmp_name'], PATH . '/media/games/' . $id . '/game.' . pathinfo($_FILES['game']['name'], PATHINFO_EXTENSION))) {
                                                    $warning->addSuccess(null, 'Pedido Enviado com sucesso, aguarde um administrador verificar');
                                                } else {
                                                    //print_r($_FILES);
                                                    $warning->addError(null, 'Ocorreu um erro ao enviar o Game', 'Erro no Upload');
                                                }
                                            } else {
                                                $warning->addError(null, 'Ocorreu um erro ao enviar o ícone', 'Erro no Upload');
                                            }
                                        } else {
                                            $warning->addError(null, 'Ocorreu um erro ao enviar o Logo', 'Erro no Upload');
                                        }
                                    } else {
                                        $warning->addError(null, 'Erro ao processar pedido');
                                    }
                                } else {
                                    $warning->addError(null, 'Erro ao enviar os dados, tente mais tarde');
                                }
                            } else {
                                $warning->addError(null, 'Por favor envie uma demonstração do jogo', 'Erro ao enviar Pedido');
                            }
                        } else {
                            $warning->addError(null, 'Extensão inválida para o ícone (Permitidas ' . implode(',', $allowed_icon) . ')', 'Erro ao enviar Pedido');
                        }
                    } else {
                        $warning->addError(null, 'Extensão inválida para o logo (Permitidas ' . implode(',', $allowed_logo) . ')', 'Erro ao enviar Pedido');
                    }
                } else {
                    $warning->addError(null, 'Por favor forneça uma descrição do seu jogo', 'Erro ao enviar Pedido');
                }
            } else {
                $warning->addError(null, 'Digite um número válido para o valor', 'Erro ao enviar Pedido');
            }
        } else {
            $warning->addError(null, 'Digite um nome para seu jogo', 'Erro ao enviar Pedido');
        }
            if($warning->getErrorsCount(Warnings::TYPE_ERROR)>0){
			if(is_integer($id)){
                if($mysql->delete('games', 'id='.$id)){
                @rrmdir(PATH . '/media/games/' . $id);
				}
				}
            }
        }
        $main_content .= '<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Novo Jogo</h3>
      </div>
      <div class="panel-body">
      <div class="row clearfix">
		<div class="col-md-12 column">
			<div class="row clearfix">
				<div class="col-md-12 column">
				<form class="form-horizontal" method="post" enctype="multipart/form-data">
<fieldset>

<!-- Form Name -->
<legend class="text-left">Enviar Pedido</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="nome">Nome do Jogo</label>
  <div class="col-md-4">
  <input id="nome" name="nome" type="text" placeholder="Digite aqui..." class="form-control input-md" required="">
  <span class="help-block">O nome público do seu Jogo</span>
  </div>
</div>

<!-- Prepended text-->
<div class="form-group">
  <label class="col-md-4 control-label" for="value">Valor</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">R$</span>
      <input id="value" name="value" class="form-control" placeholder="Digite aqui..." type="text" required="">
    </div>
    <p class="help-block">O Valor em Reais (BRL) que o jogo vai custar</p>
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="desc">Descrição</label>
  <div class="col-md-4">
    <textarea class="form-control" id="desc" name="desc">Uma descrição detalhada do Jogo</textarea>
  </div>
</div>
<!-- FILE UPLOAD-->
<div class="form-group">
  <label class="col-md-4 control-label" for="logo">Logo do Jogo</label>
  <div class="col-md-4">
  <input id="logo" name="logo" type="file" class="form-control input-md" required="">
  <span class="help-block">Logo do seu Jogo (Max 5mb)</span>
  </div>
</div>
<!-- FILE UPLOAD-->
<div class="form-group">
  <label class="col-md-4 control-label" for="icon">Ícone do Jogo</label>
  <div class="col-md-4">
  <input id="icon" name="icon" type="file" class="form-control input-md" required="">
  <span class="help-block">Ícone do jogo (Max 5mb)</span>
  </div>
</div>
<!-- FILE UPLOAD-->
<div class="form-group">
  <label class="col-md-4 control-label" for="game">Demonstração do Jogo</label>
  <div class="col-md-4">
  <input id="game" name="game" type="file" class="form-control input-md" required="">
  <span class="help-block">Uma demonstração do jogo para análise (Max 1gb)</span>
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
			<hr>
			<div class="row clearfix">
				<div class="col-md-12 column">
					<legend class="text-left">Histórico de Pedidos</legend>
					<table class="table table-hover">
  <thead>
  <tr>
  <th>ID</th>
  <th>Nome</th>
  <th>Descrição</th>
  <th>Valor</th>
  <th>Data</th>
  <th>Aprovado?</th>
  </tr>
  </thead>
  <tbody id="myTable" class="searchable">
  ';
        foreach ($mysql->query('SELECT * FROM games WHERE desenvolvedor=' . $GLOBALS['acc']->getId() . ' ORDER BY data DESC') as $row) {
            $main_content .= '<tr class="' . ($row['games']['aprovado'] == -1 ? '' : ($row['games']['aprovado'] == 0 ? 'danger' : 'success')) . '">
  <td>' . $row['games']['id'] . '</td>
  <td>' . $row['games']['nome'] . '</td>
  <td>RS$' . $row['games']['value'] . ',00</td>
  <td>' . $row['games']['desc'] . '</td>
  <td>' . $row['games']['data'] . '</td>
  <td>' . ($row['games']['aprovado'] == -1 ? 'Em Espera' : ($row['games']['aprovado'] == 0 ? 'Negado' : 'Aprovado')) . '</td>

  </tr>';
        }
        $main_content .= ' </tbody>
  </table>
				</div>
			</div>
		</div>
	</div>
      </div>
      </div>
      ';
    }
}