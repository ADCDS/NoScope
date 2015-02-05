<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 24/06/14
 * Time: 12:53
 */

class Notification {
    public $id;
    public $type;
    public $to_user;
    public $from_user;
    public $reference;
    public $data;
    public $newcount;

    function __construct($from_user=null, $to_user=null, $reference=null, $data=null, $type=null)
    {
        if($to_user){
        $this->from_user = $from_user;
        $this->reference = $reference;
        $this->data = $data;
        $this->to_user = $to_user;
        $this->type = $type;
        }
        if(!$this->Add())
            return false;
        return true;
    }


    public function getAllNotificationsFromUser() {
       global $mysql;
        $this->newcount = Notification::getCount($this->to_user);
        $result = $mysql->select(array (
            'table' => 'notifications',
            'fields' => '*',
            'condition' => '`to_acc` = '.$this->to_user,
            'order' => '`data` DESC',
            'limit' => 10
        ));
        if ($result) {
            return $result;
        }
        return false; //none found
    }
    public function Add() {
       global $mysql;
        if($this->from_user==null){
            $result = $mysql->insert('notifications',array (
                'to_acc' => $this->to_user,
                'reference' => $this->reference,
                'type' => $this->type
            ));
    }else{
            $result = $mysql->insert('notifications',array (
                'to_acc' => $this->to_user,
                'from_acc' => $this->from_user,
                'reference' => $this->reference,
                'type' => $this->type
            ));
        }
        if ($result) {
            $this->id = $result;
            return $result;
        }
        return false; //none found
     }
    static function Seen($nid) {
       global $mysql;
        $result = $mysql->update('notifications',array ('seen' => 1),'id='.$nid);
        if ($result) {
            return $result;
        }
        return false; //none found
    }
    public function getUnSeenCount() {
       global $mysql;
        $result = $mysql->select(array (
            'table' => 'notifications',
            'fields' => 'count(*) as num',
            'condition' => '`to_acc` = '.$this->to_user.' AND seen=0',
            'limit' => 10
        ));

        if ($result) {
            $this->newcount = $result[0][0]['num'];
            return $result[0][0]['num'];
        }
        return false; //none found
    }
    public function getCount() {
       global $mysql;
        $result = $mysql->select(array (
            'table' => 'notifications',
            'fields' => 'count(*) as num',
            'condition' => '`to_acc` = '.$this->to_user,
            'limit' => 10
        ));

        if ($result) {
            $this->newcount = $result[0][0]['num'];
            return $result[0][0]['num'];
        }
        return false; //none found
    }
    static function deleteNotification($id) {
       global $mysql;
        $result = $mysql->delete('notifications','id ='.$id);
        if ($result) {
            return $result;
        }
        return false; //none found
    }
} 