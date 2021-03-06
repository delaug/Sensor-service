<?php


namespace src;

use SQLite3;

/**
 * Class DB
 * @package src
 */
class DB
{
    /**
     * @var SQLite3
     */
    private $db;
    /**
     * Path to DB file
     * @var string
     */
    private $dbpath = __DIR__ . '/../db/';

    /**
     * DB name
     * @var string
     */
    private $dbname = 'store.db';

    /**
     * DB constructor.
     */
    public function __construct()
    {
        // Check dir exist
        if (!is_dir($this->dbpath))
            mkdir($this->dbpath);

        $this->db = new SQLite3($this->dbpath . $this->dbname);
        $this->db->exec("CREATE TABLE IF NOT EXISTS 
            sensors (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                created_at DATETIME,
                temperature REAL,
                humidity REAL,
                pressure REAL,
                altitude REAL,
                battery_voltage REAL,
                battery_percent REAL
            )"
        );
    }

    /**
     * Add value
     * @param $arValues
     * @return \SQLite3Result
     */
    public function add($arValues)
    {
        $stmt = $this->db->prepare("INSERT INTO 
            sensors (
                created_at, 
                temperature, 
                humidity, 
                pressure, 
                altitude,
                battery_voltage,
                battery_percent     
            ) VALUES(
                datetime(CURRENT_TIMESTAMP, 'localtime'),
                :temperature,
                :humidity,
                :pressure,
                :altitude,
                :battery_voltage,
                :battery_percent 
            )"
        );
        $stmt->bindValue(':temperature', $arValues['temperature'], SQLITE3_FLOAT);
        $stmt->bindValue(':humidity', $arValues['humidity'], SQLITE3_FLOAT);
        $stmt->bindValue(':pressure', $arValues['pressure'], SQLITE3_FLOAT);
        $stmt->bindValue(':altitude', $arValues['altitude'], SQLITE3_FLOAT);
        $stmt->bindValue(':battery_voltage', $arValues['bat_voltage'], SQLITE3_FLOAT);
        $stmt->bindValue(':battery_percent', $arValues['bat_percent'], SQLITE3_FLOAT);
        return $stmt->execute();
    }

    /**
     * Get list
     * @return array
     */
    public function getList()
    {
        $result = [];

        $res = $this->db->query("SELECT * FROM sensors ORDER BY created_at DESC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }
}