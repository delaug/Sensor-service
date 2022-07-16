<?php

namespace src;

use src\DB as DB;

require_once 'DB.php';

/**
 * Class App
 * @package src
 */
class App
{
    /**
     * @var
     */
    private $configs;

    /**
     * @var \src\DB
     */
    private $DB;

    /**
     * Required fields for validation
     * @var string[]
     */
    protected $required = ['temperature','humidity','pressure', 'altitude', 'bat_voltage', 'bat_percent'];

    /**
     * App constructor.
     * @param $configs
     */
    public function __construct($configs)
    {
        $this->configs = $configs;
        $this->DB = new DB();

        // Read request
        if(isset($_GET) && !empty($_GET))
            $this->add($_GET);
    }

    /**
     * Validate date before store
     * @param $arData
     * @return bool
     */
    private function validate($arData) {
        if(!$arData){
            echo 'Error: Empty data!<br>';
            return false;
        }

        if(!$arData['token']) {
            echo 'Error: Empty token!<br>';
            return false;
        }

        if(!in_array($arData['token'], array_values($this->configs['tokens']))) {
            echo 'Error: Wrong token!<br>';
            return false;
        }

        // Check required fields
        $errors = [];
        foreach ($this->required as $field) {
            if(!in_array($field, array_keys($arData))) {
                $errors[] = 'Error: Required field: "'.$field.'"<br>';
            }
        }

        if($errors) {
            foreach ($errors as $error)
                echo $error;
            return false;
        }

        return $arData;
    }

    /**
     * Add data
     * @param $arData
     */
    public function add($arData) {
        if($validated = $this->validate($arData)){
            $this->DB->add($validated);
        }
    }

    /**
     * Get all data
     * @return array
     */
    public function getData() {
        return $this->DB->getList();
    }
}