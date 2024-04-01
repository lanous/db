<?php

namespace Lanous\db\Table;

class Table extends \Lanous\db\Lanous {
    private $dbsm,$table_name,$database;
    public function __construct($table_name,$dbsm,$database) {
        if(!is_subclass_of($table_name,\Lanous\db\Structure\class_name))
            throw new \Lanous\db\Exceptions\Structure(" ---- ");
        $this->dbsm = $dbsm;
        $this->table_name = $table_name;
        $this->database = $database;
    }
    public function Select($column="*",$distinct=false,object $order_by=new \stdClass()) {
        return new Select($this->table_name,$this->dbsm,$this->database,$column,["distinct"=>$distinct,"order_by"=>$order_by]);
    }
    public function Update() {
        return new Update($this->table_name,$this->dbsm,$this->database);
    }
    public function Insert() {
        return new Insert($this->table_name,$this->dbsm,$this->database);
    }
    public function Describe () {
        $class_explode = explode("\\",$this->table_name);
        $table_name = array_pop($class_explode);
        $stmt = $this->database->query("DESCRIBE ".$table_name);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public static function Order(array $columns,string $direction=null) : object {
        $return = [];
        if ($direction != null) {
            array_walk($columns,function ($column_name,$key) use (&$return,$direction) {
                $return[$column_name] = $direction;
            });
        } else {
            array_walk($columns,function ($direction,$column_name) use (&$return) {
                if ($direction != \Lanous\db\Lanous::ORDER_DESC and $direction != \Lanous\db\Lanous::ORDER_ASC)
                throw new \Lanous\db\Exceptions\Structure("direction must be (\Lanous\db\Lanous::ORDER_DESC) or (\Lanous\db\Lanous::ORDER_ASC)");
                $return[$column_name] = $direction;
            });
        }
        return (object) $return;
    }
    public function Where ($column_name,$operator,$value) {
        return new Where($this->table_name,$column_name,$operator,$value);
    }

}
