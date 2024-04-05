<?php

namespace Lanous\db\Table;

class Update extends \Lanous\db\Lanous {
    use \Lanous\db\Traits\ValuePreparation;

    private $where,$new_values=[];
    private $table_name,$dbsm,$database;
    public function __construct($table_name,$dbsm,$database){
        $this->table_name = $table_name;
        $this->dbsm = $dbsm;
        $this->database = $database;
    }
    /**
     * Edit a row
     * @param string $column_name The name of the column you want to change the value of
     * @param mixed $to New value
     */
    public function Edit (string $column_name,mixed $to) : Update {
        $to = $this->ValuePreparation($this->table_name,$column_name,$to,'send');
        $this->new_values[$column_name] = $to;
        return $this;
    }
    /**
     * submit to edit a row
     * @param $where If you have specific rows in mind, pass the where object here.
     */
    public function Push (Where $where=null,$primary_value = null) {
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

        $class_ref = new \ReflectionClass($this->table_name);
        $table_name = $class_ref->getShortName();
        $query = $this->MakeQuery($this->dbsm)->Update($table_name, $this->new_values, $where);
        return $this->database->exec($query);
    }
}