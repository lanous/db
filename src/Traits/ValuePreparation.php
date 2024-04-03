<?php

namespace Lanous\db\Traits;

trait ValuePreparation
{
    const PreparationExtract = 'get';
    const Preparationinjection = 'send';
    /**
     * Data passes through this method after extraction and before injection.
     * The extraction output is different from the injection output because the injection output is sent to the query.
     * @param string $table_class The class name of the data table that is taken from the developer.
     * @param string $column_name The name of the column is received to identify the data type. (for extraction and injection method)
     * @param mixed $value The data we intend to extract or inject.
     * @param string $method Is this data used for <b>self::Preparationinjection</b> or <b>self::PreparationExtract</b>?
     */
    public function ValuePreparation(string $table_class,string $column_name,mixed $value,$method=self::PreparationExtract) {
        $table = new $table_class();
        $table = $table->Result();
        $DataHandling = $table[\Lanous\db\Structure\Table::Result['DataHandling']];
        $DataType = new $table[\Lanous\db\Structure\Table::Result['Columns']][$column_name]["type"]($value);

        if ($method == self::PreparationExtract) {
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
        if ($method == self::Preparationinjection) {
            if(!$DataType->Validation($value)) {
                throw new \Lanous\db\Exceptions\Structure(\Lanous\db\Exceptions\Structure::ERR_VLDDTYP);
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