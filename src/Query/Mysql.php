<?php

namespace Lanous\db\Query;

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
            $this->query .= "`$column_name` $QueryType";
            $this->query .= $DataSize != false ? "($DataSize)" : " ";
            $this->query .= ($AutoIncrement == false) ? "" : "NOT NULL AUTO_INCREMENT ";
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
}