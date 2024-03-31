<?php

namespace Lanous\db\Table;

class Select extends \Lanous\db\Lanous {
    private $where,$query;
    private $table_name,$dbsm,$column_name,$database;
    public function __construct($table_name,$dbsm,$database,$column_name) {
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->column_name = $column_name;
        $this->database = $database;
        $class_explode = explode("\\",$this->table_name);
        $table_name = array_pop($class_explode);
        $this->query = "SELECT $column_name FROM `$table_name`";
    }
    public function Where($key,$value) {
        return new Where($this->database,$this->table_name,$this->query,$key,$value);
    }

    public function Result () {
        $Result = new Result($this->database,$this->table_name);
        $Result->Run($this->query);
        return $Result;
    }
}


class Where {
    public $End;
    private $where;
    private $query;
    private $database,$table_name;

    public function __construct($database,$table_name,$query,$key,$value) {
        $this->database = $database;
        $this->table_name = $table_name;
        $this->query = $query;
        $this->where = $key." = '".$value."'";
    }
    public function And($key,$value) {
        $this->where .= " AND ".$key." = '".$value."'";
        return $this;
    }
    public function Or($key,$value) {
        $this->where .= " OR ".$key." = '".$value."'";
        return $this;
    }
    public function Result () {
        $Result = new Result($this->database,$this->table_name);
        $Result->Run($this->query." WHERE ".$this->where);
        return $Result;
    }
}


class Result {
    use \Lanous\db\ValuePreparation;
    public $Rows;
    private $database,$table_name;
    public function __construct ($database,$table_name){
        $this->database = $database;
        $this->table_name = $table_name;
    }
    public function Run($query) {
        $stmt = $this->database->query($query);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        array_walk($data,function ($values,$data_key) use (&$data) {
            array_walk($values,function ($column_value,$column_name) use (&$data,$data_key) {
                $data[$data_key][$column_name] = $this->ValuePreparation($this->table_name,$column_name,$column_value);
            });
            $data[$data_key] = (object) $data[$data_key];
        });
        $this->ReturnMaker ($data);
    }
    public function ReturnMaker ($data) {
        $this->Rows = $data;
    }
}