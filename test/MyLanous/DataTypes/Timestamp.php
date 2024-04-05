<?php

namespace MyLanous\DataTypes;

class Timestamp implements \Lanous\db\Structure\DataType {
    const Query = "timestamp";
    private $data;
    public $Date;
    public function __construct($data) {
        $this->data = $data;
        $this->Date = new Date($data);
    }
    public function Injection($data) { return date("Y-m-d H:i:s",$data); }
    public function Extraction($data) { return strtotime($data); }
    public function Validation($data): bool { return true; }
}

class Date {
    private $data;
    public function __construct($data) { $this->data = strtotime($data); }
    public function Format($date_format) {
        return \date($date_format,$this->data);
    } 
}