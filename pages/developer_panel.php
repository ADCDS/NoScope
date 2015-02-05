<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 21/07/14
 * Time: 00:11
 */
if(!isLogged()){
        $warning->addWarning(10007, "Você precisa estar logado para acessar esta página, <a href='".urlToPage('login')."'>clique aqui</a> para fazer login.");
        $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
    }else{
        if($GLOBALS['acc']->getGroup()!=2){//Checa se é desenvolvedor
            $warning->addWarning(10008, "Você não é um desenvolvedor");
            $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
        }else{
        $title .= ' - Painel do Desenvolvedor';
$main_content .= '<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Painel do Desenvolvedor</h3>
      </div>
      <div class="panel-body">
    <div class="row clearfix">
		<div class="col-md-12 column">
			<div class="row clearfix">
				<div class="col-md-8 column text-left">
					<h3>
						Meus Games
					</h3>
					<ul>';
        if(!$GLOBALS['acc']->getDevelopedGames()){
        $main_content .= 'Você não tem nenhum jogo';
        }else{
        foreach($GLOBALS['acc']->getDevelopedGames() as $row){
            $main_content .='<li '.($row['games']['publicado']==1?'style=" background-color: rgba(92, 184, 92, 0.58); border-radius: 5px; "':'style=" background-color: rgba(217, 83, 79, 0.33); border-radius: 5px; "').'>
							<a href="'.urlToPage('game').'&id='.$row['games']['id'].'">'.$row['games']['nome'].'</a><span class="badge pull-right">'.($row['games']['publicado']!=1?'Não Publicado':'').'</span><a href="'.urlToPage('panel_game').'&id='.$row['games']['id'].'"><span class="glyphicon glyphicon-pencil pull-right"></span></a>
						</li>';
        }
        }

		$main_content .='</ul>
				</div>
				<div class="col-md-4 column">
					 <a href="'.urlToPage('points').'&saque=1" class="btn btn-block btn-success" type="button">Sacar Copes</a> <a href="'.urlToPage('new_game').'" class="btn btn-info btn-block" type="button">Novo Game</a>
				</div>
			</div>
		</div>
	</div>
	</div>
      </div>';
    }
}
