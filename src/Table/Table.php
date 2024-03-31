<?php

namespace Lanous\db\Table;

class Table extends \Lanous\db\Lanous {
    private $dbsm,$table_name;
    public function __construct($table_name,$dbsm) {
        if(!is_subclass_of($table_name,\Lanous\db\Structure\class_name))
            throw new \Lanous\db\Exceptions\Structure(" ---- ");
        $this->dbsm = $dbsm;
        $this->table_name = $table_name;
    }
    public function Select($column_name="*") {
        return new Select($this->table_name,$this->dbsm,$column_name);
    }
    public function Insert() {
        return new Insert($this->table_name,$this->dbsm);
    }
}
