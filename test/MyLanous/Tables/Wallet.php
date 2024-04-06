<?php

namespace MyLanous\Tables;

class Wallet extends \Lanous\db\Structure\Table {
    public function __construct() {
        $this->AddColumn("user_id")
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255)
            ->Constraints(Primary: true);

        $this->AddColumn("usd")
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255)
            ->Constraints(not_null: true);
    }
}