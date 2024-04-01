<?php

namespace Lanous\db\Table;

class Select extends \Lanous\db\Lanous {
    private $where,$query;
    private $table_name,$dbsm,$column_name,$database,$order_by=false;
    public function __construct($table_name,$dbsm,$database,$column_name,array $keywords=[]) {
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->column_name = $column_name;
        $this->database = $database;
        $class_explode = explode("\\",$this->table_name);
        $table_name = array_pop($class_explode);
        $distinct = $keywords["distinct"] == true ? "DISTINCT" : "";
        $this->query = "SELECT ".$distinct." $column_name FROM `$table_name`";
        if ($keywords["order_by"] != new \stdClass()) {
            $this->order_by = " ORDER BY ";
            array_walk($keywords["order_by"],function ($direction,$column_name) {
                $this->order_by .= $column_name." ".$direction.",";
            });
            $this->order_by = rtrim($this->order_by,",");
        }
    }
    public function Where (object $obj) {
        $this->query .= " WHERE ".$obj->where;
        return $this;
    }

    public function Result () {
        $Result = new Result($this->database,$this->table_name);
        $order_by = $this->order_by != false ? $this->order_by : "";
        $Result->Run($this->query.$order_by);
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