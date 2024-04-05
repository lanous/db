<?php

namespace Lanous\db\Settings;

class Table extends \Lanous\db\Lanous {
    private $database;
    private $table_class;
    private $dbsm;
    public function __construct($database,$dbsm,$table_class) {
        $this->table_class = $table_class;
        $this->dbsm = $dbsm;
        $this->database = $database;
    }
    public function FOREIGN_KEY($column_name,$reference_table_class,$reference_column) {
        $data = [];
        $data['operation'] = "foreign_key";
        $data['column_name'] = $column_name;
        $reference_table_class_ref = new \ReflectionClass($reference_table_class);
        $reference_table_name = $reference_table_class_ref->getShortName();
        $data['reference_table'] = $reference_table_name;
        $data['reference_column'] = $reference_column;
        $table_ref = new \ReflectionClass($this->table_class);
        $table_name = $table_ref->getShortName();
        $query = $this->MakeQuery($this->dbsm)->Alter($table_name, $data);
        return $this->database->exec($query);
    }
}