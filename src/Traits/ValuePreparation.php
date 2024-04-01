<?php

namespace Lanous\db\Traits;
trait ValuePreparation
{
    public function ValuePreparation($table_class,$column_name,$value,$method="get") {
        $table = new $table_class();
        $table = $table->Result();
        $DataHandling = $table[\Lanous\db\Structure\Table::Result['DataHandling']];
        $DataType = new $table[\Lanous\db\Structure\Table::Result['Columns']][$column_name]["type"]($value);

        if ($method == "get") {
            if (isset($DataHandling["Extract"][$column_name])) {
                if(isset($DataHandling["Extract"][$column_name][\Lanous\db\Structure\Table::DataHandling["Evaluation"]])) {
                    $DataHandling["Extract"][$column_name][\Lanous\db\Structure\Table::DataHandling["Evaluation"]]($value);
                }
                if(isset($DataHandling["Extract"][$column_name][\Lanous\db\Structure\Table::DataHandling["Edit"]])) {
                    $value = $DataHandling["Extract"][$column_name][\Lanous\db\Structure\Table::DataHandling["Edit"]]($value);
                }
            }
            $result = [];
            $result["methods"] = $DataType;
            $result["value"] = $DataType->Extraction($value);
            $value = (object) $result;
        }
        if ($method == "send") {
            if(!$DataType->Validation($value)) {
                throw new \Lanous\db\Exceptions\Structure(" ---- ");
            }
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
        $value = !is_array($value) ? ($method == "send" ? "'$value'" : $value) : $value;
        return $value;
    }
}
