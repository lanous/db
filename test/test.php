<?php

include("vendor/autoload.php");

use Lanous\db as Database;

class LanousConfig {
    const hostname = '127.0.0.1';
    const username = 'root';
    const password = '';
    const database = "lanous";
    const dbsm = Database\Lanous::DBSM_Mysql;
    const project_name = "MyLanous";
    const project_dir = __DIR__;
}

$database = new Database\Connect(new LanousConfig);

$database->Setting->Table(MyLanous\Table\Wallet::class)
    ->FOREIGN_KEY("user_id",MyLanous\Table\Users::class,"id");

$WalletTable = $database->OpenTable (MyLanous\Table\Wallet::class);
$UsersTable = $database->OpenTable (MyLanous\Table\Users::class);

/*
$UsersTable->Insert()
    ->Set(MyLanous\Table\Users::first_name,"Mohammad")
    ->Set(MyLanous\Table\Users::last_name,"azad")
    ->Set(MyLanous\Table\Users::address,["city"=>"karaj"])
    ->Set(MyLanous\Table\Users::status,MyLanous\Table\UsersStatus::Active)
->Push();

$WalletTable->Insert()
    ->Set("user_id",1)
    ->Set("usd",5000)
->Push();
*/

$Table = $database->OpenTable (MyLanous\Table\Users::class);
$User = $Table->Select(column: "*")->Extract(primary_value: 1);
$Wallet = $User->Child (MyLanous\Table\Wallet::class); #Check L.N 19
$UserData = $User->LastRow();
// echo "Hello ".$UserData['first_name']."! - Your dollar wallet: $".$Wallet['usd'];
# Hello mohammad! - Your dollar wallet: $5000

$UserData2 = $Table->QuickFind(2);
// echo "Hello ".$UserData2["first_name"];
# Hello mohammad

# The first method
$method_1 = $Table->Select(column: "*")->Extract(primary_value: 1);

# The second method
$Where = $Table->Where(MyLanous\Table\Users::ID,"=",1);
$method_2 = $Table->Select(column: "*")->Extract($Where);
$data = $method_2;

$created_at1 = $method_2->LastRow();
$created_at1 = $created_at1["created_at"];
$callback_test = $method_2->Callback(function ($column,$value) {
    if($column == "created_at") {
        return $value;
    }
});
$created_at2 = $callback_test->LastRow(\Lanous\db\Table\RowReturn::ObjectType);
var_dump($created_at2->created_at->Date->MakeArray());

/*
array(7) {
  ["id"]=>
  int(1)
  ["first_name"]=>
  string(5) "Ahmad"
  ["last_name"]=>
  string(4) "azad"
  ["password"]=>
  string(0) ""
  ["status"]=>
  enum(MyLanous\Table\UsersStatus::Active)
  ["address"]=>
  array(1) {
    ["city"]=>
    string(5) "karaj"
  }
  ["created_at"]=>
  int(1712324262)
}
*/

if ($data == false)
    exit("no data found!");
# LastRow | FirstRow

$data->LastRow()["first_name"];
# mohammad

$data->LastRow($data::ObjectType)->{MyLanous\Table\Users::join_time}->Date->format("Y-m-d H:i:s");
# "2024-04-05 12:17:03"

$data->LastRow($data::ObjectType)->{MyLanous\Table\Users::status}->value;
# enum(MyLanous\Table\UsersStatus::Active)

$data->LastRow($data::ObjectType)->{MyLanous\Table\Users::status}->value->toPersian();
# فعال

$data->LastRow($data::ArrayType)['first_name'];
// mohammad

$data->LastRow($data::Keys)[1];
// first_name

$data->LastRow($data::Methods)->first_name->test("foo","bar");
// Hello mohammad p.a = foo and p.b = bar

$data->LastRow($data::ObjectType)->first_name->value;
// mohammad
$data->LastRow($data::ObjectType)->first_name
        ->methods->test("foo","bar");
// Hello mohammad p.a = foo and p.b = bar

$data->LastRow($data::Values)[1];
// mohammad



/*

try {

    $Job = $database->NewJob();
        $Job->Sensitivity(2);

        $User1 = $Job->Get(MyLanous\Table\Users::class,1);
        $User2 = $Job->Get(MyLanous\Table\Users::class,2);

        $Job->Edit($User1,"amount",50000);
        $Job->Edit($User2,"amount",100000);

} catch (\Lanous\db\Exceptions\Jobs $error) {

    if ($error->getCode() == $error::ERR_RECOVERY) {
        // -- Be sure to specify this case in the catch --
        // If the error code is 700, it means that the data recovery has encountered an error
        // and it is better to check the operation manually.
    } elseif ($error->getCode() == $error::ERR_NOCHANGE) {
        // No changes were made to one of the rows
    } elseif ($error->getCode() == $error::ERR_EXPERROR) {
        // An error occurred while applying the changes.
    } elseif ($error->getCode() == $error::ERR_CANTFIND) {
        // One of the data was not found in the get method.
    } elseif ($error->getCode() == $error::ERR_DUPLICTE) {
        // When the repeated get method is written, you will encounter this error.
    }

}
*/


/*
$Table = $database->OpenTable (MyLanous\Table\Users::class);

$Users = $database->LoadPlugin(MyLanous\Plugins\Test::class);
echo "Hi ".$Users->GetName (1).PHP_EOL;

$Where = $Table->Where(MyLanous\Table\Users::ID,"=",1);

$Order = $Table::Order([
    MyLanous\Table\Users::first_name=>Database\Lanous::ORDER_ASC,
    MyLanous\Table\Users::last_name=>Database\Lanous::ORDER_DESC
]);

var_dump($Table->Select(
    column: "*",
    order_by: $Order,
    distinct: true,
    limit: 10
)->Extract($Where));

*/
//$Table->Update()->Edit("password","987654321")->Push();

/*
var_dump($Table->Select()->Where($Where)->Result()->Rows[0]->password->value);

$Table->Update()->Edit("password","123456789")->Set($Where);

var_dump($Table->Select()->Where($Where)->Result()->Rows[0]->password->value);
*/

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