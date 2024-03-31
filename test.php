<?php

include("vendor/autoload.php");

use Lanous\db as Database;

class LanousConfig {
    const hostname = '127.0.0.1';
    const username = 'root';
    const password = '';
    const database = "lanous";
    const dbsm = "mysql";
    const project_name = "MyLanous";
}


$database = new Database\Connect(new LanousConfig);

$database->Table(MyLanous\Table\Users::class)->Insert()
    ->Key(MyLanous\Table\Users::first_name)->Value("Mohammad")
    ->Key(MyLanous\Table\Users::last_name)->Value("azad")
    ->Key(MyLanous\Table\Users::address)->Value(["city"=>"karaj"])
->Push();


// $database->Table(MyLanous\Table\Users::class);
