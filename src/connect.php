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
                throw new Exceptions\NonSupport("dbsm entered in config (".$this->dbsm."), is not supported.");
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
            throw new exceptions\init($e->getMessage());
        }
    }

    /**
     * Open the database settings
     */
    public function Settings () {
        return new Settings\Settings($this->database);
    }

    public function OpenTable (string $table_name) {
        return new Table\Table($table_name,$this->dbsm,$this->database);
    }
    public function Load (string $plugin_class,$data=null) {
        return new $plugin_class($this->database,$this->dbsm,$data);
    }
    public function GetTables () {
        $stmt = $this->database->query("SHOW TABLES");
        return array_keys($stmt->fetchAll(\PDO::FETCH_UNIQUE));
    }

    private function CheckConfig(object $config) {
        $const_list = ['hostname','username','password','database','dbsm','project_name'];
        $config = get_class($config);
        $reflect = new \ReflectionClass($config);
        $invalid_list = array_diff($const_list,array_keys($reflect->getConstants()));
        return count($invalid_list) == 0 ? true : throw new exceptions\config("Config class is incomplete, [".$invalid_list[0]."] constant is not defined.");
    }
    private function MakeProject(string $project_name) {
        $this->MakeDirectory($project_name);
        # ------- Tables ------- #
        $this->MakeDirectory($project_name."/Tables");
            $this->Copy(__DIR__."/Examples/Table.php",$project_name."/Tables/Users.php");
        # ----- Data Types ----- #
        $this->MakeDirectory($project_name."/DataTypes");
            $this->Copy(__DIR__."/Examples/DataTypes/Varchar.php",$project_name."/DataTypes/Varchar.php");
            $this->Copy(__DIR__."/Examples/DataTypes/Integer.php",$project_name."/DataTypes/Integer.php");
            $this->Copy(__DIR__."/Examples/DataTypes/ArrayData.php",$project_name."/DataTypes/ArrayData.php");
        $this->MakeDirectory($project_name."/Plugins");
    }
    private function AutoLoad($project) {
        $directores = scandir($this->project_directory."\\".$project);
        unset($directores[0]);
        unset($directores[1]);
        foreach ($directores as $directory) {
            foreach(glob($this->project_directory."\\".$project."\\".$directory."\\*.php") as $Files) {
                include_once($Files);
            }
        }
    }
    private function MakeTables () {
        $Tables = array_values(array_filter(get_declared_classes(),fn ($data) => is_subclass_of($data,\Lanous\db\Structure\class_name)));
        array_map(function ($class_name) {
            $class_explode = explode("\\",$class_name);
            if ($class_explode[0] != $this->project_name)
                throw new Exceptions\Structure("the namespace set does not match the project name. class: [".$class_name."]");
            $table_name = array_pop($class_explode);
            if (!in_array(strtolower($table_name),$this->GetTables())){
                $data = new $class_name();
                $MakeQuery = $this->MakeQuery($this->dbsm)->MakeTable($table_name, $data->Result());
                $this->database->exec($MakeQuery);
            }
        },$Tables);
    }
    private function MakeDirectory($directory) {
        return !file_exists($this->project_directory."\\".$directory) ? mkdir($this->project_directory."\\".$directory) : true;
    }
    private function Copy($from,$to) {
        return !file_exists($this->project_directory."\\".$to) ? copy($from,$this->project_directory."\\".$to) : true;
    }
}
