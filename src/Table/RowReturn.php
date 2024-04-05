<?php

namespace Lanous\db\Table;

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
    public function __construct ($rows){
        $this->Rows = $rows;
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