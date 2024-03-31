<?php

namespace Lanous\db;

class Lanous {
    final protected function MakeQuery($dbsm) {
        $dbsm = ucfirst($dbsm);
        $class = "\\Lanous\\db\\Query\\".$dbsm;
        return new $class();
    }
}

trait ValuePreparation
{
    public function ValuePreparation($table_class,$column_name,$value,$method="get") {

        $table = new $table_class();
        $table = $table->Result();
        $DataHandling = $table[\Lanous\db\Structure\Table::Result['DataHandling']];
        $DataType = new $table[\Lanous\db\Structure\Table::Result['Columns']][$column_name]["type"]();

        if(!$DataType->Validation($value))
            throw new \Lanous\db\Exceptions\Structure(" ---- ");

        if ($method == "get") {
            if (isset($DataHandling["Extract"][$column_name])) {
                if(isset($DataHandling["Extract"][$column_name][\Lanous\db\Structure\Table::DataHandling["Evaluation"]])) {
                    $DataHandling["Extract"][$column_name][\Lanous\db\Structure\Table::DataHandling["Evaluation"]]($value);
                }
                if(isset($DataHandling["Extract"][$column_name][\Lanous\db\Structure\Table::DataHandling["Edit"]])) {
                    $value = $DataHandling["Extract"][$column_name][\Lanous\db\Structure\Table::DataHandling["Edit"]]($value);
                }
            }
            $value = $DataType->Extraction($value);
        }
        if ($method == "send") {
            if (isset($DataHandling["Injection"][$column_name])) {
                if(isset($DataHandling["Injection"][$column_name][\Lanous\db\Structure\Table::DataHandling["Evaluation"]])) {
                    $DataHandling["Injection"][$column_name][\Lanous\db\Structure\Table::DataHandling["Evaluation"]]($value);
                }
                if(isset($DataHandling["Injection"][$column_name][\Lanous\db\Structure\Table::DataHandling["Edit"]])) {
                    $value = $DataHandling["Injection"][$column_name][\Lanous\db\Structure\Table::DataHandling["Edit"]]($value);
                }
            }
            $value = $DataType->Injection($value);
        }

        $value = "'$value'";
        return $value;
    }
}
