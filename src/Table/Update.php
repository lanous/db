<?php

namespace Lanous\db\Table;

class Update extends \Lanous\db\Lanous {
    use \Lanous\db\Traits\ValuePreparation;

    private $where,$new_values=[];
    private $table_name,$dbsm,$database;
    public function __construct($table_name,$dbsm,$database){
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->database = $database;
    }
    public function Edit (string $column_name,$to) {
        $to = $this->ValuePreparation($this->table_name,$column_name,$to,'send');
        $this->new_values[$column_name] = $to;
        return $this;
    }
    public function Push (Where $where=null) {
        $class_explode = explode("\\",$this->table_name);
        $table_name = array_pop($class_explode);
        $query = $this->MakeQuery($this->dbsm)->Update($table_name, $this->new_values, $where);
        echo $query.PHP_EOL;
        return $this->database->exec($query);
    }
}