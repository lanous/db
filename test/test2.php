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
    const project_dir = __DIR__;
}


$database = new Database\Connect(new LanousConfig);
$table = $database->OpenTable(MyLanous\Table\Users::class);
$where = $table->Where("ID","=",1);
$table->Select("*")->Extract($where);
