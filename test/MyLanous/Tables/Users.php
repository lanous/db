<?php

namespace MyLanous\Tables;

enum UsersStatus {
    case Active;
    case Disabled;
    function toPersian() {
        return match($this->name) {
            "Active" => "فعال",
            "Disabled" => "غیرفعال"
        };
    }
}

class Users extends \Lanous\db\Structure\Table {

    const ID = "id";
    const first_name = "first_name";
    const last_name = "last_name";
    const password = "password";
    const address = "address";
    const status = "status";
    const join_time = "created_at";

    public function __construct() {
        # Column List
        $this->AddColumn(self::ID)
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255)
            ->AutoIncrement(true)
            ->Constraints(Primary: true);

        $this->AddColumn(self::first_name)
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255)
            ->Constraints(not_null: true);

        $this->AddColumn(self::last_name)
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255)
            ->Constraints(not_null: true);
            
        $this->AddColumn(self::password)
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255)
            ->Constraints(not_null: true);

        $this->AddColumn(self::status)
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255)
            ->Enum(UsersStatus::class)
            ->Constraints(not_null: true);


        $this->AddColumn(self::address)
            ->DataType(\MyLanous\DataTypes\ArrayData::class)
            ->Constraints(not_null: true);


        $this->AddColumn(self::join_time)
            ->DataType(\MyLanous\DataTypes\Timestamp::class)
            ->Constraints(default: "NOW()");
            
        # ---------- Data Handling
        /*$this->Injection(self::first_name)
            ->Evaluation(function ($data) {
                if ($data == "mohammad")
                    throw new \Exception("you can't set mohammad name");
            });*/
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