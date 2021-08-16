<?php

class View
{
    private $_name;
    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function html($parameters = array())
    {
        if (!file_exists(__DIR__ . "/../views/" . $this->_name . ".php")) {
            return false;
        }
        foreach ($parameters as $name => $value) {
            $$name = $value;
        }
        require_once __DIR__ . "/../views/" . $this->_name . ".php";
        return true;
    }
}
