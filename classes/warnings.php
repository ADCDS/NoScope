<?php

class Warnings
{
    const TYPE_ALL = 0; // parameter for some functions to return 'errors and notices'
    const TYPE_NOTICE = 1; // show information for website user, for example 'this name contains illegal letters' [when create account]
    const TYPE_ERROR = 2; // show important information about bug that administrator must fix, for example 'file ./logs/paypal_transactions.log does not exist'
    const TYPE_CRITIC = 3; // show error information and block script execution
    const TYPE_WARNING = 4;
    const TYPE_SUCCESS = 5;
    const LOCATION_VERTICAL_TOP = 'top';
    const LOCATION_VERTICAL_BOTTOM = 'bottom';
    const LOCATION_HORIZONTAL_RIGHT = 'right';
    const LOCATION_HORIZONTAL_CENTER = 'center';
    const LOCATION_HORIZONTAL_LEFT = 'left';
    private $warnings = array ();
    private $errors = array();
    private $notices = array();
    private $success = array();

    public function addSuccess($id = null, $text = null, $title = null, $Dismissable = null, $vertical = null, $horizontal = null, $delay = null)
    {
        $this->success[] = new Warning($id,$text, Warnings::TYPE_SUCCESS, $title, $Dismissable, $vertical, $horizontal, $delay);
    }
    public function addWarning($id = null, $text = null, $title = null, $Dismissable = null, $vertical = null, $horizontal = null, $delay = null)
    {
        $this->warnings[] = new Warning($id,$text, Warnings::TYPE_WARNING, $title, $Dismissable, $vertical, $horizontal, $delay);
    }
    public function addNotice($id = null, $text = null, $title = null, $Dismissable = null, $vertical = null, $horizontal = null, $delay = null)
    {
        $this->notices[] = new Warning($id, $text, Warnings::TYPE_NOTICE, $title, $Dismissable, $vertical, $horizontal, $delay);
    }

    public function addError($id = null, $text = null, $title = null, $Dismissable = null, $vertical = null, $horizontal = null, $delay = null)
    {
        $this->errors[] = new Warning($id, $text, Warnings::TYPE_ERROR, $title, $Dismissable, $vertical, $horizontal, $delay);
    }

    public function addCriticError($id = null, $text = null, $errors = array())
    {
        new Error_Critic($id, $text, $errors);
    }

    public function addErrors($array)
    {
        $this->errors = array_merge($this->errors, $array);
    }

    public function addNotices($array)
    {
        $this->notices = array_merge($this->notices, $array);
    }

    public function getErrorsList($type = Warnings::TYPE_ALL)
    {
        if($type == Warnings::TYPE_ALL)
            return array_merge($this->notices, $this->errors, $this->success, $this->warnings);
        elseif($type == Warnings::TYPE_NOTICE)
            return $this->notices;
        elseif($type == Warnings::TYPE_ERROR)
            return $this->errors;
        elseif($type == Warnings::TYPE_WARNING)
            return $this->warnings;
        elseif($type == Warnings::TYPE_SUCCESS)
            return $this->success;
        else
            return array();
    }

    public function isErrorsListEmpty($type = Warnings::TYPE_ALL)
    {
        if($type == Warnings::TYPE_ALL)
            $arr = array_merge($this->notices, $this->errors, $this->warnings, $this->success);
        elseif($type == Warnings::TYPE_NOTICE)
            $arr = $this->notices;
        elseif($type == Warnings::TYPE_ERROR)
            $arr = $this->errors;
        elseif($type == Warnings::TYPE_SUCCESS)
            $arr = $this->success;
        elseif($type == Warnings::TYPE_WARNING)
            $arr = $this->warnings;
        else
            $arr = array();
        return empty($arr);
    }

    public function getErrorsCount($type = Warnings::TYPE_ALL)
    {
        if($type == Warnings::TYPE_ALL)
            return count($this->notices) + count($this->errors) + count($this->success) + count($this->warnings);
        elseif($type == Warnings::TYPE_NOTICE)
            return count($this->notices);
        elseif($type == Warnings::TYPE_ERROR)
            return count($this->errors);
        elseif($type == Warnings::TYPE_SUCCESS)
            return count($this->success);
        elseif($type == Warnings::TYPE_WARNING)
            return count($this->warnings);
        else
            return 0;
    }
}