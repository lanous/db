<?php

namespace Lanous\db\Table;

class Delete extends \Lanous\db\Lanous {
    private $table_name,$database,$where;
    public $result;
    public function __construct($table_name,$config,$database,Where $where=null) {
        $this->table_name = $table_name;
        $this->database = $database;
        $class_ref = new \ReflectionClass($this->table_name);
        $table_name = $class_ref->getShortName();
        $query = $this->MakeQuery($config)->Delete($table_name,$where);
        $this->result = $this->database->exec($query);
    }
}