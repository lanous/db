<?php

namespace Lanous\db\Table;

class Insert extends \Lanous\db\Lanous {
    use \Lanous\db\Traits\ValuePreparation;
    
    private $data;
    private $table_data;
    private $table_name;
    private $config;
    private $columns_list;
    private $database;
    
    public function __construct($table_name,object $config,$database) {
        $this->table_name = $table_name;
        $this->database = $database;
        $this->config = $config;
        $data = new $table_name();
        $this->table_data = $data->Result();
        $columns = $this->table_data[\Lanous\db\Structure\Table::Result["Columns"]];
        $this->columns_list = array_keys($columns);
    }
    /**
     * Setting the column name and its value
     */
    public function Set (string $column_name,mixed $value) : Insert {
        $value = $this->ValuePreparation($this->table_name,$column_name,$value,'send');
        $this->data[$column_name] = $value;
        return $this;
    }

    /**
     * submit to create a new row
     */
    public function Push() : bool {
        $class_ref = new \ReflectionClass($this->table_name);
        $table_name = $class_ref->getShortName();
        $query = $this->MakeQuery($this->config)->Insert($table_name, $this->data);
        return $this->database->exec($query);
    }

}