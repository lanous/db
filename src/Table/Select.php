<?php

namespace Lanous\db\Table;

class Select extends \Lanous\db\Lanous {
    use \Lanous\db\Traits\ValuePreparation;

    private $table_name,$dbsm,$column_name,$database,$order_by=false,$keywords;
    public function __construct($table_name,$dbsm,$database,$column_name,array $keywords=[]) {
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->column_name = $column_name;
        $this->database = $database;
        $this->keywords = $keywords;
    }

    public function Extract (Where $where=null) {
        $class_explode = explode("\\",$this->table_name);
        $table_name = array_pop($class_explode);
        $query = $this->MakeQuery($this->dbsm)->Extract($table_name,$this->column_name,$this->keywords,$where);
        $stmt = $this->database->query($query);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        array_walk($data,function ($values,$data_key) use (&$data) {
            array_walk($values,function ($column_value,$column_name) use (&$data,$data_key) {
                $data[$data_key][$column_name] = $this->ValuePreparation($this->table_name,$column_name,$column_value);
            });
            $data[$data_key] = (object) $data[$data_key];
        });
        return new ReturnStrc($data);
    }

}


