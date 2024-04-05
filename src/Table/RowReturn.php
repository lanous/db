<?php

namespace Lanous\db\Table;


/*
    ["reference"]=>
    array(3) {
        ["table_reference"]=>
        string(5) "users"
        ["column_reference"]=>
        string(2) "id"
        ["column_name"]=>
        string(7) "user_id"
    }
*/

class RowReturn {
    /**
     * Display only values, column names are indexed from 0.
     * e.g: <code>$row[0] == "foo";</code>
     */
    const Values = 1;
    /**
     * Display only data type methods, keys are used as column names
     * e.g: <code>$row->first_name->test($a,$b);</code>
     */
    const Methods = 2;
    /**
     * Display only column names.
     * e.g: <code>$row[0] == "first_name";</code>
     */
    const Keys = 3;
    /**
     * Output as an array
     * where column names serve as array keys and array values correspond to column values.
     * e.g: <code>$row["first_name"] == "foo";</code>
     */
    const ArrayType = 4;
    /**
     *  Output as an object, containing both column names and their associated data type methods.
     *  e.g(1): <code>$row->first_name->value;</code>
     *  e.g(2): <code>$row->first_name->methods->test($a,$b);</code>
     */
    const ObjectType = 5;
    public $Rows;
    private $dbsm,$database;
    public function __construct ($rows,$dbsm,$database){
        $this->Rows = $rows;
        $this->dbsm = $dbsm;
        $this->database = $database;
    }


    /**
     * Display the oldest row added to the data table
     */
    public function FirstRow (int $mode=self::ObjectType) {
        $data = $this->Rows[0];
        return $this->MODES($data,$mode);
    }
    /**
     * Display the newest row added to the data table
     */
    public function LastRow (int $mode=self::ArrayType) {
        $Rows = $this->Rows;
        $data = array_pop($Rows);
        return $this->MODES($data,$mode);
    }
    public function Child ($table_class,$mode=self::ArrayType) {
        $Table = new Table($table_class,$this->dbsm,$this->database);
        $reference = $Table->Describe()["reference"] ?? throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_RFRNCNF);
        $column_reference = $reference['column_reference'];
        $column_name = $reference['column_name'];
        $LastRowResult = $this->LastRow (self::ArrayType);
        $value_reference_column = $LastRowResult[$column_reference];
        $Where = $Table->Where($column_name,"=",$value_reference_column);
        return $Table->Select("*")->Extract($Where)->LastRow($mode);
    }

    private function MODES ($data,$mode) {
        if ($mode == self::ObjectType){           
            return $data;
        } elseif ($mode == self::ArrayType) {
            $return = [];
            array_walk($data,function ($data,$column_name) use (&$return) {
                $return[$column_name] = $data->value;
            });
            return $return;
        } elseif ($mode == self::Keys) {
            $return = [];
            array_walk($data,function ($data,$column_name) use (&$return) {
                $return[] = $column_name;
            });
            return $return;
        } elseif ($mode == self::Methods) {
            $get_methods = [];
            array_walk($data,function ($data,$column_name) use (&$get_methods) {
                $get_methods[$column_name] = $data->methods;
            });
            return (object) $get_methods;
        } elseif ($mode == self::Values) {
            $return = [];
            array_walk($data,function ($data,$column_name) use (&$return) {
                $return[] = $data->value;
            });
            return $return;
        }
    }

}