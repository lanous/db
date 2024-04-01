<?php

namespace Lanous\db\Table;

class Delete extends \Lanous\db\Lanous {
    private $table_name,$dbsm,$database,$where;
    public $result;
    public function __construct($table_name,$dbsm,$database,Where $where=null) {
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->database = $database;
        $class_explode = explode("\\",$this->table_name);
        $table_name = array_pop($class_explode);
        $query = $this->MakeQuery($this->dbsm)->Delete($table_name,$where);
        $this->result = $this->database->exec($query);
    }
}