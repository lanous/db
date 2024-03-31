<?php

namespace Lanous\db\Settings;

class Table {
    private $database;
    public function __construct($database) {
        $this->database = $database;
    }
    public function AddTable() { }
    public function DropTable() { }
}