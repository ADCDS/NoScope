<?php
/**
 * Created by PhpStorm.
 * User: hal
 * Date: 3/19/14
 * Time: 1:31 PM
 */

class news {
    const LOADTYPEID = 'id';

    private $data = array('id'=>null,'author'=>null,'author_ip'=>null,'category'=>null,'views'=>null,'title'=>null,'seo_name'=>null,'keywords'=>null,'data'=>null,'date'=>null,'game_id'=>null,'publicado'=>null);

    public function __construct($id=false){
        if($id){
            $this->load($id);
        }
    }
    public function load($texto, $campo = self::LOADTYPEID){
       global $mysql;
        $this->data = $mysql->row(array('table' => 'news',
            'condition' => $campo.' = "'.$texto.'"'
        ));
    }
    public function loadById($id){
        $this->load($id, self::LOADTYPEID);
    }

    public function save(){
        global $mysql;
        if($this->data['id']!=null){
           if($mysql->update('news', $this->data, 'id='.$this->data['id']))
               return true;
        }else{
            unset($this->data['date']);
            if($mysql->insert('news', $this->data))
                return true;
        }
        return false;
    }

    /* Getters e Setters das variaveis */

    public function getId(){
        return $this->data['id'];
    }
    public function getAuthor(){
        return $this->data['author'];
    }
    public function getAuthorip(){
        return $this->data['author_ip'];
    }
    public function getCategory(){
        return $this->data['category'];
    }
    public function getViews(){
        return $this->data['views'];
    }
    public function getTitle(){
        return $this->data['title'];
    }
    public function getSeoname(){
        return $this->data['seo_name'];
    }
    public function getKeywords(){
        return $this->data['keywords'];
    }
    public function getData(){
        return $this->data['data'];
    }
    public function getDate(){
        return $this->data['date'];
    }
    public function getGameid(){
        return $this->data['game_id'];
    }
    public function getPublicado(){
        return $this->data['publicado'];
    }

    public function Despublicar(){
        $this->data['publicado'] = 0;
        return $this->save();
    }
    public function Publicar(){
       $this->data['publicado'] = 1;
       return $this->save();
    }
    public function setId($var){
         $this->data['id'] = $var;
    }
    public function setAuthor($var){
         $this->data['author'] = $var;
    }
    public function setAuthorip($var){
         $this->data['author_ip'] = $var;
    }
    public function setCategory($var){
         $this->data['category'] = $var;
    }
    public function setViews($var){
         $this->data['views'] = $var;
    }
    public function setTitle($var){
         $this->data['title'] = $var;
    }
    public function setSeoname($var){
         $this->data['seo_name'] = $var;
    }
    public function setKeywords($var){
         $this->data['keywords'] = $var;
    }
    public function setData($var){
         $this->data['data'] = $var;
    }
    public function setDate($var){
         $this->data['date'] = $var;
    }
    public function setGameid($var){
         $this->data['game_id'] = $var;
    }


    public function addView(){
        $this->data['views']++;
        $this->save();
    }
}