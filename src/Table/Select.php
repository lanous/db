<?php

namespace Lanous\db\Table;

class Select extends \Lanous\db\Lanous {
    use \Lanous\db\Traits\ValuePreparation;

    private $table_name,$dbsm,$column_name,$database,$order_by=false,$keywords;
    public function __construct($table_name,$dbsm,$database,$column_name,array $keywords=[]) {
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->column_name = $column_name;
        $this->database = $database;
        $this->keywords = $keywords;
    }

    public function Extract (Where $where=null,$primary_value = null) : false | RowReturn {

        // find user
        if ($where != null and $primary_value != null)
            throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_IFEINPR);
        if ($primary_value != null) {
            $where = new \Lanous\db\Table\Table($this->table_name,$this->dbsm,$this->database);
            $find_primary = new $this->table_name();
            $find_primary = $find_primary->Result();
            $find_primary = $find_primary[\Lanous\db\Structure\Table::Result["Settings"]];
            $find_primary = $find_primary[\Lanous\db\Structure\Table::Setting["Primary"]] ?? false;
            if ($find_primary == false)
                throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_PKNOTST);
            $where = $where->Where ($find_primary,"=",$primary_value);
        }

        // Make Query and get result
        $class_ref = new \ReflectionClass($this->table_name);
        $table_name = $class_ref->getShortName();
        $query = $this->MakeQuery($this->dbsm)->Extract($table_name,$this->column_name,$this->keywords,$where);
        $stmt = $this->database->query($query);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (count($data) == 0)
            return false;
        return new RowReturn($data,$this->dbsm,$this->database,$this->table_name);
    }

}


