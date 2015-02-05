<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 17/07/14
 * Time: 11:49
 */
if(!isLogged()){
    $warning->addWarning(10007, "Você precisa estar logado para acessar esta página, <a href='".urlToPage('login')."'>clique aqui</a> para fazer login.");
    $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
}else{
    if(!$GLOBALS['acc']->isValidated()){//Checa se o usuario é validado
        $warning->addWarning(10007, "Você precisa validar sua conta antes de acessar essa página, <a href='".urlToPage('validate')."'>clique aqui</a> para validar sua conta.");
        $layout_bottom .= jsRedirect($GLOBALS['lastpage']);
    }else{
        $title .= ' - Copes';
        if(isset($_GET['comprar'])){
            $main_content.='<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Meus Pontos</h3>
      </div>
       <div class="panel-body"><!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
<form action="https://pagseguro.uol.com.br/checkout/v2/cart.html?action=add" method="post" onsubmit="PagSeguroLightbox(this); return false;">
<!-- NÃO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
<input type="hidden" name="itemCode" value="3685869CDBDB5D3BB4FB9FB3DEB7C736" />
<input type="image" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/pagamentos/209x48-pagar-azul-assina.gif" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />
</form>
<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->
</div>
<a href="'.urlToPage('points').'" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-arrow-left"></span> Voltar</a>
<br/>
</div>';
        }elseif(isset($_GET['saque'])){
            if($GLOBALS['acc']->getGroup()==2){
            if(isset($_POST['points'])){
                if(is_numeric($_POST['points'])){
                        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                            if(is_numeric($_POST['metodo'])){
                                if($GLOBALS['acc']->removePoints($_POST['points'])){
                            $mysql->insert('points_transfer', array('accounts_id'=>$GLOBALS['acc']->getId(),'points'=>$_POST['points'],'email'=>$_POST['email'],'payment_type'=>$_POST['metodo'],'value'=>$_POST['points']*1-($_POST['points']/100*10)));
                            $warning->addSuccess(null, 'Tudo certo, você receberá o dinheiro assim que um administrador aprovar');
                            }else{
                                    $warning->addError(null, 'Você não tem Copes suficientes');

                            }
                            }else{
                                $warning->addError(null, 'Método de Pagamento inválido');

                        }
                        }else{
                            $warning->addError(null, 'Email Inválido');
                    }
                }else{
                    $warning->addError(null, 'Digite apenas números');
                }
            }
                $main_content .='<script type=\'text/javascript\'>$(document).ready(function (){$(\'#myTable\').pageMe({pagerSelector:\'#myPager\',showPrevNext:true,hidePageNumbers:false,perPage:4});});</script>';
        $main_content .= '<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Sacar Copes</h3>
      </div>
       <div class="panel-body">
<!-- Text input-->
<form class="form-horizontal" id="saque" method="post">
<fieldset>



<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="points">Copes</label>
  <div class="col-md-4">
  <input id="points" name="points" type="text" placeholder="Digite aqui..." class="form-control input-md" required="">
  <span class="help-block">Quantidade de Copes que deseja sacar</span>
  </div>
</div>

<!-- Multiple Radios -->
<div class="form-group">
  <label class="col-md-4 control-label" for="metodo">Método de Pagamento</label>
  <div class="col-md-4">
  <div class="radio text-left">
    <label for="metodo-0">
      <input type="radio" name="metodo" id="metodo-0" value="1" checked="checked">
      PagSeguro
    </label>
	</div>
  <div class="radio text-left">
    <label for="metodo-1">
      <input type="radio" name="metodo" id="metodo-1" value="2">
      PayPal
    </label>
	</div>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email">Email</label>
  <div class="col-md-4">
  <input id="email" name="email" type="text" placeholder="Digite aqui..." class="form-control input-md" required="">
  <span class="help-block">Email do PagSeguro ou PayPal que receberá o pagamento</span>
  </div>
</div>
<span class="help-block text-info text-center">Todo pagamento será feito com BRL(Real Brasileiro)<br/>10% do Total de Copes sacadps fica com a '.NAME.'</span>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-success">Enviar</button>
  </div>
</div>

</fieldset>
</form>

  <hr>
  <h3>Histórico</h3>
  <table class="table table-hover">
  <thead>
  <tr>
  <th>ID</th>
  <th>Quantidade (Copes)</th>
  <th>Valor a receber</th>
  <th>Método de Pagamento</th>
  <th>Email de Recebimento</th>
  <th>Data</th>
  <th>Recebido?</th>
  <th>Aprovado?</th>
  </tr>
  </thead>
  <tbody id="myTable" class="searchable">
  ';
  foreach($mysql->query('SELECT * FROM points_transfer WHERE accounts_id='.$GLOBALS['acc']->getId().' ORDER BY data DESC') as $row){
      $main_content .='<tr class="'.($row['points_transfer']['aprovado']==-1?'':($row['points_transfer']['aprovado']==0?'danger':'success')).'">
  <td>'.$row['points_transfer']['id'].'</td>
  <td>'.$row['points_transfer']['points'].'</td>
  <td>RS$'.$row['points_transfer']['value'].',00</td>
  <td>'.$row['points_transfer']['payment_type'].'</td>
  <td>'.$row['points_transfer']['email'].'</td>
  <td>'.$row['points_transfer']['data'].'</td>
  <td>'.($row['points_transfer']['received']==1?'Recebido':'Não recebido').'</td>
  <td>'.($row['points_transfer']['aprovado']==-1?'Em Espera':($row['points_transfer']['aprovado']==0?'Negado':'Aprovado')).'</td>

  </tr>';
  }
 $main_content.=' </tbody>
  </table>
   <div class="col-md-12 text-center">
      <ul class="pagination pagination-lg pager" id="myPager"></ul>
      </div>
  </div>
  <a href="'.urlToPage('points').'" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-arrow-left"></span> Voltar</a>
</div>


       </div>
       </div>';
            }
        }else{
    $main_content .= '<div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">Meus Pontos</h3>
      </div>
      <div class="panel-body">
      <h2>Você tem</h2>
      <h1><a id="copes" href="'.urlToPage('points').'"><span class="label label-default"><span class="glyphicon glyphicon-euro"></span>'.$GLOBALS['acc']->getPoints().'</span></a></h1>
      <h2>Copes</h2>
      <a href="'.urlToPage('points').'&comprar=1" class="btn btn-success btn-large"><i class="glyphicon glyphicon-barcode"></i> Comprar Copes</a>'.($GLOBALS['acc']->getGroup()==2?'&nbsp;&nbsp;<a href="'.urlToPage('points').'&saque=1" class="btn btn-info btn-large"><i class="glyphicon glyphicon-transfer"></i> Sacar Copes</a>':'').'
      </div>
      </div>';
    }

    }
}