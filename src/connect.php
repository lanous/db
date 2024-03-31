<?php

namespace Lanous\db;

class Connect {

    private $database;
    protected $config;

    public $Setting;

    /**
     * Establishing a connection with the database
     * @param object $config config class
     */
    public function __construct(object $config) {
        try {
            $this->CheckConfig($config);
            $host = $config::hostname;
            $username = $config::username;
            $password = $config::password;
            $db_name = $config::database;
            $project = $config::project;
            $this->database = new \PDO("mysql:host=$host;dbname=".$db_name, $username, $password);
            $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->MakeProject($project);
        } catch(\PDOException $e) {
            throw new exceptions\init($e->getMessage());
        }
    }

    /**
     * Open the database settings
     */
    public function Settings () {
        return new Settings\Settings();
    }

    private function CheckConfig(object $config) {
        $const_list = ['hostname','username','password','database','project'];
        $config = get_class($config);
        $reflect = new \ReflectionClass($config);
        $invalid_list = array_diff($const_list,array_keys($reflect->getConstants()));
        return count($invalid_list) == 0 ? true : throw new exceptions\config("Config class is incomplete, [".$invalid_list[0]."] constant is not defined.");
    }
    private function MakeProject(string $project_name) {
        if(!file_exists($project_name)) {
            mkdir($project_name);
            # ------- Tables ------- #
            mkdir($project_name."/Tables");
            copy(__DIR__."/Examples/Table.php",$project_name."/Tables/Users.php");
            copy(__DIR__."/Examples/Guides/Table.html",$project_name."/Tables/Guide.html");
        }
    }
}