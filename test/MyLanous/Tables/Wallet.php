<?php
namespace MyLanous\Tables;

class Wallet extends \Lanous\db\Structure\Table {
    public function __construct() {
        $this->AddColumn("user_id")
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255)
            ->AutoIncrement(true)
            ->Constraints(Primary: true);

        $this->AddColumn("usd")
            ->DataType(\MyLanous\DataTypes\Decimal::class)
            ->Size(10, 2)
            ->Constraints(not_null: true);

        $this->AddColumn("irt")
            ->DataType(\MyLanous\DataTypes\Decimal::class)
            ->Size(10, 2)
            ->Constraints(not_null: true);
    }
}