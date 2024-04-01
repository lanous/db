<?php

namespace Lanous\db\Table;

class Where extends \Lanous\db\Lanous {
    use \Lanous\db\Traits\ValuePreparation;
    public $where=[];
    private $table_name;

    public function __construct($table_name,$column_name,$operator,$value){
        $this->table_name = $table_name;
        $value = $this->ValuePreparation($this->table_name,$column_name,$value,'send');
        $this->where[] = [
            "condition"=>"main",
            "column_name"=>$column_name,
            "operator"=>$operator,
            "value"=>$value
        ];
    }
    public function AND ($column_name,$operator,$value){
        $value = $this->ValuePreparation($this->table_name,$column_name,$value,'send');
        $this->where[] = [
            "condition"=>"and",
            "column_name"=>$column_name,
            "operator"=>$operator,
            "value"=>$value
        ];
        return $this;
    }
    public function OR ($column_name,$operator,$value){
        $value = $this->ValuePreparation($this->table_name,$column_name,$value,'send');
        $this->where[] = [
            "condition"=>"or",
            "column_name"=>$column_name,
            "operator"=>$operator,
            "value"=>$value
        ];
        return $this;
    }
}