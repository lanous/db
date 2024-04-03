<?php

namespace MyLanous\DataTypes;

class Varchar implements \Lanous\db\Structure\DataType {
    const Query = "varchar";
    private $data;
    public function __construct($data) { $this->data = $data; }
    public function Injection($data) { return $data; }
    public function Extraction($data) { return $data; }
    public function Validation($data): bool { return true; }
    /**
     * @deprecated
     */
    public function test($a,$b) : string {
        return "Hello ".$this->data." p.a = ".$a." and p.b = ".$b;
    }
}