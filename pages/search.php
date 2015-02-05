<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 26/07/14
 * Time: 22:50
 */
if(isset($_REQUEST['search'])){
    if(!isset($_REQUEST['type'])||!is_numeric($_REQUEST['type'])){
        $_REQUEST['type'] = 0;
    }
    switch($_REQUEST['type']){
        case 0://Pessoas
            $legend = 'Pessoas';

            break;
        case 1://Games
            $legend = 'Games';

            break;
        default://Pessoas
            $legend = 'Pessoas';

            break;
    }
$main_content .= '<div class="panel panel-success"><div class="panel-heading">
        <h3 class="panel-title">Busca por '.$_REQUEST['search'].'</h3>
      </div>
      <div class="panel-body">
       <div class="row clearfix">
		<div class="col-md-2 column">
			<ul class="nav nav-pills nav-stacked">
				<li>
					<a href="'.urlToPage('search').'&search='.$_REQUEST['search'].'&type=0">Pessoas</a>
				</li>
				<li>
					<a href="'.urlToPage('search').'&search='.$_REQUEST['search'].'&type=1">Games</a>
				</li>
			</ul>
		</div>
		<div class="col-md-10 column">
		<legend>'.$legend.'</legend>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>

						</th>
					</tr>
				</thead>
				<tbody>
					';

    switch($_REQUEST['type']){
        case 0://Pessoas

            $data = $mysql->query('SELECT * FROM accounts WHERE name LIKE "%'.$_REQUEST['search'].'%" OR nick LIKE "%'.$_REQUEST['search'].'%" OR email LIKE "%'.$_REQUEST['search'].'%"');
            foreach($data as $result){
                $main_content .= '<tr>
						<td>
						<a href="'.urlToPage('profile').'&value1='.$result['accounts']['id'].'"><img src="'.getAccountAvatarURL($result['accounts']['id']).'" class="img-thumbnail" width="80" height="80"></a>
						<a href="'.urlToPage('profile').'&value1='.$result['accounts']['id'].'">'.$result['accounts']['nick'].'</a>
						</td>
						</tr>';
            }
            break;
        case 1://Games

            $data = $mysql->query('SELECT * FROM games WHERE nome LIKE "%'.$_REQUEST['search'].'%"');
            foreach($data as $result){
                $main_content .= '<tr>
						<td>
						<a href="'.urlToPage('game').'&id='.$result['games']['id'].'"><img src="'.URL.'/media/games/'.$result['games']['id'].'/icon" class="img-thumbnail" width="80" height="80"></a>
						<a href="'.urlToPage('game').'&id='.$result['games']['id'].'">'.$result['games']['nome'].'</a>
						</td>
						</tr>';
            }
            break;
        default://Pessoas

            $data = $mysql->query('SELECT * FROM accounts WHERE name LIKE "%'.$_REQUEST['search'].'%" OR nick LIKE "%'.$_REQUEST['search'].'%" OR email LIKE "%'.$_REQUEST['search'].'%"');
            foreach($data as $result){
                $main_content .= '<tr>
						<td>
						<a href="'.urlToPage('profile').'&value1='.$result['accounts']['id'].'"><img src="'.getAccountAvatarURL($result['accounts']['id']).'" class="img-responsive"></a>
						</td>
						<td>
						<a href="'.urlToPage('profile').'&value1='.$result['accounts']['id'].'">'.getNick($result['accounts']['nick']).'</a>
						</td>
						</tr>';
            }
            break;
    }

				$main_content .='</tbody>
			</table>
		</div>
	</div>
      </div></div>';
}
?>
