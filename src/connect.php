<?php

namespace Lanous\db;

class Connect extends Lanous {

    private $project_name,$project_directory,$dbsm,$database;

    /**
     * Establishing a connection with the database
     * @param object $config config class
     */
    public function __construct(object $config) {
        try {
            $this->CheckConfig($config);

            $supported_dbsm = ['mysql'];
            $this->dbsm = $config::dbsm;
            if (!in_array($this->dbsm,$supported_dbsm))
                throw new Exceptions\NonSupport(Exceptions\NonSupport::ERR_DBSM);
            $host = $config::hostname;
            $username = $config::username;
            $password = $config::password;
            $db_name = $config::database;
            $project = $config::project_name;
            $this->project_directory = $config::project_dir;
            $this->project_name = $project;
            $this->MakeProject($project);
            $this->Autoload($project);
            $this->database = new \PDO("mysql:host=$host;dbname=".$db_name, $username, $password);
            $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->MakeTables ();
        } catch(\PDOException $e) {
            throw new Exceptions\init(Exceptions\init::ERR_CNCTERR);
        }
    }

    /**
     * Open the database settings
     */
    /*public function Settings () {
        return new Settings\Settings($this->database);
    }*/

    /**
     * It opens a table to perform core operations such as <code>Select</code>, <code>Update</code>, <code>Insert</code>, <code>Delete</code>, and <code>Describe</code>.
     * @param string $table_name The class name where table settings are stored is.
     */
    public function OpenTable (string $table_name) : Table\Table {
        return new Table\Table($table_name,$this->dbsm,$this->database);
    }
    /**
     * Loading a plugin
     * @param string $plugin_class Plugin class name
     * @param mixed $data If you want to send specific data to the plugin, set it in this parameter.
     */
    public function LoadPlugin (string $plugin_class,mixed $data=null) : object {
        return new $plugin_class($this->database,$this->dbsm,$data);
    }
    /**
     * Get a list of all data tables
     */
    public function GetTables () : array {
        $stmt = $this->database->query("SHOW TABLES");
        return array_keys($stmt->fetchAll(\PDO::FETCH_UNIQUE));
    }
    /**
     * Create a new job
     */
    public function NewJob () : Jobs\Job {
        return new Jobs\Job($this->database,$this->dbsm);
    }





    /**
     * To examine the structure of the passed configuration in the __construct constructor.
     */
    private function CheckConfig(object $config) {
        // A list of constants that are forced to be used in the config class.
        $const_list = ['hostname','username','password','database','dbsm','project_name'];
        $reflect = new \ReflectionClass($config);
        $invalid_list = array_diff($const_list,array_keys($reflect->getConstants()));
        return count($invalid_list) == 0 ? true : throw new Exceptions\Config(Exceptions\Config::ERR_CGCLSIC);
    }

    /**
     * To create main project folders and copy datatypes
     */
    private function MakeProject(string $project_name) : void {
        $this->MakeDirectory($project_name);
        # ----- Data Types ----- #
        $this->MakeDirectory($project_name."/DataTypes");
        foreach (glob(__DIR__."/Examples/DataTypes/*.phps") as $filename) {
            $to_filename = explode("/",str_replace(".phps",".php",$filename));
            $to_filename = array_pop($to_filename);
            $this->Copy($filename,$project_name."//DataTypes//".$to_filename);
        }
        $this->MakeDirectory($project_name."/Plugins");
        $this->MakeDirectory($project_name."/Tables");
    }
    private function MakeDirectory($directory) : bool {
        return !file_exists($this->project_directory."\\".$directory) ? mkdir($this->project_directory."\\".$directory) : true;
    }
    private function Copy($from,$to) : bool {
        $readfile = file_get_contents($from);
        $readfile = str_replace("namespace MyLanous","namespace ".$this->project_name,$readfile);
        return !file_exists($this->project_directory."\\".$to) ? file_put_contents($this->project_directory."\\".$to,$readfile) : true;
    }

    /**
     * Run all PHP files in the main folder of the project
     * @param string $project The name set for the project in the config class
     */
    private function AutoLoad(string $project) : void {
        $directores = scandir($this->project_directory."\\".$project);
        unset($directores[0]);
        unset($directores[1]);
        foreach ($directores as $directory) {
            foreach(glob($this->project_directory."\\".$project."\\".$directory."\\*.php") as $Files) {
                include_once($Files);
            }
        }
    }
    /**
     * Create data tables set in the \Tables folder
     */
    private function MakeTables () {
        // Extracting classes that inherit from \Lanous\db\Structure\Table
        // using this method allows us to obtain a list of tables
        // and ensure that the table structure adheres to the defined rules and principles.
        $Tables = array_values(array_filter(get_declared_classes(),fn ($data) => is_subclass_of($data,\Lanous\db\Structure\Table::class)));
        array_map(function ($class_name) {
            $class_explode = explode("\\",$class_name);
            // Ensure that the namespace matches the project name.
            if ($class_explode[0] != $this->project_name)
                throw new Exceptions\Structure(Exceptions\Structure::ERR_NMESPCE);
            // The name of the data table that is sent to the Query
            $table_name = array_pop($class_explode);
            // The list of database tables is taken with the tables method
            // and if this table has not been created, a request to create it is sent.
            // This is better than when we settle for (CREATE TABLE IF NOT EXISTS).
            if (!in_array(strtolower($table_name),$this->GetTables())){
                $data = new $class_name();
                $MakeQuery = $this->MakeQuery($this->dbsm)->MakeTable($table_name, $data->Result());
                $this->database->exec($MakeQuery);
            }
        },$Tables);
    }

}
