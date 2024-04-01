<?php

namespace Lanous\db\Table;

class Where extends \Lanous\db\Lanous {
    use \Lanous\db\ValuePreparation;
    public $where;
    private $table_name;

    public function __construct($table_name,$column_name,$operator,$value){
        $this->table_name = $table_name;
        $value = $this->ValuePreparation($this->table_name,$column_name,$value,'send');
        $this->where = "$column_name $operator $value";
    }
    public function AND ($column_name,$operator,$value){
        $value = $this->ValuePreparation($this->table_name,$column_name,$value,'send');
        $this->where .= " AND $column_name $operator $value";
        return $this;
    }
    public function OR ($column_name,$operator,$value){
        $value = $this->ValuePreparation($this->table_name,$column_name,$value,'send');
        $this->where .= " OR $column_name $operator $value";
        return $this;
    }
}