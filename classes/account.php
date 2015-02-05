<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Particular
 * Date: 16/03/14
 * Time: 19:29
 * To change this template use File | Settings | File Templates.
 */

class Account{
    const LOADTYPEID = 'id';
    const LOADTYPEEMAIL = 'email';
    const LOADTYPENICK = 'nick';

    private $data = array('id'=>null,'name'=>null,'nick'=>null,'password'=>null,'email'=>null,'group'=>null,'datareg'=>null,'lastlogin'=>null,'banned'=>null,'points'=>null, 'validado'=>1);
    private $createAcc = false;
    public function __construct($id=false){
     if($id){
         $this->load($id);
     }
 }
    public function load($texto, $campo = self::LOADTYPEID){
        if(!accExists($texto, false, $campo))
            return false;
        global $mysql;
         $this->data = $mysql->row(array('table' => 'accounts',
         'condition' => $campo.' = "'.$texto.'"'
         ));
    }
    public function loadByNick($nick){
        $this->load($nick, self::LOADTYPENICK);
    }
    public function loadById($id){
        $this->load($id, self::LOADTYPEID);
    }
    public function loadByEmail($email){
        $this->load($email, self::LOADTYPEEMAIL);
    }
    public function save(){
       global $mysql;
        if($this->createAcc){//Criando nova Conta
          $this->data = array_filter($this->data);
        if($AccountId = $mysql->insert('accounts', $this->data)){
            if(($mysql->insert('validations', array(
                'code' => $rand = generateValidationCode(),
                'accounts_id' => $AccountId
            )))&&($mysql->insert('accounts_options', array('accounts_id'=>$AccountId))))//Cria um registro na tabela de validações e opções mail($this->getEmail(),'Sua conta na '.NAME,'Aqui está seu código: '.$rand.' Bem vindo a '.NAME,"From: admin@gamesite.com\n")
           return true;
            return false;
        }else{
            return false;
        }

        }else{//Não está criando
        if($mysql->update('accounts',$this->data,'id = '.$this->data['id'])){//Salvou
            return true;
        }else{//Deu erro
            return false;
        }
        }
    }

    public function getCreateAcc()
    {
        return $this->createAcc;
    }

    public function setCreateAcc($createAcc)
    {
        $this->createAcc = $createAcc;
    }

    /* Getters */


    public function getNick(){
        return $this->data['nick'];
    }
    public function getId(){
        return $this->data['id'];
    }
    public function getName(){
        return $this->data['name'];
    }
    public function getPassword(){
        return $this->data['password'];
    }
    public function getEmail(){
        return $this->data['email'];
    }
    public function getDataReg(){
        return $this->data['datareg'];
    }
    public function getLastLogin(){
        return $this->data['lastlogin'];
    }

    public function getGroup(){
        return $this->data['group'];
    }

    public function setNick($s){
        $this->data['nick']=$s;
    }

    /* Setters */

    public function setName($s){
        $this->data['name']=$s;
    }

    public function setPassword($s){
        if($this->data['password']=md5($s)){
         return true;
        }
    }

    public function setLastLogin(){
        $this->data['lastlogin']=CURRENT_TIME;
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }

    public function setEmail($s){
       $this->data['email']=$s;
    }

    public function setGroup($s){
      $this->data['group']=$s;
    }

    public function getPoints(){
        return $this->data['points'];
    }

    public function validarConta($code){
       global $mysql;
        if(!$this->isValidated()){
        if($mysql->get('validations', 'code', 'accounts_id ='.$this->getId())==$code&&($this->data['validado']=1)&&($mysql->update('validations', array('date_validated' => CURRENT_TIME), 'accounts_id ='.$this->getId()))&&$this->save())
            return true;
        else
            return false;
        }
        }
    public function setDataReg($s){
        $this->data['datareg']=$s;
    }

    public function unbanAccount(){
        $this->data['banned']=0;
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }

    public function banAccount(){
        $this->data['banned']=1;
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }


    public function addPoints($s){
        $this->data['points']=$this->getPoints()+$s;
        if($this->save()){
            return true;
        }else{

            return false;
        }
    }

    public function removePoints($s){
        if($this->getPoints()>=$s){
            $this->data['points']=$this->getPoints()-$s;
            if($this->save()){
                return true;
            }
                 return false;
        }else{
            return false;
        }
    }

    public function checkPassword($pass){
        if($this->data['password']==md5($pass)){
            return true;
        }else{
            return false;
        }
    }

    public function isBanned(){
        if($this->data['banned']==1){
            return true;
        }else{
            return false;
        }
    }

    public function isAdmin(){
        if($this->getGroup()==4)
            return true;
        else
            return false;
    }

    public function isValidated(){
        if($this->data['validado']!=0)
            return true;
        return false;
    }

    public function getOption($option){
       global $mysql;
        $result = $mysql->get('accounts_options', $option, 'accounts_id='.$this->getId());
        if($result==1) $result='checked';
        return $result;
    }

    public function setOption($option,$value){
        if($option=='bio'){//Se for a bio, faz uma checagem das palavras para previnir bugs no layout
           $value = preg_replace('/([a-zA-Z1-9]{50})/', '$1 ', $value);
        }
       global $mysql;
        return $mysql->update('accounts_options', array($option => $value), 'accounts_id='.$this->getId());
    }

    public function sendFriendRequest($uid){
        if(accExists($uid)&&!checkFriendRequest($this->getId(),$uid)){//Checa se o outro usuário existe e Checa se já não existe um pedido de amizade
       global $mysql;
        $notification = new Notification($this->getId(),$uid,null,CURRENT_TIME,'friend_request');
        $mysql->insert('friends', array (
            'from_acc' => $this->getId(),
            'to_acc' => $uid,
            'data' => CURRENT_TIME,
            'notifications_id' => $notification->id
        ));
        return true;
        }else{
        return false;
        }
    }
    public function denyFriendRequest($uid){
        if(accExists($uid)&&$friend_request = checkFriendRequest($this->getId(),$uid, false, true)){//Checa se a conta existe e se há um pedido de amizade entre elas
       global $mysql;
        $mysql->update('friends', array('accepted'=>'-1'), 'id = '.$friend_request);
        return true;
        }else{
            return false;
        }

    }
    public function acceptFriendRequest($uid){
        if(accExists($uid)&&$friend_request = checkFriendRequest($this->getId(),$uid, false, true)){//Checa se a conta existe e se há um pedido de amizade entre elas
           global $mysql;
            $mysql->update('friends', array('accepted'=>'1'), 'id = '.$friend_request);
            return true;
        }else{
            return false;
        }
    }

    public function removeFriend($uid){
        if(accExists($uid)&&$friend_request = checkFriendRequest($this->getId(),$uid, true, false)){//Checa se a conta existe e se há uma amizade estabelecida entre elas
           global $mysql;
            $mysql->delete('friends', 'id = '.$friend_request);
            return true;
        }else{
            return false;
        }
    }

    public function getDevelopedGames(){
        if($this->getGroup()>=2){
            global $mysql;
            return $mysql->query('SELECT * FROM games WHERE aprovado=1 AND desenvolvedor='.$this->getId());
        }
        return false;
    }

    public function getGames(){
        global $mysql;
        $result = array();
        foreach($mysql->query('SELECT * FROM accounts_has_games WHERE accounts_id='.$this->getId()) as $game){
            $result[] = $game['accounts_has_games']['games_id'];
        }
        return $result;
    }

    public function comprarGame($gid){
        global $mysql;
        if(gameExists($gid)){
        $game = $mysql->query('SELECT * FROM games WHERE id='.$gid);
        if($this->getPoints()>=$game[0]['games']['value']){
            if($this->removePoints($game[0]['games']['value'])){
                $developer = $game[0]['games']['desenvolvedor'];
                $developer = new Account($developer);
                $developer->addPoints($game[0]['games']['value']);
                new Notification($this->getId(), $developer->getId(), "<b>".$this->getNick()."</b> comprou ".$game[0]['games']['nome']." por ".$game[0]['games']['value']." Copes", CURRENT_TIME, 'custom');
          $mysql->insert('accounts_has_games', array('accounts_id'=>$this->getId(),'games_id'=>$game[0]['games']['id']));
            $mysql->insert('buy_history', array('games_id'=>$gid,'accounts_id'=>$this->getId(),'copes'=>$game[0]['games']['value']));
            return true;
            }
        }
        }
        return false;
    }

    public function temGame($gid){
       global $mysql;
        if(gameExists($gid)){
           if($mysql->get('accounts_has_games', 'accounts_id', 'accounts_id='.$this->getId().' AND games_id='.$gid)!=""){
               return true;
           }
        }
    return false;
    }

}
