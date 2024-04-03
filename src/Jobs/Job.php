<?php

namespace Lanous\db\Jobs;

class Job {
    private $database,$dbsm;
    private $datas,$primarys,$sensitivity;
    public function __construct($database,$dbsm) {
        $this->database = $database;
        $this->dbsm = $dbsm;
    }
    
    /**
     * @param $level [1] = If the data does not change (such as the same data), the data is restored. [2] = Only if data editing encounters a structural error, the data will be restored. [3] = If there is a structural error or no data is edited, the data will be restored.
     */
    public function Sensitivity ($level=3) {
        $this->sensitivity = $level;
    }

    /**
     * Get a data by looking up the value from the primary column
     * Note that you must receive data through this method, otherwise the data recovery operation will be incomplete.
     * @param string $table_name The class of the data table in which you want to look up the primary key value
     * @param string $primary_value The value of the primary key column
     */
    public function Get ($table_name,$primary_value) {
        if(isset($this->datas[$table_name][$primary_value])) {
            throw new \Lanous\db\Exceptions\Jobs(\Lanous\db\Exceptions\Jobs::ERR_DUPLICTE);
        }
        $Table = new \Lanous\db\Table\Table($table_name,$this->dbsm,$this->database);
        if (!isset($this->primarys[$table_name])) {
            $FindPrimary = $Table->Describe();
            foreach($FindPrimary as $Value) {
                if ($Value["Key"] == "PRI")
                    $this->primarys[$table_name] = $Value["Field"]; break;
            }
        }
        $Where = $Table->Where($this->primarys[$table_name],"=",$primary_value);
        $Datas = $Table->Select("*")->Extract($Where);
        $Datas = $Datas->Rows[0] ?? throw new \Lanous\db\Exceptions\Jobs(\Lanous\db\Exceptions\Jobs::ERR_CANTFIND);
        $this->datas[$table_name][$primary_value] = $Datas;
        return (object) ['table_name'=>$table_name,'data'=>$Datas];
    }

    public function Edit (object $row,string $key,$value) : bool {
        $table_name = $row->table_name;
        $row_data = $row->data;
        $primary_value = $this->primarys[$table_name];
        $primary_value = $row_data->{$primary_value}->value;
        $Table = new \Lanous\db\Table\Table($table_name,$this->dbsm,$this->database);
        $where = $Table->Where($this->primarys[$table_name],"=",$primary_value);
        try {
            $result = $Table->Update()->Edit($key,$value)->Push($where);
            if ($result == 0 and ($this->sensitivity == 1 or $this->sensitivity == 3)) {
                $this->RestoreData();
                throw new \Lanous\db\Exceptions\Jobs(\Lanous\db\Exceptions\Jobs::ERR_NOCHANGE);
            }
        } catch (\Throwable $th) {
            $this->RestoreData();
            if(get_class($th) != "Lanous\db\Exceptions\Jobs") {
                throw new \Lanous\db\Exceptions\Jobs(\Lanous\db\Exceptions\Jobs::ERR_EXPERROR);
            } else {
                throw $th;
            }
        }
        return true;
    }

    private function RestoreData () {
        foreach ($this->datas as $table_name => $primaries_keys) {
            foreach ($primaries_keys as $primary_value => $data) {
                $primary_key = $this->primarys[$table_name];
                $Table = new \Lanous\db\Table\Table($table_name,$this->dbsm,$this->database);
                $Where = $Table->Where($primary_key,"=",$primary_value);
                foreach ($data as $column_name=>$value) {
                    try {
                        $Table->Update()->Edit($column_name,$value->value)->Push($Where);
                    } catch (\Throwable $th) {
                        throw new \Lanous\db\Exceptions\Jobs(\Lanous\db\Exceptions\Jobs::ERR_RECOVERY);
                    }
                }
            }
        }
    }
}