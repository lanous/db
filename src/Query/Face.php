<?php

namespace Lanous\db\Query;

interface Face {
    public function MakeTable(string $table_name,array $data) : string;
    public function Insert(string $table_name,array $data) : string;
}