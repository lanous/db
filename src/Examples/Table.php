<?php

namespace ProjectName\Table;
# ----- Change [ProjectName]


class Users extends \Lanous\db\Structure\Table {
    public function __construct() {
        # Column List
        # Change [ProjectName]
        $this->AddColumn("id")->DataType(\ProjectName\DataTypes\Integer::class)->Size(255);
        $this->AddColumn("first_name")->DataType(\ProjectName\DataTypes\Varchar::class)->Size(255);
        $this->AddColumn("last_name")->DataType(\ProjectName\DataTypes\Varchar::class)->Size(255);
        $this->AddColumn("password")->DataType(\ProjectName\DataTypes\Varchar::class)->Size(255);

        # ---------- Data Handling
        # Lowercase Firstname and Lastname
        $this->Injection("first_name")
            ->Edit(fn($data) => strtolower($data));
        $this->Injection("last_name")
            ->Edit(fn($data) => strtolower($data));

        # Base64 encode/decode password
        $this->Injection("password")
            ->Edit(fn($data) => base64_encode($data));
        $this->Extract("password")
            ->Edit(fn($data) => base64_decode($data));
    }
}