<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 25/07/14
 * Time: 13:20
 */
if(!isLogged()){
    $warning->addWarning(10007, "Você precisa estar logado para acessar esta página, <a href='".urlToPage('login')."'>clique aqui</a> para fazer login.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
}else{
    if($GLOBALS['acc']->getGroup()!=2&&!$GLOBALS['acc']->isAdmin()){//Checa se é desenvolvedor
        $warning->addWarning(10008, "Você não é um desenvolvedor");
        $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
    }else{
    if(isset($_GET['id'])){
    if(gameExists($_GET['id'])){
        $game = new Game($_GET['id']);
        if(($game->getDesenvolvedor()==$GLOBALS['acc']->getId()&&$game->isAprovado())||$GLOBALS['acc']->isAdmin()) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (@$_POST['action'] == 1) {
                    if ($game->getPublicado() == 0) {
                        if (isset($_FILES['imgs'])) {
                            foreach ($_FILES['imgs']['name'] as $key => $name) {
                                $allowed = array('png', 'jpg', 'gif');
                                if (in_array(pathinfo($_FILES['imgs']['name'][$key], PATHINFO_EXTENSION), $allowed) && $_FILES['imgs']['size'][$key] <= 5242880) {
                                    if (!move_uploaded_file($_FILES["imgs"]["tmp_name"][$key], PATH .'/media/games/' . $game->getId() . '/images/' . $key)) {
                                        $warning->addError(null, 'Ocorreu um erro a imagem ' . $key);
                                    }
                                }
                            }
                        }
                        if (isset($_FILES['game'])) {
                            if (!move_uploaded_file($_FILES["game"]["tmp_name"], PATH .'/media/games/' . $game->getId() . '/releases/1.' . pathinfo($_FILES['game']['name'], PATHINFO_EXTENSION))) {
                                $warning->addError(null, 'Ocorreu um erro ao enviar o Jogo');
                            } else {
                                $game->setExtension(pathinfo($_FILES['game']['name'], PATHINFO_EXTENSION));
                            }
                        }

                        if ($warning->getErrorsCount(Warnings::TYPE_ERROR) == 0) {
                            if ($mysql->insert('games_stats', array('game_id' => $game->getId()))) {
                                if ($game->Publicar(true)) {
                                    $warning->addSuccess(null, 'Jogo publicado com sucesso');
                                } else {
                                    $warning->addError(null, 'Não foi possível completar a publicação tente mais tarde.');
                                }
                            }
                        }
                    }
                }
            }


            $main_content .= '<div class="panel panel-success"><div class="panel-heading">
        <h3 class="panel-title">Painel do Game</h3>
      </div>
      <div class="panel-body">
       <div class="row clearfix">
		<div class="col-md-2 column pull-left">
			<ul class="nav nav-pills nav-stacked">
			    <li>
					<a href="' . (urlToPage('panel_game')) . '&id=' . $_GET['id'] . '">Início</a>
				</li>
				<li>
					<a href="' . (urlToPage('panel_game')) . '&id=' . $_GET['id'] . '&news=1">Notícias</a>
				</li>
				<li>
					<a href="' . (urlToPage('panel_game')) . '&id=' . $_GET['id'] . '&updates=1">Updates</a>
				</li>
				<li>
					<a href="' . (urlToPage('panel_game')) . '&id=' . $_GET['id'] . '&history=1">Histórico de Compras</a>
				</li>
				<li>
					<a href="' . (urlToPage('panel_game')) . '&id=' . $_GET['id'] . '&opt=1">Opções</a>
				</li>
			</ul>
		</div>
		<div class="col-md-10 column">';
            if ($game->getPublicado() == 0) {
                $main_content .= '<p>
				O Game ' . $game->getNome() . ' ainda <b>não está publicado</b>, para que ele seja publicado, complete esse último passo.
				</p>
				<form class="form-horizontal" method="post" enctype="multipart/form-data">
<fieldset>
';
                $x = 0;
                $dir = new DirectoryIterator(PATH .'/media/games/' . $game->getId() . '/images/');
                foreach ($dir as $file) {
                    $x++;
                }
                if ($x < 5) {
                    $main_content .= '
<div class="form-group">
  <label class="col-md-4 control-label" for="imgs">Imagens</label>
  <div class="col-md-4">
  <input id="imgs" name="imgs[]" type="file" multiple="multiple" accept="image/*" placeholder="" class="form-control input-md" required="">
  <span class="help-block">Ao menos 3 imagens com alta definição, serão usadas em todo site</span>
  </div>
</div>';
                }
                $x = 0;
                $dir = new DirectoryIterator(PATH .'/media/games/' . $game->getId() . '/releases/');
                foreach ($dir as $file) {
                    $x++;
                }
                if ($x == 2) {
                    $main_content .= '
<div class="form-group">
  <label class="col-md-4 control-label" for="game">Game</label>
  <div class="col-md-4">
  <input id="game" name="game" type="file" placeholder="" class="form-control input-md" required="">
  <span class="help-block">Primeira versão do game para ser publicada</span>
  </div>
</div>';
                }

                $main_content .= '<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-success">Enviar</button>
  </div>
</div>
</fieldset>
<input type="hidden" name="action" id="action" value="1">
</form>

				';
            } elseif (isset($_GET['news'])) {
                if (isset($_GET['new'])) {
                    $main_content .= '<form class="form-horizontal" method="post" action="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&news=1">
<fieldset>

<!-- Form Name -->
<legend>Nova Notícia</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="title">Título</label>
  <div class="col-md-4">
  <input id="title" name="title" type="text" placeholder="Digite aqui..." class="form-control input-md" required="">

  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="category">Categoria</label>
  <div class="col-md-4">
    <select id="category" name="category" class="form-control">
     ';
                    foreach (getCategories() as $category) {
                        $main_content .= '<option value="' . $category['category']['id'] . '">' . $category['category']['name'] . '</option>';
                    }
                    $main_content .= '
    </select>
  </div>
</div>
 <!-- Multiple Checkboxes (inline) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="checkboxes">Publicado?</label>
  <div class="col-md-4">
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="publicado" class="switch" id="checkboxes-0" value="1">
    </label>
  </div>
</div>
<!-- Textarea -->
<div class="form-group">
  <label class="col-md-2 control-label" for="data">Texto</label>
  <div class="col-md-10">
    <textarea class="form-control" id="data" name="data"></textarea>
  </div>



<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
  <br>
    <button id="submit" name="submit" class="btn btn-success">Enviar</button>
  </div>
</div>

</fieldset>
</form>
';
                    $layout_bottom .= " <script>
                CKEDITOR.replace( 'data' );
                                      </script>";
                }elseif(isset($_GET['edit_new'])&&newsExists($_GET['edit_new'])){
                    $edit_news = new news($_GET['edit_new']);
                    $main_content .= '<form class="form-horizontal" method="post" action="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&news=1">
<fieldset>

<!-- Form Name -->
<legend>Editando '.maxDotDotDot($edit_news->getTitle(), 25).'</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="title">Título</label>
  <div class="col-md-4">
  <input id="title" name="title" type="text" placeholder="Digite aqui..." class="form-control input-md" required="" value="'.$edit_news->getTitle().'">

  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="category">Categoria</label>
  <div class="col-md-4">
    <select id="category" name="category" class="form-control">
     ';
                    foreach (getCategories() as $category) {
                        $main_content .= '<option value="' . $category['category']['id'] . '">' . $category['category']['name'] . '</option>';
                    }
                    $main_content .= '
    </select>
  </div>
</div>
 <!-- Multiple Checkboxes (inline) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="checkboxes">Publicado?</label>
  <div class="col-md-4">
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="publicado" class="switch" id="checkboxes-0" value="1">
    </label>
  </div>
</div>
<!-- Textarea -->
<div class="form-group">
  <label class="col-md-2 control-label" for="data">Texto</label>
  <div class="col-md-10">
    <textarea class="form-control" id="data" name="data">'.$edit_news->getData().'</textarea>
  </div>

<input id="eid" name="eid" type="hidden" value="'.$edit_news->getId().'">

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
  <br>
    <button id="submit" name="submit" class="btn btn-success">Enviar</button>
  </div>
</div>

</fieldset>
</form>
';
                    $layout_bottom .= " <script>
                CKEDITOR.replace( 'data' );
                                      </script>";
                } else {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					$autor=$_SESSION['userId'];
                        if (isset($_POST['title'])) {
                            if (isset($_POST['category'])) {
                                if (isset($_POST['data'])) {
                                    if(isset($_POST['eid'])){//TA EDITANDO A NOTICIA
                                        $news = new news($_POST['eid']);
                                        if($news->getAuthor()!=$_SESSION['userId']&&!$GLOBALS['acc']->isAdmin()){
                                            unset($news);
                                        }else{
										$autor = $news->getAuthor();
										}
                                    }else{
                                        $news = new news();
                                    }
                                    if(@$news!=null)
                                    {$news->setCategory($_POST['category']);
                                    $news->setData($_POST['data']);
                                    $news->setGameid($game->getId());
                                    $news->setAuthor($autor);
                                    $news->setAuthorip($_SERVER['REMOTE_ADDR']);
                                    $news->setTitle($_POST['title']);
                                    $news->setSeoname(format_uri($_POST['title']));
                                    }								
                                    if ($_POST['publicado']==1&&@$news->Publicar()) {
                                        $warning->addSuccess(null, 'Notícia adicionada com sucesso');
                                        unset($news);
                                    } elseif($_POST['publicado']!=1){
									$news->Despublicar();
									}else{
                                        $warning->addError(null, 'Erro ao adicionar notícia');
                                        unset($news);
                                    }
                                }
                            }
                        }

                    }
                    if (isset($_GET['publish'])) {
                        $id = $mysql->get('news', 'id', 'id=' . $_GET['publish'] . ' AND game_id=' . $game->getId());
                        $news = new news($id);
                        if ($news->Publicar()) {
                            $warning->addSuccess(null, 'Notícia publicada com sucesso');
                        } else {
                            $warning->addError(null, 'Erro ao publicar notícia');
                        }
                    } elseif (isset($_GET['unpublish'])) {
                        $id = $mysql->get('news', 'id', 'id=' . $_GET['unpublish'] . ' AND game_id=' . $game->getId());
                        $news = new news($id);
                        if ($news->Despublicar()) {
                            $warning->addSuccess(null, 'Notícia despublicada com sucesso');
                        } else {
                            $warning->addError(null, 'Erro ao despublicar notícia');
                        }
                    } elseif (isset($_GET['delete'])) {
                        $id = $mysql->get('news', 'id', 'id=' . $_GET['delete'] . ' AND game_id=' . $game->getId());
                        if ($mysql->delete('news', 'id=' . $id)) {
                            $warning->addSuccess(null, 'Notícia deletada com sucesso');
                        } else {
                            $warning->addError(null, 'Erro ao deletar notícia');
                        }
                    }

                    $main_content .= '
<script type=\'text/javascript\'>$(document).ready(function () {
$(\'#myTable3\').pageMe({pagerSelector:\'#myPager3\',showPrevNext:true,hidePageNumbers:false,perPage:4});
});</script>
<legend>Notícias</legend>
<a href="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&news=1&new=1" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Notícia</a>
<table class="table table-hover">
  <thead>
  <tr>
  <th>Título</th>
  <th>Data</th>
  <th>Autor</th>
  <th>Views</th>
  <th>Comandos</th>
  </tr>
  </thead>
  <tbody id="myTable3" class="searchable">
  ';
                    foreach ($game->getNews(null, false) as $row) {
                        $news = new news($row['news']['id']);
                        $main_content .= '<tr class="' . ($news->getPublicado() == 0 ? 'danger' : 'success') . '">
  <td>' . $news->getTitle() . '</td>
  <td>' . $news->getDate() . '</td>
  <td>' . getNick($news->getAuthor()) . '</td>
  <td>' . $news->getViews() . '</td>
  <td>
   <a href="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&news=1&edit_new=' . $row['news']['id'] . '"><span class="glyphicon glyphicon-edit"></span></a>
 &nbsp;&nbsp;&nbsp;&nbsp; ' . ($news->getPublicado() == 0 ? '<a id="publish" href="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&news=1&publish=' . $row['news']['id'] . '"><span class="glyphicon glyphicon-ok"></span></a>' : ' <a id="unpublish" href="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&news=1&unpublish=' . $row['news']['id'] . '"><span class="glyphicon glyphicon-remove"></span></a>') . '
  &nbsp;&nbsp;&nbsp;&nbsp;<a href="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&news=1&delete=' . $row['news']['id'] . '"><span class="glyphicon glyphicon-trash"></span></a>
 </td>
  </tr>';
                    }
                    $main_content .= ' </tbody>
  </table>
    <div class="col-md-12 text-center">
      <ul class="pagination pagination-lg pager" id="myPager3"></ul>
      </div>';
                }
            } elseif (isset($_GET['updates'])) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['versao'])) {
                        if (isset($_POST['com'])) {
                            if (isset($_FILES['game']['name'])) {
                                $dir_id = $mysql->get('versions', 'COUNT(*)', 'game_id=' . $game->getId());
                                $dir_id++;
                                if (move_uploaded_file($_FILES["game"]["tmp_name"], PATH .'/media/games/' . $game->getId() . '/releases/' . $dir_id . '.' . pathinfo($_FILES['game']['name'], PATHINFO_EXTENSION))) {
                                    if ($ultima_versao = $mysql->insert('versions', array(
                                        'versao' => $_POST['versao'],
                                        'dir_id' => $dir_id,
                                        'date' => CURRENT_TIME,
                                        'comentario' => $_POST['com'],
                                        'ext' => pathinfo($_FILES['game']['name'], PATHINFO_EXTENSION),
                                        'game_id' => $game->getId()
                                    ))
                                    ) {
                                        $game->setUltimaVersao($ultima_versao);
                                        if ($game->save()) {
                                            $warning->addSuccess(null, 'Uma nova versão do seu game foi lançada!');
                                        } else {
                                            @unlink(PATH .'/media/games/' . $game->getId() . '/releases/' . $dir_id . '.' . pathinfo($_FILES['game']['name'], PATHINFO_EXTENSION));
                                            $mysql->delete('versions', 'id=' . $ultima_versao);
                                            $warning->addError(null, 'Erro ao salvar as atualizações');
                                        }
                                    }
                                } else {
                                    $warning->addError(null, 'Não foi possível enviar o Update');
                                }
                            }
                        }
                    }
                }
                if (isset($_GET['update'])) {
                    $main_content .= '<form class="form-horizontal" enctype="multipart/form-data" method="post" action="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&updates=1">
<fieldset>

<!-- Form Name -->
<legend>Novo Update</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="versao">Versao</label>
  <div class="col-md-4">
  <input id="versao" name="versao" type="text" placeholder="Digite aqui" class="form-control input-md" required="">

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="game">Game</label>
  <div class="col-md-4">
  <input id="game" name="game" type="file" placeholder="" class="form-control input-md" required="">
  <span class="help-block">Envie aqui o jogo que ficará disponível para download</span>
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="com">Comentários</label>
  <div class="col-md-8">
    <textarea class="form-control" id="com" name="com"></textarea>
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
';
                    $layout_bottom .= " <script>
                CKEDITOR.replace( 'com' );
                                      </script>";
                } else {
                    $main_content .= '
<script type=\'text/javascript\'>$(document).ready(function () {
$(\'#myTable3\').pageMe({pagerSelector:\'#myPager3\',showPrevNext:true,hidePageNumbers:false,perPage:4});
});</script>
<legend>Updates</legend>
<a href="' . urlToPage('panel_game') . '&id=' . $game->getId() . '&updates=1&update=1" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Update</a>
<table class="table table-hover">
  <thead>
  <tr>
  <th>#ID</th>
  <th>Versão</th>
  <th>Data</th>
  <th>Extensão</th>
  <th>Comentário</th>
  <th>Download</th>
  </tr>
  </thead>
  <tbody id="myTable3" class="searchable">
  ';
                    foreach ($game->getVersoes() as $row) {
                        $main_content .= '<tr">
  <td>' . $row['versions']['id'] . '</td>
  <td>' . $row['versions']['versao'] . '</td>
  <td>' . $row['versions']['date'] . '</td>
    <td>' . $row['versions']['ext'] . '</td>
  <td>' . $row['versions']['comentario'] . '</td>
  <td><a href="' . $game->getDirectDownloadLink($row['versions']['id']) . '"><span class="glyphicon glyphicon-cloud-download"></span></a></td>
   </tr>';
                    }
                    $main_content .= ' </tbody>
  </table>
    <div class="col-md-12 text-center">
      <ul class="pagination pagination-lg pager" id="myPager3"></ul>
      </div>';
                }
            } elseif (isset($_GET['opt'])) {

                if (isset($_POST['desc'])) {
                    $game->setDesc(strip_tags($_POST['desc']));
                    if ($game->save())
                        $warning->addSuccess(null, 'Descrição alterada com sucesso');
                    else
                        $warning->addError(null, 'Ocorreu um erro ao alterar a descrição do jogo, tente mais tarde');
                }elseif(isset($_FILES['icone'])){
                    if(file_exists(PATH.'/media/games/'.$game->getId().'/icon')){
                         unlink(PATH.'/media/games/'.$game->getId().'/icon');
                    }
                    if (move_uploaded_file($_FILES["icone"]["tmp_name"], PATH.'/media/games/'.$game->getId().'/icon')) {
                        $warning->addSuccess(null, "Ícone alterado com sucesso!", "Sucesso");
                    } else {
                        $warning->addError(null, "Ocorreu um erro ao alterar o ícone!", "Erro");
                    }

                }elseif(isset($_FILES['logo'])){
                    if(file_exists(PATH.'/media/games/'.$game->getId().'/logo')){
                        unlink(PATH.'/media/games/'.$game->getId().'/logo');
                    }
                    if (move_uploaded_file($_FILES["logo"]["tmp_name"], PATH.'/media/games/'.$game->getId().'/logo')) {
                        $warning->addSuccess(null, "Logo alterado com sucesso!", "Sucesso");
                    } else {
                        $warning->addError(null, "Ocorreu um erro ao alterar o logo!", "Erro");
                    }
                }
                $main_content .= '<form class="form-horizontal" method="post" action="">
<fieldset>

<!-- Form Name -->
<legend>Opções do game ' . $game->getNome() . '</legend>

<a href="#configeral" data-toggle="collapse" class="btn btn-block btn-default"><span class="glyphicon glyphicon-wrench"></span> Configurações Gerais</a>
<div id="configeral" class="panel-collapse collapse">
<!-- Textarea -->
<div class="form-group">
  <label class="col-md-2 control-label" for="desc">Descrição</label>
  <div class="col-md-10">
    <textarea class="form-control" id="desc" name="desc">' . $game->getDesc() . '</textarea>
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

<a href="#img" data-toggle="collapse" class="btn btn-block btn-default"><span class="glyphicon glyphicon-camera"></span> Configurações das Imagens</a>
<div id="img" class="panel-collapse collapse">
<iframe id="gal" frameBorder="0" class="well" style="width: 830px;" name="gal" src="'.URL.'/external/img_editor.php?gid='.$game->getId().'" onload="calcHeight(\'gal\',42);"></iframe>
<hr>
<div class="well">
<form enctype="multipart/form-data" class="form-horizontal" method="post" action="">
<fieldset>

<!-- Form Name -->
<legend>Alterar Logo</legend>

<!-- File Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="logo">Logo</label>
  <div class="col-md-4">
    <input id="logo" name="logo" class="input-file" type="file">
  </div>
  <div class="col-md-12">
   <img id="lpre" name="lpre" class="img-responsive" src="'.URL.'/media/games/'.$game->getId().'/logo" />
   </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="subbmit"></label>
  <div class="col-md-4">
    <button id="subbmit" name="subbmit" class="btn btn-success">Enviar</button>
  </div>
</div>

</fieldset>
</form>

</div>
<hr>
<div class="well">
<form enctype="multipart/form-data" class="form-horizontal" method="post" action="">
<fieldset>

<!-- Form Name -->
<legend>Alterar Ícone</legend>

<!-- File Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="logo">Ícone</label>
  <div class="col-md-4">
    <input id="icone" name="icone" class="input-file" type="file">
  </div>
    <div class="col-md-12">
   <img id="ipre" name="ipre" heigth=32px width=32px src="'.URL.'/media/games/'.$game->getId().'/icon"/>
   </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="subbmit"></label>
  <div class="col-md-4">
    <button id="subbmit" name="subbmit" class="btn btn-success">Enviar</button>
  </div>
</div>

</fieldset>
</form>

</div>
</div>
';
                $layout_bottom .= " <script>
                CKEDITOR.replace( 'desc' );
                                      </script>";
            } elseif (isset($_GET['history'])){
                $main_content .= '
<legend>Histórico de Compras</legend>
<div class="input-group"> <span class="input-group-addon">Filtro</span>

    <input id="filter" type="text" class="filter form-control" placeholder="Digite aqui...">
</div>
<table class="table table-hover">
  <thead>
  <tr>
  <th>ID</th>
  <th>Usuario (id)</th>
  <th>Data</th>
  <th>Qtd. Copes</th>
  </tr><tbody id="myTable2" class="searchable">';
                foreach($mysql->query('SELECT * FROM buy_history WHERE games_id='.$game->getId()) as $row){
                    $row=$row['buy_history'];
                    $main_content .=
                        '<tr>
                        <td>'.$row['id'].'</td>
                        <td>'.getNick($row['accounts_id']).' ('.$row['accounts_id'].')</td>
                        <td>'.$row['date'].'</td>
                         <td>'.$row['copes'].'</td>
                        </tr>';
                }
  $main_content.='
  </thead>

  </tbody>
  </table>
     <div class="col-md-12 text-center">
      <ul class="pagination pagination-lg pager" id="myPager2"></ul>
      </div>
      ';
                $layout_bottom .= "<script type=text/javascript>$('#myTable2').pageMe({pagerSelector:'#myPager2',showPrevNext:true,hidePageNumbers:false,perPage:4});</script>";


            }else{
                $main_content .= '
                <legend>Estatísticas</legend>
                <div class="row clearfix">
		<div class="col-md-4 column"><h2><span class="glyphicon glyphicon-eye-open"></span><br/>Views<p>'.$game->getStats(0).'</p></h2>
		</div>
		<div class="col-md-4 column"><h2><span class="glyphicon glyphicon-euro"></span><br/>Copes<p>'.$game->getStats(1).'</p></h2>
		</div>
		<div class="col-md-4 column"><h2><span class="glyphicon glyphicon-cloud-download"></span><br/>Downloads<p>'.$game->getStats(2).'</p></h2>
		</div>
	</div>';
            }
            $main_content .='</div>
	</div>
      </div></div>';
        }else{
            $warning->addWarning(10011, "Você não é o desenvolvedor desse game");
            $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
        }
    }else{
        $warning->addWarning(10010, "Esse game não existe");
        $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
    }
    }else{
        $warning->addWarning(10009, "Página inválida");
        $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
    }
    }
}
?>