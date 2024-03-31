<?php

namespace MyLanous\DataTypes;

class Integer implements \Lanous\db\Structure\DataType {
    const Query = "int";
    private $data;
    public function __construct($data) { $this->data = $this->Extraction($data); }
    public function Injection($data) { return $data; }
    public function Extraction($data) { return $data; }
    public function Validation($data): bool { return true; }

}