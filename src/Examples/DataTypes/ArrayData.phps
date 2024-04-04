<?php

namespace MyLanous\DataTypes;

class ArrayData implements \Lanous\db\Structure\DataType {
    const Query = "JSON";
    private $data;
    public function __construct($data) { $this->data = $data; }
    public function Injection($data) { return json_encode($data); }
    public function Extraction($data) { return json_decode($data,1); }
    public function Validation($data): bool { return is_array($data); }
}