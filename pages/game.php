<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 26/07/14
 * Time: 15:37
 */
if(isset($_GET['id'])){
    if(gameExists($_GET['id'], true)){
        
        $layout_bottom .= '<script src="js/game.js"></script>';
        $game = new Game($_GET['id']);
        $game->addView();
        $title = $game->getNome();
        $main_content .= '<link rel="shortcut icon" href="'.URL.'/media/games/'.$game->getId().'/icon">
        <style>
.detailBox {
    border:1px solid #bbb;
}
.titleBox {
    background-color:#fdfdfd;
    padding:10px;
}
.titleBox label{
  color:#444;
  margin:0;
  display:inline-block;
}

.commentBox {
    padding:10px;
    border-top:1px dotted #bbb;
}
.commentBox .form-group:first-child, .actionBox .form-group:first-child {
    width:80%;
}
.commentBox .form-group:nth-child(2), .actionBox .form-group:nth-child(2) {
    width:18%;
}
.actionBox .form-group * {
    width:100%;
}
.taskDescription {
    margin-top:10px 0;
}
.commentList {
    padding:0;
    list-style:none;
    max-height:200px;
    overflow:auto;
}
.commentList li {
    margin:0;
    margin-top:10px;
}
.commenterImage {
    width:30px;
    margin-right:5px;
    height:100%;
    float:left;
}
.commenterImage img {
    width:100%;
    border-radius:50%;
}
.commentText p {
    margin:0;
}
.sub-text {
    color:#aaa;
    font-family:verdana;
    font-size:11px;
}
.actionBox {
    border-top:1px dotted #bbb;
    padding:10px;
}</style>
        <div class="panel panel-success"><div class="panel-heading">
      <img heigth=16px width=16px src="'.URL.'/media/games/'.$game->getId().'/icon"></img>'.$game->getNome().'
      </div>
      <div class="panel-body">
       <div class="row clearfix">
		<div class="col-md-12 column">
			<div class="row clearfix">
				<div class="col-md-12 column">
				<div class="jumbotron">
               <center>
                <img class="img-responsive" src="'.URL.'/media/games/'.$game->getId().'/logo" />
                </center>
                </div>

				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-9 column">
					<div class="row clearfix">
						<div class="col-md-12 column">
						<div class="well well-sm">
<div id="myCarousel" class="carousel slide" data-interval="3000" data-ride="carousel">
    	<!-- Carousel indicators -->
        <ol class="carousel-indicators">';
$dir = new DirectoryIterator('./media/games/'.$game->getId().'/images/');
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
 $dir = new DirectoryIterator('./media/games/'.$game->getId().'/images/');
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
<img class="img-responsive" src="'.URL.'/media/games/'.$game->getId().'/images/'.$x.'">
                <div class="carousel-caption">
                  <h3>'.$game->getNome().'</h3>
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

						';
        $main_content .='
</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-md-12 column">
						<div class="well well-lg noticias">';
        foreach($game->getNews('2') as $news){
        $noticia = new news($news['news']['id']);

            $main_content.= '<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">'.$noticia->getTitle().' - <small>'.format_interval($noticia->getDate()).'</small></h3>
  </div>
  <div class="panel-body">
'.maxDotDotDot($noticia->getData(), 750).'
    </div>
							 <div class="panel-footer"><a class="btn" href="'.urlToPage('news').'&nid='.$noticia->getId().'">Ler Mais <span class="glyphicon glyphicon-chevron-right"></span></a></div>
  </div>

							';
        }
						$main_content .='
						<small>Desenvolvido por <b>'.getNick($game->getDesenvolvedor()).'</b></small>
</div>
                        </div>
					</div>
				</div>
				<div class="col-md-3 column">
				<span class="label label-default">Compre o Jogo</span>
					<div class="well"><h3>';
				if($game->getValue()==0){
                    $main_content .= 'Gratuito';
                }elseif($game->getValue()==1){
                    $main_content .= '1 Cope';
                }else{
			$main_content .= $game->getValue().' Copes';
		}
					$main_content .='</h3>


					';
        if(isLogged()){
            if($game->getDesenvolvedor()!=$_SESSION['userId']){
        if(!$GLOBALS['acc']->temGame($game->getId())){
            $main_content .='<button id="buygame" data-g="'.$game->getId().'" type="button" class="btn btn-default btn-block">Comprar</button>';
        }else{
            $tem = true;
            $main_content .='<button id="downloadgame" data-g="'.$game->getId().'" type="button" class="btn btn-success btn-block">Download</button>';
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
					<iframe class="well well-small" style="width: 100%;" height="140px" src="./external/rating.php?gid=/game/'.$game->getId().'"></iframe>
					</h3>
                    </div>
                    <div class="col-md-3 column">
					<div class="detailBox">
    <div class="titleBox">
      <label>Comentários</label>
    </div>
    <div class="commentBox">
<button type="button" class="close" onclick="$(\'.commentBox\').remove();">&times;</button>
        <p class="taskDescription">Esses são os comentários de alguns usuários que compraram esse jogo.</p>
    </div>
    <div class="actionBox">
        <ul class="commentList">
            ';
        $resenhas = '';
        foreach($mysql->query('SELECT * FROM reviews WHERE gid='.$game->getId().' ORDER BY date DESC') as $resenha){
            $resenha = $resenha['reviews'];
            $resenhas .= '<li>
                <div class="commenterImage">
                  <img src="'.getAccountAvatarURL($resenha['uid']).'" />
                </div>
                <div class="commentText">
                 ';
            if($resenha['uid']==@$_SESSION['userId']){
                $resenhas .= ' <button type="button" class="close pull-right" onclick="deletaResenha('.$resenha['id'].')">&times;</button>';
            }
            $resenhas .='
                     <p class="">'.$resenha['data'].'</p> <span class="date sub-text"><a href="'.urlToPage('profile').'&value1='.$resenha['uid'].'">'.getNick($resenha['uid']).'</a> '.format_interval($resenha['date']).'</span>

                </div>
            </li>';
        }
        if($resenhas!=''){
            $main_content .= $resenhas;
        }else{
            $main_content .= 'Não há comentários';
        }
        $main_content .='
        </ul>';
if(@$tem&&$mysql->get('reviews', 'id', 'gid='.$game->getId().' AND uid='.$GLOBALS['acc']->getId())==null){
        $main_content .='<div class="form-inline">
            <div class="form-group">
                <input id="resenhatext" name="resenhatext" class="form-control" type="text" placeholder="Seu comentário" />
            </div>
            <div class="form-group">
                <div id="enviaResenha" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-right"></span></div>
            </div>
        </div>';
}
        $main_content .='
    </div>
</div>
</div>
					<div class="row clearfix">
						<div class="col-md-12 column">
						<!--<hr>
							<ul>
								<li>
									Update 1 - Data
								</li>
								<li>
									Update 2 - Data
								</li>
							</ul>-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
      </div></div>';
        $layout_bottom .= '<script type="text/javascript">var GID = '.$game->getId().';</script>';
    }elseif(gameExists($_GET['id'], true)){
       $main_content.='<div class="panel panel-danger"><div class="panel-heading">
        <h3 class="panel-title">Erro</h3>
      </div>
      <div class="panel-body">
        <center>Esse jogo não está publicado</center>
      </div></div>';
    }else{
        $main_content.='<div class="panel panel-danger"><div class="panel-heading">
        <h3 class="panel-title">Erro</h3>
      </div>
      <div class="panel-body">
        <center>Esse jogo não existe</center>
      </div></div>';
    }
}
