<?php

namespace Lanous\db;

class Connect {

    private $database;
    protected $config;

    /**
     * Establishing a connection with the database
     * @param string $db_name database name
     * @param string $host hostname
     * @param string $username username
     * @param string $password password
     * @param string $config config class (optional)
     */
    public function __construct(string $db_name,string $host='127.0.0.1',string $username='root',string $password='') {
        try {
            $this->database = new \PDO("mysql:host=$host;dbname=".$db_name, $username, $password);
            $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            throw new exceptions\init($e->getMessage());
        }
    }
}