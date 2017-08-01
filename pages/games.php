<?php
$title .= ' - Loja';
//PEGANDO JOGO MAIS BEM AVALIADO
$mysql_result = $mysql->select(array (
        'table' => 'ratings',
        'fields' => 'gid',
        'condition' => '1'
    ));
$bagid = array();
foreach($mysql_result as $row){
$bagid[] = $row["ratings"]["gid"];
}
$result = array();
foreach($bagid as $game){
   $id=str_replace('/game/', '', $game);
    $result=mysql_query("select sum(rating) as ratings from ratings where gid='$game'");
    $row=mysql_fetch_array($result);

    $rating=$row['ratings'];

    $quer = mysql_query("select rating from ratings where gid='$game'");
    $all_result = mysql_fetch_assoc($quer);
    $rows_num = mysql_num_rows($quer);

    if($rows_num > 0){
        $result_d[$id] = $rating;
    }
}

$jogomaisbemavaliado = new Game(doublemax($result_d)['i']);


//PEGANDO JOGO MAIS FAMOSO
$mysql_result = $mysql->query("SELECT game_id as views FROM games_stats WHERE views = (SELECT MAX(views) FROM games_stats);")[0];
$jogomaisfamoso = new Game($mysql_result['games_stats']['game_id']);
$jogomaisfamosoviews = $mysql_result['0']['views'];


//PEGANDO JOGO PROMOVIDO
$mysql_result = $mysql->query("SELECT * FROM adm_options WHERE opcao='featured'")[0]["adm_options"]["valor"];
$jogopromovido = new Game($mysql_result);
$main_content .= '
<div class="panel panel-success"><div class="panel-heading">Loja de Games</div>
      <div class="panel-body">
<div class="row clearfix">
		<div class="col-md-12 column">

				<div class="panel panel-warning"><div class="panel-heading">
        <h3 class="panel-title">Jogo em destaque</h3>
      </div>
      <div class="row clearfix">
      <div class="col-md-12 column">
      <hr>
		</div>
		<div class="col-md-2 column">
		<h3>
		 <img class="img-responsive" src="' . URL . '/media/games/' . $jogopromovido->getId() . '/logo" />
					'.$jogopromovido->getNome().'
				</h3>
		</div>
		<div class="col-md-7 column well">
		<p><div id="myCarousel" class="carousel slide" data-interval="3000" data-ride="carousel">
    	<!-- Carousel indicators -->
        <ol class="carousel-indicators">';
$dir = new DirectoryIterator('./media/games/'.$jogopromovido->getId().'/images/');
$x=-2;
foreach($dir as $file ){
    switch($x){
        case 0:
            $main_content.='<li data-target="#myCarousel" data-slide-to="'.$x.'" class="active">';
            break;
        case -1:
            break;
        case -2:
            break;
        default:
            $main_content.='<li data-target="#myCarousel" data-slide-to="'.$x.'"></li>';
            break;
    }
    if($x>=0){
        $main_content .='
                </li>
';
    }
    $x++;
}
$x=0;$dir=null;

$main_content .='</ol>
       <!-- Carousel items -->
        <div class="carousel-inner">';
$dir = new DirectoryIterator('./media/games/'.$jogopromovido->getId().'/images/');
$x=-2;
foreach($dir as $file ){
    switch($x){
        case 0:
            $main_content.='<div class="active item">';
            break;
        case -1:
            break;
        case -2:
            break;
        default:
            $main_content.='<div class="item">';
            break;
    }
    if($x>=0){
        $main_content .='
<img class="img-responsive" src="'.URL.'/media/games/'.$jogopromovido->getId().'/images/'.$x.'">
                <div class="carousel-caption">
                  <h3>'.$jogopromovido->getNome().'</h3>
                  <p>Imagem '.($x+1).'</p>

                </div>
</div>


';
    }
    $x++;
}


$main_content .='
        </div>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>
					'.$jogopromovido->getDesc().'</p>
		</div>

		<br>
		<br>
			<div class="col-md-3 column">
				<span class="label label-default">Compre o Jogo</span>
					<div class="well"><h3>';

				if($jogopromovido->getValue()==0){
				$main_content .= 'Gratuito';
				}elseif($jogopromovido->getValue()==1){
				$main_content .= '1 Cope';
				}else{
				$main_content .= $jogopromovido->getValue().' Copes';
				}
				$main_content.='	</h3>
		';

	if(isLogged()){
            if($jogopromovido->getDesenvolvedor()!=$_SESSION['userId']){
        if(!$GLOBALS['acc']->temGame($jogopromovido->getId())){
            $main_content .='<button id="buygame" data-g="'.$jogopromovido->getId().'" type="button" class="btn btn-default btn-block">Comprar</button>';
        }else{
            $main_content .='<button id="downloadgame" data-g="'.$jogopromovido->getId().'" type="button" class="btn btn-success btn-block">Download</button>';
        }
            }else{
                $main_content .='<button type="button" class="btn btn-danger btn-block">Você é o dono desse jogo</button>';
            }
        }
        $main_content .='

					</div>
					</div>
					<div class="col-md-3 column">
					<span class="label label-default">Avaliações</span>
					<iframe class="well well-small" style="width: 100%;" height="140px" src="./external/rating.php?gid=/game/'.$jogopromovido->getId().'"></iframe>
                    </div>

		</div>
	</div>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-6 column">
		<div class="panel panel-danger"><div class="panel-heading">
        <h3 class="panel-title">Jogo mais bem avaliado</h3>
      </div>
      <div class="panel-body">
       <img class="img-responsive" src="' . URL . '/media/games/' . $jogomaisbemavaliado->getId() . '/logo" />
       <h3>'.$jogomaisbemavaliado->getNome().'</h3>
       <p><a href="'.urlToPage('game').'&id='.$jogomaisbemavaliado->getId().'" class="btn btn-primary btn-large">Veja <span class="glyphicon glyphicon-chevron-right"></span></a></p>

      </div></div>
		</div>
		<div class="col-md-6 column">
			<div class="panel panel-info"><div class="panel-heading">
			<h3 class="panel-title">Jogo mais popular</h3>
      </div>
      <div class="panel-body">
        <img class="img-responsive" src="' . URL . '/media/games/' . $jogomaisfamoso->getId() . '/logo" />
       <h3>'.$jogomaisfamoso->getNome().'</h3>
       <p><a href="'.urlToPage('game').'&id='.$jogomaisfamoso->getId().'" class="btn btn-primary btn-large">Veja <span class="glyphicon glyphicon-chevron-right"></span></a></p>

      </div></div>
		</div>

	</div>
	</div>';
$main_content .= '<div class="row clearfix"><cente><h2>Outros Games</h2></cente>';
foreach(getDeveloperGameList() as $game){
 $tmp_game = new Game($game['games']['id']);
 if($tmp_game->isPublicado())
    $main_content .= '<div class="col-md-3 column">
<div class="thumbnail">
      <img src="'.URL.'/media/games/'.$tmp_game->getId().'/logo" class="img-box" alt="'.$tmp_game->getNome().'">
      <div class="caption">
        <h3>'.$tmp_game->getNome().'</h3>
        <p><a href="'.urlToPage('game').'&id='.$tmp_game->getId().'" class="btn btn-primary" role="button">Ver mais...</a></p>
      </div>
    </div>
		</div>';
}
$main_content .='</div>';
$layout_bottom .='<script src="js/game.js"></script>';
?>