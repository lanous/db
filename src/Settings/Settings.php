<?php

namespace Lanous\db\Settings;

class Settings {
    private $database;
    private $dbsm;
    public function __construct($database,$dbsm) {
        $this->database = $database;
        $this->dbsm = $dbsm;
    }
    public function Table($table_class) {
        return new Table($this->database,$this->dbsm,$table_class);
    }
}