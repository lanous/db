<?php

namespace Lanous\db;

/**
 * @author Mohammad Azad
 * @package Lanous\db
 * @version 1.0.0
 * @link https://github.com/lanous/db/
 */
class Lanous {
    const ORDER_ASC = "ASC";
    const ORDER_DESC = "DESC";

    
    const DBSM_Mysql = "mysql";

    final protected function MakeQuery(object $config) : object {
        $dbsm = ucfirst($config::dbsm);
        $class = "\\Lanous\\db\\Query\\".$dbsm;
        return new $class();
    }
}