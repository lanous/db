<?php

namespace Lanous\db;

class Lanous {
    const ORDER_ASC = "ASC";
    const ORDER_DESC = "DESC";
    final protected function MakeQuery($dbsm) {
        $dbsm = ucfirst($dbsm);
        $class = "\\Lanous\\db\\Query\\".$dbsm;
        return new $class();
    }
}