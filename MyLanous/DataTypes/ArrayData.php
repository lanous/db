<?php

namespace MyLanous\DataTypes;

class ArrayData implements \Lanous\db\Structure\DataType {
    const Query = "JSON";
    public function Injection($data) { return json_encode($data); }
    public function Extraction($data) { return json_decode($data); }
    public function Validation($data): bool { return is_array($data); }

}