<?php

namespace Lanous\db\Table;

class Insert extends \Lanous\db\Lanous {
    use \Lanous\db\Traits\ValuePreparation;
    
    private $data,$table_data,$table_name,$dbsm,$column_name,$columns_list,$database;
    
    public function __construct($table_name,$dbsm,$database) {
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->database = $database;
        $data = new $table_name();
        $this->table_data = $data->Result();
        $columns = $this->table_data[\Lanous\db\Structure\Table::Result["Columns"]];
        $this->columns_list = array_keys($columns);
    }
    /**
     * The name of the data table column to which you want to assign a value.
     */
    public function Key(string $column_name) : Insert {
        if ($this->column_name != null)
            throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_INSTPTN);
        if (!in_array($column_name,$this->columns_list))
            throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_CLUMNND);
        $this->column_name = $column_name;
        $this->data[$this->column_name] = "";
        return $this;
    }
    /**
     * The value you want to assign to this column
     */
    public function Value(mixed $value) : Insert {
        if ($this->column_name == null)
            throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_INSTPTN);
        $this->data[$this->column_name] = $this->ValuePreparation($this->table_name,$this->column_name,$value,'send');
        $this->column_name = null;
        return $this;
    }
    /**
     * submit to create a new row
     */
    public function Push() : bool {
        $class_explode = explode("\\",$this->table_name);
        $table_name = array_pop($class_explode);
        $query = $this->MakeQuery($this->dbsm)->Insert($table_name, $this->data);
        return $this->database->exec($query);
    }

}