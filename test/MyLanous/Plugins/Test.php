<?php

namespace MyLanous\Plugins;

class Test extends \Lanous\db\Structure\Plugins {
    public function GetName ($id) {
        $Users = $this->Table("test");
        $Find = $Users->Where(\MyLanous\Table\Users::ID,"=",$id);
        $User = $Users->Select("*")->Extract($Find)->Rows[0];
        return $User->{\MyLanous\Table\Users::first_name}->value;
    }
}