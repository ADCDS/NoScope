<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 25/07/14
 * Time: 12:57
 */

class Game {
    const LOADTYPEID = 'id';
    private $first_version = false;
    private $data = array('id'=>null,'nome'=>null,'desenvolvedor'=>null,'desc'=>null,'value'=>null,'data'=>null,'aprovado'=>null,'publicado'=>null,'ultima_versao'=>null);
    private $extension = null;
    public function __construct($id=false){
        if($id){
            $this->load($id);
        }
    }


    public function load($texto, $campo = self::LOADTYPEID){
        global $mysql;
        $this->data = $mysql->row(array('table' => 'games',
            'condition' => $campo.' = "'.$texto.'"'
        ));
    }
    public function loadById($id){
        $this->load($id, self::LOADTYPEID);
    }

    public function save(){
        global $mysql;
        if($this->data['id']!=null){
            if($this->first_version)
            $this->data['ultima_versao']= $mysql->insert('versions', array(
                'versao'=>1,
                'date'=>CURRENT_TIME,
                'comentario'=>'Primeira Versão',
                'ext'=>$this->extension,
                'game_id'=>$this->data['id']));
            if($mysql->update('games', $this->data, 'id='.$this->data['id']))
                return true;
        }else{

            if($id=$mysql->insert('games', $this->data)){
                return true;
            }
        }
        return false;
    }


    public function getId(){
        return $this->data['id'];
    }
    public function getNome(){
        return $this->data['nome'];
    }
    public function getDesenvolvedor(){
        return $this->data['desenvolvedor'];
    }
    public function getDesc(){
        return $this->data['desc'];
    }
    public function getValue(){
        return $this->data['value'];
    }
    public function getData(){
        return $this->data['data'];
    }
    public function getAprovado(){
        return $this->data['aprovado'];
    }
    public function getPublicado(){
        return $this->data['publicado'];
    }
	public function isPublicado(){
	if($this->data['publicado']!=0)
	return true;
	return false;
	}
    public function getUltimaVersao(){
        return $this->data['ultima_versao'];
    }

    public function setId($var){
        $this->data['id']= $var;
    }
    public function setNome($var){
        $this->data['nome']= $var;
    }
    public function setDesenvolvedor($var){
        $this->data['desenvolvedor']= $var;
    }
    public function setDesc($var){
        $this->data['desc']= $var;
    }
    public function setValue($var){
        $this->data['value']= $var;
    }
    public function setData($var){
        $this->data['data']= $var;
    }
    public function setAprovado($var){
        $this->data['aprovado']= $var;
    }

    public function setUltimaVersao($var){
        $this->data['ultima_versao']=$var;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }
    public function getExtension($version = false)
    {
        if(!$version)
            $version = $this->getUltimaVersao();
        global $mysql;
        return $mysql->get('versions','ext', 'id='.$version);
    }
    public function getNews($limit=false,$onlyPublicado = true){
        global $mysql;
		if($onlyPublicado){
		if(!$limit){
        return $mysql->query('SELECT id FROM news WHERE game_id='.$this->getId().' AND publicado=1 ORDER BY date DESC');
        }
        return $mysql->query('SELECT id FROM news WHERE game_id='.$this->getId().' AND publicado=1 ORDER BY date DESC LIMIT 0,'.$limit.'');
		}else{
		if(!$limit){
        return $mysql->query('SELECT id FROM news WHERE game_id='.$this->getId().' ORDER BY date DESC');
        }
        return $mysql->query('SELECT id FROM news WHERE game_id='.$this->getId().' ORDER BY date DESC LIMIT 0,'.$limit.'');
        }
    }
    public function getStats($opt){
        global $mysql;
        switch ($opt){
            case 0:
               $result =  $mysql->get('games_stats', 'views', 'game_id='.$this->getId());
                break;
            case 1:
                $result =  $mysql->get('games_stats', 'copes', 'game_id='.$this->getId());
                break;
            case 2:
                $result =  $mysql->get('games_stats', 'downloads', 'game_id='.$this->getId());
                break;
            default:
                $result =  $mysql->query('SELECT * FROM games_stats WHERE game_id='.$this->getId());
            break;
        }
        if($result==false)
            return "0";
        return $result;
    }

    public function Publicar($first=false){
        $this->data['publicado']= 1;
        $this->first_version=$first;
        return $this->save();
    }
    public function Despublicar(){
        $this->data['publicado']= 0;
        return $this->save();
    }

    public function getVersoes(){
        global $mysql;
        return $mysql->query('SELECT * FROM versions WHERE game_id='.$this->getId().' ORDER BY id DESC');
    }
    public function getDirIdFromVersion($version){
        global $mysql;
        return $mysql->get('versions', 'dir_id', 'id='.$version.' AND game_id='.$this->getId());
    }
	public function getExtFromVersion($version){
        global $mysql;
        return $mysql->get('versions', 'ext', 'id='.$version.' AND game_id='.$this->getId());
    }
    public function getDirectDownloadLink($version = false){
        if(!$version)
            return URL.'/media/games/'.$this->getId().'/releases/'.$this->getDirIdFromVersion($this->getUltimaVersao()).'.'.$this->getExtension();
        return URL.'/media/games/'.$this->getId().'/releases/'.$this->getDirIdFromVersion($version).'.'.$this->getExtFromVersion($version);
    }
    public function getDirectDownloadPath($version = false){
        if(!$version)
            return PATH.'/media/games/'.$this->getId().'/releases/'.$this->getDirIdFromVersion($this->getUltimaVersao()).'.'.$this->getExtension();
        return PATH.'/media/games/'.$this->getId().'/releases/'.$this->getDirIdFromVersion($version).'.'.$this->getExtFromVersion($version);
    }

    public function isAprovado(){
        if($this->getAprovado()==1)
            return true;
                return false;

    }
    public function addView(){
        global $mysql;
        @$mysql->query('UPDATE games_stats SET views=views+1 WHERE game_id='.$this->getId());
    }
    public function addCope(){
        global $mysql;
        @$mysql->query('UPDATE games_stats SET copes=copes+'.$this->getValue().' WHERE game_id='.$this->getId());
    }
    public function addDownload(){
        global $mysql;
        @$mysql->query('UPDATE games_stats SET downloads=downloads+1 WHERE game_id='.$this->getId());
    }
} 