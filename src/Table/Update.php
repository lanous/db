<?php

namespace Lanous\db\Table;

class Update extends \Lanous\db\Lanous {
    use \Lanous\db\ValuePreparation;

    private $query,$where;
    private $table_name,$dbsm,$database;
    public function __construct($table_name,$dbsm,$database){
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->database = $database;
        $class_explode = explode("\\",$this->table_name);
        $table_name = array_pop($class_explode);
        $this->query = "UPDATE `".$table_name."` SET ";
    }
    public function Where (object $obj) {
        $this->where = " WHERE ".$obj->where;
        return $this;
    }
    public function Edit (string $column_name,$to) {
        $to = $this->ValuePreparation($this->table_name,$column_name,$to,'send');
        $this->query .= "$column_name = $to,";
        return $this;
    }
    public function Set () {
        $this->query = rtrim($this->query,",");
        if (isset($this->where))
            $this->query .= $this->where;
        return $this->database->exec($this->query);
    }
}