<?php

namespace Lanous\db\Table;

class Table extends \Lanous\db\Lanous {
    private $dbsm,$table_name,$database;
    public function __construct($table_name,$dbsm,$database) {
        if(!is_subclass_of($table_name,\Lanous\db\Structure\Table::class))
            throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_TABLEND);
        $this->dbsm = $dbsm;
        $this->table_name = $table_name;
        $this->database = $database;
    }
    /**
     * To select one or more rows, we use this method.
     * @param string $column If you want to extract only a specific column, enter the column name here. If you want to specify multiple specific columns, separate them using commas (,). And if you want to display all columns, use an asterisk (*) 
     * @param bool $distinct If it is set to true, it will return distinct data.
     * @param object $order_by Data ordering settings - can only be set via the order method.
     * @param int $limit Limit the number of output rows given
     * @param int $offset Offset (can only be used if limit is set)
     */
    public function Select(string $column="*",bool $distinct=false,object $order_by=new \stdClass(),int $limit=0,int $offset=0) {
        return new Select($this->table_name,$this->dbsm,$this->database,$column,[
            "distinct"=>$distinct,
            "order_by"=>$order_by,
            "limit"=>$limit,
            "offset"=>$offset
        ]);
    }
    /**
     * Updating (editing) the data of a table.
     * @return Update The output of a class includes Edit and Push methods.
     */
    public function Update() : Update {
        return new Update($this->table_name,$this->dbsm,$this->database);
    }
    /**
     * This method is used to add a new row to the table.
     */
    public function Insert() : Insert {
        return new Insert($this->table_name,$this->dbsm,$this->database);
    }
    /**
     * Delete a row from the table
     * @param $where must be passed with the Where method <b>Note:</b> If the where parameter is not passed, all the data in the table will be deleted.
     */
    public function Delete(Where $where=null) : bool {
        $Delete = new Delete($this->table_name,$this->dbsm,$this->database,$where);
        return $Delete->result;
    }
    /**
     * Output of table information including columns and information of each column
     */
    public function Describe () : array {
        $class_ref = new \ReflectionClass($this->table_name);
        $table_name = $class_ref->getShortName();
        $stmt = $this->database->query("DESCRIBE ".$table_name);
        $stmt = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt2 = $this->database->query("SHOW CREATE TABLE `$table_name`");
        $stmt2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC)[0]["Create Table"];
        preg_match('#\(`(.*?)`\)[[:space:]]REFERENCES[[:space:]]`(.*?)`[[:space:]]\(`(.*?)`\)#',$stmt2,$stmt2);
        if(isset($stmt2[1]) and isset($stmt2[2]) and isset($stmt2[3]))
            $stmt["reference"] = ["table_reference"=>$stmt2[2],"column_reference"=>$stmt2[3],"column_name"=>$stmt2[1]];
        return $stmt;
    }

    /**
     * Quick search in data table
     * @param $column_value The data value you are looking for.
     * @param $column_name The column that has this data value, if you do not set a value, it will look for the primary key.
     * @return mixed form of an array consisting of keys that correspond to the column name and their value is the same as the value of the columns, and if no data is found, the output will be false.
     */
    public function QuickFind($column_value,$column_name=null) : bool | array {
        if ($column_name == null) {
            $find_primary = new $this->table_name();
            $find_primary = $find_primary->Result();
            $find_primary = $find_primary[\Lanous\db\Structure\Table::Result["Settings"]];
            $find_primary = $find_primary[\Lanous\db\Structure\Table::Setting["Primary"]] ?? false;
            if ($find_primary == false)
                throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_PKNOTST);
            $where = $this->Where ($find_primary,"=",$column_value);
        } else {
            $where = $this->Where ($column_name,"=",$column_value);
        }
       $data = $this->Select("*")->Extract($where);
       return $data == false ? false : $data->LastRow(RowReturn::ArrayType);
    }


    /**
     * It does not have any special abilities on its own.
     * It is used to create an object from order.
     * You can sort rows using this method.
     * @param array $columns An array of columns whose value can be DESC or ASC
     * @param string $direction If you haven’t set the values of the previous parameter keys, you need to determine through this parameter whether the order should be via DESC or ASC.
     */
    public static function Order(array $columns,string $direction=null) : object {
        $return = [];
        if ($direction != null) {
            array_walk($columns,function ($column_name,$key) use (&$return,$direction) {
                $return[$column_name] = $direction;
            });
        } else {
            array_walk($columns,function ($direction,$column_name) use (&$return) {
                if ($direction != \Lanous\db\Lanous::ORDER_DESC and $direction != \Lanous\db\Lanous::ORDER_ASC)
                throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_ORDERDC);
                $return[$column_name] = $direction;
            });
        }
        return (object) $return;
    }

    /**
     * It does not have any special abilities on its own.
     * It is used to search one or more rows of the data table.
     * @param string $column_name Which column is the value you want to search for?
     * @param string $operator Contains: =, <, >, <=, >=, <>,
     * @param string $value $column_name value
     */
    public function Where (string $column_name,string $operator,string $value) : Where {
        return new Where($this->table_name,$column_name,$operator,$value);
    }

}
