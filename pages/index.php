<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 5/7/14
 * Time: 9:38 AM
 */
$title .= ' - Página Inicial';

if(isLogged()){
    $main_content .= '<div class="panel panel-success">
      <div class="panel-heading">

      </div>
      <div class="panel-body">
      <div class="row clearfix">
		<div class="col-md-12 column">
			<div class="row clearfix text-left">
				<div class="col-md-9 column">
					<h3>
						Notícias
					</h3>';
    $data = null;

    foreach($GLOBALS['acc']->getGames() as $game){
        $jogo = new Game($game);
        if(gameExists($jogo->getId(), true)) {
            foreach ($jogo->getNews(2) as $news) {
                $new_array[] = new news($news['news']['id']);
            }
        }
    }
    if(isset($new_array)&&is_array($new_array)){
    @usort($new_array, function($a, $b) {//Ordena noticias por data
        $ad = new DateTime($a->getDate());
        $bd = new DateTime($a->getDate());

        if ($ad == $bd) {
            return 0;
        }

        return $ad < $bd ? 1 : -1;
    });

    foreach($new_array as $new){
        $jogo = new Game($new->getGameid());
        $data .= '<div class="well" style="overflow: hidden">
					<h2>' . $new->getTitle() . ' <a href="' . urlToPage('game') . '&id=' . $jogo->getId() . '"> <img src="' . URL . '/media/games/' . $jogo->getId() . '/icon" width="32" height="32"/> ' . $jogo->getNome() . '</a></h2>
					<small style="opacity: 0.5">' . format_interval($new->getDate()) . ' - <a href="' . urlToPage('profile') . '&value1=' . $new->getAuthor() . '">' . getNick($new->getAuthor()) . '</a></small>
				<div class="noticias">' . maxDotDotDot($new->getData(), 500) . '</div>
					<p><a href="'.getNewsUrl($new->getId()).'" class="btn btn-primary btn-large">Leia mais <span class="glyphicon glyphicon-chevron-right"></span></a></p>

					</div>';
    }
}
    if($data!=null){
        $main_content .= $data;
    }else{
        $main_content .= '<div class="well"><center>Não há notícias :(</center></div>';
    }
    $main_content .='


				</div>
				<div class="col-md-3 column">
					<h3>
						Meus Games
					</h3>
					<ul class="nav nav-pills nav-stacked">
						';
            foreach($GLOBALS['acc']->getGames() as $game){
                $jogo = new Game($game);
                if(gameExists($jogo->getId(), true))
                    $main_content .= '<li><a href="' . urlToPage('game') . '&id=' . $game . '"><img src="' . URL . '/media/games/' . $jogo->getId() . '/icon" width="32" height="32"/>' . $jogo->getNome() . '</a></li>';
                }
    $main_content .='
					</ul>
				</div>
			</div>

		</div>
	</div>
      </div>

    </div>
    <div class="jumbotron text-left">
				<h1>
					Venda seu Game!
				</h1>
				<p>
					Torne-se um desenvolvedor e distribua seu Game gratuitamente no nosso site!
				</p>
				<p>
					<a class="btn btn-primary btn-large" href="'.urlToPage('new_game_guide').'">Ler Mais</a>
				</p>
			</div>';
}else{
    $main_content .= '<div class="jumbotron">

     <h1><center><img class="img-responsive" src="'.URL.'/images/logo.png"/></center></h1>
  <p>Neste site você pode se divertir, comprar jogos, fazer amigos, e se você for um desenvolvedor poderá trabalhar conosco.</p>
  <p><a href="'.URL.'/?page=login" class="btn btn-primary btn-info" role="button">Login</a>&nbsp;<a href="'.URL.'/?page=register" class="btn btn-primary btn-success" role="button">Registre-se</a></p>
  </div>
</div>
  ';
    foreach($mysql->query('SELECT game_id FROM games_stats ORDER BY downloads DESC LIMIT 3') as $row){
        $game = new Game($row['games_stats']['game_id']);
   $main_content .= '<div class="row">
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="'.URL.'/media/games/'.$game->getId().'/logo" class="img-box" alt="'.$game->getNome().'">
      <div class="caption">
        <h3>'.$game->getNome().'</h3>
        <p><a href="'.urlToPage('game').'&id='.$game->getId().'" class="btn btn-primary" role="button">Ver mais...</a></p>
      </div>
    </div>
  </div>';
    }
}


?>