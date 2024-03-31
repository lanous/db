<?php

namespace MyLanous\Table;

class Users extends \Lanous\db\Structure\Table {

    const ID = "id";
    const first_name = "first_name";
    const last_name = "last_name";
    const password = "password";
    const address = "address";

    public function __construct() {
        # Column List
        $this->AddColumn(self::ID)
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255)
            ->AutoIncrement(true)
            ->Primary();

        $this->AddColumn(self::first_name)
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255);

        $this->AddColumn(self::last_name)
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255);

        $this->AddColumn(self::password)
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255);

        $this->AddColumn(self::address)
            ->DataType(\MyLanous\DataTypes\ArrayData::class)
            ->Size(255);

        # ---------- Data Handling
        $this->Injection(self::first_name)
            ->Evaluation(function ($data) {
                if ($data == "mohammad")
                    throw new \Exception("you can't set mohammad name");
            });
        $this->Injection(self::first_name)
            ->Edit(fn($data) => strtolower($data));
        $this->Injection(self::last_name)
            ->Edit(fn($data) => strtolower($data));

        # Base64 encode/decode password
        $this->Injection(self::password)
            ->Edit(fn($data) => base64_encode($data));
        $this->Extract(self::password)
            ->Edit(fn($data) => base64_decode($data));
    }
}