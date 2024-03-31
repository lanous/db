<?php

namespace Lanous\db\Settings;

class Settings {
    /**
     * Database settings
     */
    public $DataBase;
    /**
     * Table settings
     */
    public $Table;
    public function __construct($database) {
        $this->DataBase = new Database($database);
        $this->Table = new Table($database);
    }
}