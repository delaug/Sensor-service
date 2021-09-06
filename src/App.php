<?php

namespace src;

use src\DB as DB;
use function Couchbase\defaultDecoder;

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
     * App constructor.
     * @param $configs
     */
    public function __construct($configs)
    {
        $this->configs = $configs;
        $this->DB = new DB();

        $this->add([
            'token' => 'sdfZX5)@34Zxf',
        ]);
    }

    /**
     * Validate date before store
     * @param $arData
     * @return bool
     */
    private function validate($arData) {
        if(!$arData){
            echo 'Error: Empty data!';
            return false;
        }

        if(!$arData['token']) {
            echo 'Error: Empty token!';
            return false;
        }

        if(!in_array($arData['token'], array_values($this->configs['tokens']))) {
            echo 'Error: Wrong token!';
            return false;
        }

        if(count(array_intersect_key($arData, ['temperature','humidity','pressure', 'altitude'])) != 4) {
            echo 'Error: Lost value(s)!';
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