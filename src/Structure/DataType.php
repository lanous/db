<?php

namespace Lanous\db\Structure;

interface DataType {
    public function Injection($data);
    public function Extraction($data);
    public function Validation($data) : bool;
}