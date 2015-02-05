<?php
if(!defined('INITIALIZED'))
	exit;

class Warning
{
	private $notificationId = '';
    private $notificationText = 'no text';
	private $notificationType = Warnings::TYPE_ERROR;
    private $notificationTitle = '';
    private $notificationDismissable = 'false';
    private $notificationVertical = Warnings::LOCATION_VERTICAL_BOTTOM;
    private $notificationHorizontal = Warnings::LOCATION_HORIZONTAL_RIGHT;
    private $notificationIcon = '';
    private $notificationDelay = 0;
    private $notificationTypeString = '';

  	public function __construct($id = null, $text = null, $type = null, $title = null, $Dismissable = null, $vertical = null, $horizontal = null, $delay = null)
	{
		if(isset($id))
			$this->notificationId = $id;
		if(isset($text))
			$this->notificationText = $text;
        if(isset($type)){
            $this->notificationType = $type;
            switch($type){
                case Warnings::TYPE_ERROR:
                    $this->notificationIcon = "glyphicon glyphicon-remove";
                    $this->notificationTypeString = "danger";
                    break;
                case Warnings::TYPE_SUCCESS:
                    $this->notificationIcon = "glyphicon glyphicon-ok";
                    $this->notificationTypeString = "success";
                    break;
                case Warnings::TYPE_WARNING:
                    $this->notificationIcon = "glyphicon glyphicon-exclamation-sign";
                    $this->notificationTypeString = "warning";
                    break;
                case Warnings::TYPE_NOTICE:
                    $this->notificationIcon = "glyphicon glyphicon-list";
                    $this->notificationTypeString = "info";
                    break;
            }
            if(!isset($title)){
            switch($type){
                case Warnings::TYPE_ERROR:
                    $this->notificationTitle = "Erro";
                    break;
                case Warnings::TYPE_SUCCESS:
                    $this->notificationTitle = "Sucesso";
                    break;
                case Warnings::TYPE_WARNING:
                    $this->notificationTitle = "Aviso";
                    break;
                case Warnings::TYPE_NOTICE:
                    $this->notificationTitle = "Informação";
                    break;
            }
            }
        }
        if(isset($title))
        $this->notificationTitle = $title;
        if(isset($Dismissable))
        $this->notificationDismissable = $Dismissable;
        if(isset($vertical))
        $this->notificationVertical = $vertical;
        if(isset($horizontal))
        $this->notificationHorizontal = $horizontal;
        if(isset($delay))
        $this->notificationDelay = $delay;

	}

	public function getId()
	{
		return $this->notificationId;
	}

	public function getText()
	{
		return addslashes($this->notificationText);
	}

    public function setDelay($notificationDelay)
    {
        $this->notificationDelay = $notificationDelay;
    }

    public function getDelay()
    {
        return $this->notificationDelay;
    }

    public function setIcon($notificationIcon)
    {
        $this->notificationIcon = $notificationIcon;
    }

    public function getIcon()
    {
        return $this->notificationIcon;
    }

	public function getType()
	{
		return $this->notificationType;
	}

    public function setTitle($notificationTitle)
    {
        $this->notificationTitle = $notificationTitle;
    }

    public function getTitle()
    {
        return addslashes($this->notificationTitle);
    }


    public function setDismissable($notificationDismissable)
    {
        $this->notificationDismissable = $notificationDismissable;
    }

    public function getDismissable()
    {
        return $this->notificationDismissable;
    }

    public function setVertical($notificationVertical)
    {
        $this->notificationVertical = $notificationVertical;
    }

    public function getVertical()
    {
        return $this->notificationVertical;
    }

    public function setHorizontal($notificationHorizontal)
    {
        $this->notificationHorizontal = $notificationHorizontal;
    }

    public function getHorizontal()
    {
        return $this->notificationHorizontal;
    }

    public function getFormattedText()
    {
    return "
    $.growl( {
  title: '".$this->getTitle()."',
  icon: '".$this->getIcon()."',
  message: '".$this->getText()."',



}, {
    allow_dismiss: '".$this->getDismissable()."',
    position: {
					from: '".$this->getVertical()."',
					align: '".$this->getHorizontal()."'
				},
    delay: ".$this->getDelay().",
    type: '".$this->notificationTypeString."',
  template: {
    title_divider: \"<hr class='separator' />\"
    },
    });
    ";
    }
}