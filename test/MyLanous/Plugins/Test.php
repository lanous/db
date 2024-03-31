<?php

namespace MyLanous\Plugins;

class Test extends \Lanous\db\Structure\Plugins {
    public function GetName ($id) {
        $Users = $this->Table(\MyLanous\Table\Users::class)->Select("*");
        $Users = $Users->Where(\MyLanous\Table\Users::ID,$id);
        $User = $Users->Result()->Rows[0];
        return $User->{\MyLanous\Table\Users::first_name}->value;
    }
}

?>