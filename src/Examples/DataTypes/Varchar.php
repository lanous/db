<?php

namespace MyLanous\DataTypes;

class Varchar implements \Lanous\db\Structure\DataType {
    const Query = "varchar";
    public function Injection($data) { return $data; }
    public function Extraction($data) { return $data; }
    public function Validation($data): bool { return true; }
}