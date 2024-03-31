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
    public function __construct() {
        $this->DataBase = new Database();
        $this->Table = new Table();
    }
}