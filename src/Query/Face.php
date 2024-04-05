<?php

namespace Lanous\db\Query;

interface Face {
    public function MakeTable(string $table_name,array $data) : string;
    public function Insert(string $table_name,array $data) : string;
    public function Update(string $table_name,array $new_values,\Lanous\db\Table\Where $where=null) : string;
    public function Extract(string $table_name,string $columns,array $keywords,\Lanous\db\Table\Where $where=null) : string;
    public function Delete(string $table_name,\Lanous\db\Table\Where $where=null) : string;
    public function Alter(string $table_name,array $data) : string;
}