<?php

namespace Lanous\db\Settings;

class Database {
    private $database;
    public function __construct($database) {
        $this->database = $database;
    }
}