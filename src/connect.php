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
     * @param object $config config class (optional)
     */
    public function __construct(object $config) {
        try {
            $this->CheckConfig($config);
            $host = $config::hostname;
            $username = $config::username;
            $password = $config::password;
            $db_name = $config::database;
            $this->database = new \PDO("mysql:host=$host;dbname=".$db_name, $username, $password);
            $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            throw new exceptions\init($e->getMessage());
        }
    }




    private function CheckConfig(object $config) {
        $const_list = ['hostname','username','password','database'];
        $config = get_class($config);
        $reflect = new \ReflectionClass($config);
        $invalid_list = array_diff($const_list,array_keys($reflect->getConstants()));
        return count($invalid_list) == 0 ? true : throw new exceptions\config("Config class is incomplete, [".$invalid_list[0]."] constant is not defined.");
    }
}