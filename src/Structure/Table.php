<?php

namespace Lanous\db\Structure;
const class_name = "\Lanous\db\Structure\Table";

class Table {
    const Column = ["DataType" => "type", "DataSize" => "size", "AutoIncrement" => "auto_increment"];
    const Setting = ["Primary" => "primary","DataSize" => "size","AutoIncrement" => "auto_increment"];
    const DataHandling = ["Evaluation" => "evaluation","Edit" => "edit"];
    const Result = ["Columns" => "columns","DataHandling" => "data_handling","Settings" => "settings"];

    public static $Column;
    public static $DataHandling;
    public static $Setting;
    /**
     * add column
     * @param string $name column name
     * @return Column
     */
    final public function AddColumn(string $name) : Column {
        return new Column($name);
    }

    /**
     * Before injection data into the table, these settings are applied
     */
    final public function Injection($column_name) : DataHandling {
        return new DataHandling($column_name,__FUNCTION__);
    }

    /**
     * After Extract data from the table, these settings are applied
     */
    final public function Extract($column_name) : DataHandling {
        return new DataHandling($column_name,__FUNCTION__);
    }
    
    /**
     * @access private
     */
    final public function Result () {
        $Result = [
            self::Result["Columns"] => self::$Column,
            self::Result["DataHandling"] => self::$DataHandling,
            self::Result["Settings"] => self::$Setting
        ];
        $this->Clear();
        return $Result;
    }
    private function Clear() {
        self::$Column = [];
        self::$DataHandling = [];
        self::$Setting = [];
    }
}

class Column {
    private $name;
    private $data;
    public function __construct($name) {
        $this->name = $name;
    }


    public function DataType($object) {
        if (!class_exists($object))
            throw new \Lanous\db\Exceptions\Structure("the class passed to the first argument does not exists");
        $this->CheckDataTypes ($object);

        Table::$Column[$this->name][Table::Column["DataType"]] = $object;
        return $this;
    }


    public function Size($size) {
        Table::$Column[$this->name][Table::Column["DataSize"]] = $size;
        return $this;
    }


    public function AutoIncrement(bool $value) {
        Table::$Column[$this->name][Table::Column["AutoIncrement"]] = $value;
        return $this;
    }


    public function Primary() {
        if (isset(Table::$Setting[Table::Setting["Primary"]]))
            throw new \Lanous\db\Exceptions\Structure("Multiple primary key defined");
        Table::$Setting[Table::Setting["Primary"]] = $this->name;
        return $this;
    }



    private function CheckDataTypes ($object) {
        $const_list = ['Query'];
        $reflect = new \ReflectionClass($object);
        $invalid_list = array_diff($const_list,array_keys($reflect->getConstants()));
        return count($invalid_list) == 0 ? true : throw new \Lanous\db\Exceptions\Structure("$object class is incomplete, [".$invalid_list[0]."] constant is not defined.");
    }
}


class DataHandling {
    private $name,$method;

    /**
     * @access private
     */
    public function __construct($name,$method) {
        $this->name = $name;
        $this->method = $method;
    }
    /**
     * Evaluate the data
     * You can apply rules here that, if violated, the data will not be entered into the table
     * @param callable $callback A callable with a boolean output allows permission if it evaluates to true.
     * @access public
     */
    public function Evaluation(callable $callback) {
        Table::$DataHandling[$this->method][$this->name][Table::DataHandling["Evaluation"]] = $callback;
        return $this;
    }
    /**
     * Edit data with a callable
     * @param callable $callback A callable
     * @access public
     */
    public function Edit(callable $callback) {
        Table::$DataHandling[$this->method][$this->name][Table::DataHandling["Edit"]] = $callback;
        return $this;
    }
}