<?php

namespace Lanous\db\Table;

class RowReturn {
    use \Lanous\db\Traits\ValuePreparation;
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
    private $config;
    private $database;
    private $table_name;
    private $CleanRows;
    public function __construct ($rows,object $config,$database,$table_name){
        $this->Rows = $rows;
        $this->CleanRows = $rows;
        $this->table_name = $table_name;
        $this->config = $config;
        $this->database = $database;

        $Rows = $this->Rows;
        array_walk_recursive($Rows,function (&$value,$column_name){
            $value = $this->SetasValue($column_name,$value);
        });
        $this->Rows = $Rows;
    }


    /**
     * Display the oldest row finded to the data table
     */
    public function FirstRow (int $mode=self::ArrayType) {
        $data = $this->Rows[0];
        return $this->MODES($data,$mode);
    }
    /**
     * Display the newest row finded to the data table
     */
    public function LastRow (int $mode=self::ArrayType) {
        $Rows = $this->Rows;
        $data = array_pop($Rows);
        return $this->MODES($data,$mode);
    }
    /**
     * It internally modifies the data of columns (without updating the database) and sends it back to RowReturn.
     * @param callable $callback The callback takes two parameters: the first parameter is the column name, and the second parameter is its value. If this callback has an output, the new value is set.
     */
    public function Callback (callable $callback) {
        $data = [];
        $Rows = $this->CleanRows;
        array_walk_recursive($Rows,function (&$value,$column_name,$callback) {
            $value = $this->ValuePreparation($this->table_name,$column_name,$value);
            $callback_result = $callback($column_name,$value);
            $value = ($callback_result != NULL) ? $callback_result : $value;
            $value = $this->ValuePreparation($this->table_name,$column_name,$value,self::Preparationinjection);
        },$callback);
        $data = $Rows;
        return new $this($data,$this->config,$this->database,$this->table_name);
    }

    public function Child ($table_class) {
        $ChildTable = new Table($table_class,$this->config,$this->database);
        // Check the reference
        $reference = $ChildTable->Describe()["reference"] ?? throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_RFRNCNF);
        // save column reference (child)
        $column_reference = $reference['column_reference'];
        // save column
        $column_name = $reference['column_name'];

        $table_reference = $reference['table_reference'];
        $table_reference = $this->config::project_name."\\Tables\\".$table_reference;

        $child_data = [];
        foreach($this->Rows as $RowID=>$Data) {
            $child_primary_value = $Data[$column_reference]->value;
            $Where = $ChildTable->Where($column_name,"=",$child_primary_value);
            $Data = $ChildTable->Select("*")->Extract($Where)->LastRow(self::ArrayType);
            array_walk($Data,function (&$value,$column_name) use ($table_class) {
                $value = $this->ValuePreparation($table_class,$column_name,$value,self::Preparationinjection);
            });
            $child_data[$child_primary_value] = $Data;
        }
        return new $this($child_data,$this->config,$this->database,$table_class);

        /*
        $LastRowResult = $this->LastRow (self::ArrayType);
        $value_reference_column = $LastRowResult[$column_reference];
        $Where = $Table->Where($column_name,"=",$value_reference_column);
        return $Table->Select("*")->Extract($Where);
        */
    }
    public function Parent() {

        // find parent table
        $CurrentTable = new Table($this->table_name,$this->config,$this->database);
        $reference = $CurrentTable->Describe()["reference"] ?? throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_RFRNCNF);
        $column_name = $reference['column_name'];
        $column_reference = $reference['column_reference'];
        $table_reference = $reference['table_reference'];
        $table_reference = $this->config::project_name."\\Tables\\".$table_reference;
        $ParentTable = new Table($table_reference,$this->config,$this->database);
        $parent_data = [];
        foreach($this->Rows as $RowID=>$Data) {
            $parent_primary_value = $Data[$column_name]->value;
            $Where = $ParentTable->Where($column_reference,"=",$parent_primary_value);
            $Data = $ParentTable->Select("*")->Extract($Where)->LastRow(self::ArrayType);
            array_walk($Data,function (&$value,$column_name) use ($column_reference,$table_reference) {
                $value = $this->ValuePreparation($table_reference,$column_name,$value,self::Preparationinjection);
            });
            $parent_data[$parent_primary_value] = $Data;
        }
        return new $this($parent_data,$this->config,$this->database,$table_reference);
    }

    public function MachineLerning () {
        return new \Lanous\db\MachineLearning\MachineLearning($this->Rows);
    }

    private function MODES ($data,$mode) {
        if ($mode == self::ObjectType){           
            return (object) $data;
        } elseif ($mode == self::ArrayType) {
            $return = [];
            array_walk($data,function ($data,$column_name) use (&$return) {
                $return[$column_name] = $data->value ?? null;
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
                $get_methods[$column_name] = $data->methods ?? null;
            });
            return (object) $get_methods;
        } elseif ($mode == self::Values) {
            $return = [];
            array_walk($data,function ($data,$column_name) use (&$return) {
                $return[] = $data->value ?? null;
            });
            return $return;
        }
    }
    private function SetasValue($column,$value) {
        $result = [];
        $table_class = $this->table_name;
        $table = new $table_class();
        $table = $table->Result();
        $ColumnData = $table[\Lanous\db\Structure\Table::Result['Columns']][$column] ?? false;
        if($ColumnData == false)
            return false;
        $DataType = new $ColumnData["type"]($value);

        $FindProperties = new \ReflectionClass($DataType);
        $FindProperties = array_column($FindProperties->getProperties(\ReflectionProperty::IS_PUBLIC),"name");        
        array_map(function ($property) use (&$result,$DataType) {
            $result[$property] = $DataType->{$property};
        },$FindProperties);

        $result["methods"] = $DataType;
        $result["value"] = $this->ValuePreparation($this->table_name,$column,$value,self::PreparationExtract);
        $value = (object) $result;
        return $value;
    }

}