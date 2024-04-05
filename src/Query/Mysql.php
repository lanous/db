<?php

namespace Lanous\db\Query;

/*  ---- where
    ["condition"]=> "main|and|or"
    ["column_name"]=> "x"
    ["operator"]=> "=|!=|etc..."
    ["value"]=> "'Y'"
*/

class Mysql implements Face {
    private $query;
    public function MakeTable(string $table_name, array $data): string {
        $this->query = "CREATE TABLE IF NOT EXISTS `".$table_name."` (";
        $columns = $data[\Lanous\db\Structure\Table::Result["Columns"]];
        $settings = $data[\Lanous\db\Structure\Table::Result["Settings"]];
        array_walk($columns,function (array $column_data,string $column_name) {
            $DataType = $column_data[\Lanous\db\Structure\Table::Column["DataType"]];
            $QueryType = $DataType::Query;
            $DataSize = $column_data[\Lanous\db\Structure\Table::Column["DataSize"]] ?? false;
            $AutoIncrement = $column_data[\Lanous\db\Structure\Table::Column["AutoIncrement"]] ?? false;
            $NotNull = $column_data[\Lanous\db\Structure\Table::Column["NotNull"]] ?? false;
            $UNIQUE = $column_data[\Lanous\db\Structure\Table::Column["UNIQUE"]] ?? false;
            $Default = $column_data[\Lanous\db\Structure\Table::Column["Default"]] ?? false;

            $this->query .= "`$column_name` $QueryType";
            $this->query .= $DataSize != false ? "($DataSize)" : " ";
            $this->query .= ($AutoIncrement == false) ? "" : " NOT NULL AUTO_INCREMENT ";
            $this->query .= ($AutoIncrement == false && $NotNull == true) ? " NOT NULL " : "";
            $this->query .= ($UNIQUE == true) ? " NOT NULL " : "";
            if ($Default != false) {
                $is_function = $Default[-1] == ")" and $Default[-2] == "("; //example: CURRENT_DATE()
                $Default = $is_function == true ? $Default : "'".$Default."'";
                $this->query .= " DEFAULT ".$Default." ";
            }
            $this->query .= ",";
        });
        $this->query = rtrim($this->query," ,");
        $primary = $settings[\Lanous\db\Structure\Table::Setting["Primary"]] ?? false;
        if($primary)
            $this->query .= ", PRIMARY KEY (`".$primary."`)";
        $this->query .= ")";
        return $this->query;
    }
    public function Insert(string $table_name, array $data): string {
        $column_name = array_keys($data);
        $column_value = array_values($data);
        return "INSERT INTO `".$table_name."`(".implode(",",$column_name).") VALUES (".implode(",",$column_value).")";
    }
    public function Update(string $table_name, array $new_values, object $where=null) : string {
        $query = "UPDATE `".$table_name."` SET ";
        array_walk($new_values,function ($column_name,$new_value) use (&$query) {
            $query .= "$new_value = $column_name,";
        });
        $query = rtrim($query,",");
        if(isset($where)) {
            array_map(function ($data) use (&$query) {
                if ($data["condition"] == "main")
                    $query .= " WHERE ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
                if ($data["condition"] == "and")
                    $query .= " AND ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
                if ($data["condition"] == "or")
                    $query .= " OR ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
            },$where->where);
            $query = rtrim($query,",");
        }
        return $query;
    }
    public function Extract(string $table_name,string $columns,array $keywords,\Lanous\db\Table\Where $where=null) : string {
        $distinct = $keywords["distinct"] == true ? "DISTINCT" : "";
        $query = "SELECT ".$distinct." $columns FROM `$table_name`";
        if(isset($where)) {
            array_map(function ($data) use (&$query) {
                if ($data["condition"] == "main")
                    $query .= " WHERE ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
                if ($data["condition"] == "and")
                    $query .= " AND ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
                if ($data["condition"] == "or")
                    $query .= " OR ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
            },$where->where);
            $query = rtrim($query,",");
        }
        if ($keywords["order_by"] != new \stdClass()) {
            $query .= " ORDER BY ";
            array_walk($keywords["order_by"],function ($direction,$column_name) use (&$query) {
                $query .= $column_name." ".$direction.",";
            });
            $query = rtrim($query,",");
        }
        if($keywords['limit'] != 0) {
            $query .= " LIMIT ".$keywords['limit'];
            if($keywords['offset'] != 0) {
                $query .= " OFFSET ".$keywords['offset'];
            }
        }
        return $query;
    }

    public function Delete(string $table_name,\Lanous\db\Table\Where $where=null) : string {
        $query = "DELETE FROM `$table_name`";
        if(isset($where)) {
            array_map(function ($data) use (&$query) {
                if ($data["condition"] == "main")
                    $query .= " WHERE ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
                if ($data["condition"] == "and")
                    $query .= " AND ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
                if ($data["condition"] == "or")
                    $query .= " OR ".$data["column_name"]." ".$data["operator"]." ".$data["value"].",";
            },$where->where);
            $query = rtrim($query,",");
        }
        return $query;
    }

}