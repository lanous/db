<?php

namespace lanous\db;

class connect {

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
    public function __construct(string $db_name,string $host='127.0.0.1',string $username='root',string $password='',object $config='') {
        try {
            $this->database = new \PDO("mysql:host=$host;dbname=".$db_name, $username, $password);
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->config = $config;
        } catch(PDOException $e) {
            throw new lanous\db\exceptions\connect($e->getMessage());
        }
    }
}