<?php
/**
 * Created by PhpStorm.
 * User: Particular
 * Date: 08/06/14
 * Time: 12:10
 */

class Menu {
private $idMenu, $type, $value, $name, $visibility, $onlyGuest;

    function __construct($texto)
    {
       global $mysql;
        $array = $mysql->row(array('table' => 'menus',
            'condition' => 'id = "'.$texto.'"'
        ));

        $this->setIdMenu($array['id']);
        $this->setType($array['type']);
        $this->setValue($array['value']);
        $this->setName($array['name']);
        $this->setVisibility($array['visibility']);
        $this->setOnlyGuest($array['onlyGuest']);

    }

    /**
     * @param mixed $idMenu
     */
    public function setIdMenu($idMenu)
    {
        $this->idMenu = $idMenu;
    }

    /**
     * @return mixed
     */
    public function getIdMenu()
    {
        return $this->idMenu;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $onlyLogged
     */
    public function setOnlyGuest($onlyGuest)
    {
        $this->onlyGuest = $onlyGuest;
    }

    /**
     * @return mixed
     */
    public function getOnlyGuest()
    {
        return $this->onlyGuest;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * @return mixed
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

} 