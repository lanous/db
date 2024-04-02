<?php

namespace Lanous\db\Structure;

abstract class Plugins {
    private $database,$dbsm,$data;
    public function __construct($database,$dbsm,$data) {
        $this->database = $database;
        $this->dbsm = $dbsm;
        $this->data = $data;
    }
    public function Table (string $table_name) {
        return new \Lanous\db\Table\Table($table_name,$this->dbsm,$this->database);
    }
    public function Call (string $plugin_class,$data=null) {
        return new $plugin_class($this->database,$this->dbsm,$data);
    }
    public function NewJob () {
        return new \Lanous\db\Jobs\Make($this->database,$this->dbsm);
    }

}