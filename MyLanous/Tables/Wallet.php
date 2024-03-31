<?php

namespace MyLanous\Table;


class Wallet extends \Lanous\db\Structure\Table {
    public function __construct() {
        # Column List
        $this->AddColumn("id")
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255)
            ->AutoIncrement(true);

        $this->AddColumn("usd")
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255);

        $this->AddColumn("irt")
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255);

        # ---------- Data Handling
        $this->Injection("usd")
            ->Evaluation(fn($data) => $data > 0);
        $this->Injection("irt")
            ->Evaluation(fn($data) => $data > 0);
    }
}