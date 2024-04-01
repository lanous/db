<?php

namespace Lanous\db\Table;

class ReturnStrc {
    public $Rows;
    public function __construct ($rows){
        $this->Rows = $rows;
    }
}