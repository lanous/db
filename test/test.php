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

$Table = $database->OpenTable (MyLanous\Table\Users::class);
$Users = $database->Load(MyLanous\Plugins\Test::class);
echo "Hi ".$Users->GetName (1).PHP_EOL;

$Where = $Table->Where(MyLanous\Table\Users::ID,"=",1);

$Table->Update()->Edit("password","987654321")->Where($Where)->Set();

var_dump($Table->Select()->Where($Where)->Result()->Rows[0]->password->value);

$Table->Update()->Edit("password","123456789")->Where($Where)->Set();

var_dump($Table->Select()->Where($Where)->Result()->Rows[0]->password->value);


/*
$data = $Table->Select(
    column: "*",
    order_by: $Order,
    distinct: true
)->Where("first_name","=","mohammad")->Result();
*/



// $Table->Select("*",order_by: );


/*
$Users = $database->Load(MyLanous\Plugins\Test::class);
var_dump($Users->GetName (2));
*/

/*
echo $database->Table(MyLanous\Table\Users::class)->Insert()
    ->Key(MyLanous\Table\Users::first_name)->Value("Mohammad")
    ->Key(MyLanous\Table\Users::last_name)->Value("azad")
    ->Key(MyLanous\Table\Users::address)->Value(["city"=>"karaj"])
->Push();

$Table = $database->Table(MyLanous\Table\Users::class)->Select("*");

$Where1 = $Table
            ->Where(MyLanous\Table\Users::first_name,"mohammad")
            ->And(MyLanous\Table\Users::last_name,"azad")
        ->Result()->Rows[0];

$Where2 = $Table
            ->Where(MyLanous\Table\Users::first_name,"mohammad2")
            ->And(MyLanous\Table\Users::last_name,"azad2")
        ->Result()->Rows[0];


var_dump($Where1->{MyLanous\Table\Users::first_name}->value);

*/

/*
// method 1
$Order = $Table::Order([
        MyLanous\Table\Users::first_name=>Database\Lanous::ORDER_ASC,
        MyLanous\Table\Users::last_name=>Database\Lanous::ORDER_DESC
    ]);
// method 2
$Order = $Table::Order([
    MyLanous\Table\Users::first_name,
    MyLanous\Table\Users::last_name
],Database\Lanous::ORDER_ASC);
*/