<?php

namespace MyLanous\DataTypes;

class Integer implements \Lanous\db\Structure\DataType {
    const Query = "int";
    private $data;
    public $Percentage;
    public function __construct($data) {
        $this->data = $data;
        $this->Percentage = new Percentage($data);
    }
    public function Injection($data) { return $data; }
    public function Extraction($data) { return $data; }
    public function Validation($data): bool { return true; }
}

class Percentage {
    private $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function toNumber ($percentage) {
        return ($percentage * $this->data) / 100;
    }
    public function toPercentage ($number) {
        return ($number * 100) / $this->data;
    }
}