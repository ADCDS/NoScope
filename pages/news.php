<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 10/10/14
 * Time: 17:27
 */
if(newsExists($_GET['nid'])){
    $news = new news($_GET['nid']);
    $jogo = new game($news->getGameid());
    $news->addView();
$main_content .= '<div class="panel panel-success"><div class="panel-heading">
        <h2 class="panel-title">' . $news->getTitle() . ' <a href="' . urlToPage('game') . '&id=' . $jogo->getId() . '"> <img src="' . URL . '/media/games/' . $jogo->getId() . '/icon" width="32" height="32"/> ' . $jogo->getNome() . '</a>
					</h2>
      </div>
      <div class="panel-body">
        <center><well><small style="opacity: 0.5">' . format_interval($news->getDate()) . ' - <a href="' . urlToPage('profile') . '&value1=' . $news->getAuthor() . '">' . getNick($news->getAuthor()) . '</a></small></well></center>
					<div class="noticias">' . $news->getData() . '</div>
							<hr>';
							
					if(isset($acc)&&($acc->isAdmin()||$jogo->getDesenvolvedor()==$acc->getId())){
					$main_content .='<div class="pull-right"><a href="'.URL.'/?page=panel_game&id='.$jogo->getId().'&news=1&edit_new='.$news->getId().'"><span class="glyphicon glyphicon-edit"></span></a><a id="unpublish" href="'.URL.'/?page=panel_game&id='.$jogo->getId().'&news=1&unpublish='.$news->getId().'"><span class="glyphicon glyphicon-remove"></span></a><a href="'.URL.'/?page=panel_game&id='.$jogo->getId().'&news=1&delete='.$news->getId().'"><span class="glyphicon glyphicon-trash"></span></a></div>';
					}
					$main_content .='<h3><span class="label label-default">Comentários</span></h3>
					<iframe class="well well-small" width="100%" scrolling="no" onload="calcHeight(\'commentArea\',50)" height="1px"id="commentArea" frameborder="0" src="./external/comments.php?nid='.$news->getId().'"></iframe>
      </div></div>';

}else{
    $main_content .= '<div class="panel panel-danger"><div class="panel-heading">
        <h3 class="panel-title">Erro</h3>
      </div>
      <div class="panel-body">
        <center>Essa notícia não existe</center>
      </div></div>';
}
