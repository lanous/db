# Introduction

The purpose of creating this project is to utilize various database types without the need to learn their structures and commands.
Initially, learning this project might seem a bit complex, but using it is enjoyable.
The flexibility of this project is high, and it has significant potential for further development.
These documents are written for users of this project, and development-related documentation is located in the src directory.

One of the drawbacks of this project may appear to be its initial complexity, which might lead one to think it’s not suitable for open-source projects. However, if your project is personal, this library can be quite helpful because:

Automatic Data Encryption: Data encryption is performed automatically.
Extensible Data Type Structures: The data type structures are extensible.
Avoids Database Complexity: It stays clear of database intricacies.
High Scalability: It is highly scalable.

# Overview

```php
$database = new Database\Connect(new LanousConfig);

$Table = $database->OpenTable (MyLanous\Table\Users::class);

$Where = $Table->Where(MyLanous\Table\Users::ID,"=",1);

$data = $Table->Select(
    column: "*",
    distinct: true
)->Extract();

$data->LastRow($data::ObjectType)->first_name->value;
```

As you can see, everything is in an object-oriented manner. In fact, your data table should be defined within a class structure with specific rules and principles so that the data can be handled effectively throughout the project.

## Example of defined table

```php
        $this->AddColumn("ID")
            ->DataType(\MyLanous\DataTypes\Integer::class)
            ->Size(255)
            ->AutoIncrement(true)
            ->Constraints(Primary: true);

        $this->AddColumn("first_name")
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255);

        $this->AddColumn("last_name")
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255);

        $this->AddColumn("password")
            ->DataType(\MyLanous\DataTypes\Varchar::class)
            ->Size(255);

        $this->AddColumn("address")
            ->DataType(\MyLanous\DataTypes\ArrayData::class);
            
        # ---------- Data Handling
        $this->Injection("first_name")
            ->Edit(fn($data) => strtolower($data));
        $this->Injection("last_name")
            ->Edit(fn($data) => strtolower($data));

        # Base64 encode/decode password
        $this->Injection("password")
            ->Edit(fn($data) => base64_encode($data));
        $this->Extract("password")
            ->Edit(fn($data) => base64_decode($data));
```

As you can see, data types are of class type and we have placed an example of this class below.

```php
class ArrayData implements \Lanous\db\Structure\DataType {
    const Query = "JSON";
    # The data being input and output is passed to the construct.
    private $data;
    public function __construct($data) {
        $this->data = $data;
    }
    # Before data is entered into the database, it passes through this function.
    public function Injection($data) {
        return json_encode($data);
    }
    # After data is extracted from the database, it also passes through this function.
    public function Extraction($data) {
        return json_decode($data,1);
    }
    # By using this function, we prevent specific types or expressions from entering the database.
    public function Validation($data): bool {
        return is_array($data);
    }
    # You can also add specific functions to these data types.
    # Stay tuned for more documentation!
}
```
# Idioms
## Database
a storage location for a collection of data that is accessible and editable.
## Database management system (D B S M)
A software that uses a set of tools for managing database data, responsible for communication and interaction with the database.
## Tables
Data is stored systematically and categorized. Each table holds a group of related data. For example, the ‘user information’ table stores users’ identity information such as name, surname, and birthdate. This helps ensure that data is appropriately placed.
## Columns
Columns essentially define what the data is related to. Columns are members of tables and hold the data within them. For example, the “last name” column places users’ last names within it. Each column has specific settings, including data type, data size, constraints, and other properties.
### Primary
When a column is designated as the primary key, it acts like a unique fingerprint for data rows and prevents duplicate values in that column. Once a column is identified as the primary key, data can be edited using this primary key. Typically, this column is named "id" and throughout the program, this ID is used to identify users.
### Foreign Key
Referencing a column to the primary key of another table creates data consistency and establishes a relationship between them.

# Setting up
## Project installation
Install the lanous\db package using composer.

```shell
composer require lanous\db
```

After completing the installation, include autoload.php in the main project file (which can be index.php).

```php
<?php

include("vendor/autoload.php");

        ...

?>
```
After the project is loaded successfully, you need to connect to the database through the connect class of the project. Before connecting, it is necessary to create a config class.
Your config class should include these constants: ``hostname``, ``username``, ``password``, ``database`` - ``dbsm``, ``project_name``, ``project_dir``

An example of a config class:

```php
class LanousConfig {
    const hostname = '127.0.0.1';
    const username = 'root';
    const password = '';
    const database = "lanous";
    const dbsm = "mysql";
    const project_name = "MyLanous";
    const project_dir = __DIR__;
}
```

Furthermore, for configuring the DBMS, you can use library constants. This approach helps prevent typing errors and also informs you about the supported DBMS options (with PHPDoc)

```php
    ...
    const dbsm = Database\Lanous::DBSM_Mysql; # Database\Lanous::DBSM_(X)
    ...
```

After creating the configuration class, it’s time to establish a connection with the database.

```php
$database = new Database\Connect(new LanousConfig);
```

### Final code:

```php
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
```

After the initial execution, a directory is created in the configured folder (``project_dir``) with the name specified in the configuration class (``project_name``).

Follow the instructions to examine each of these directories.

## Set data table structure - create a new table

The ‘Tables’ folder is the location where data table structures are stored. The filenames essentially represent the class and table names (meaning all three should match). To create a data table, you need to create a namespace with the project name and inherit from \Lanous\db\Structure\Table

### General Structure
```php
namespace {project_name}\Tables;

class {table_name} extends \Lanous\db\Structure\Table {

...

}
```

We complete the table structure and add columns using the functions defined in the parent class.

Below, we explain the functions related to the columns:

### Functions

``AddColumn(string $name)``: Specifies the name of the column.

    -Sub-Methods:
    
        -DataType($object): The name of the data type class (List: DataTypes\x.php)
        
        -Size($size): Data type size

        -AutoIncrement(bool $value): Auto increment if set to true

        -Enum($class): Set enum as value

        -Constraints(
            bool $Primary=false,
            bool $not_null=false,
            bool $UNIQUE=false,
            $default=false,
            $check=false
        ): Constraints settings

``Injection($column_name)``: Before data is entered into the database, it passes through this function.

    -Sub-Methods:
    
        -Evaluation(callable $callback): Data validation
        
        -Edit(callable $callback): Data editing using callable
        
``Extract($column_name)``: After data is retrieved from the database, it also passes through this function.

    -Sub-Methods:
    
        -Evaluation(callable $callback): Data validation
        
        -Edit(callable $callback): Data editing using callable

### An example of the table class

```php
<?php
# project_name = MyLanous
# MyLanous/Tables/Information.php

namespace MyLanous\Tables;

class Information extends \Lanous\db\Structure\Table {
    public function __construct() {
        $this->AddColumn("user_id")
            ->DataType(\MyLanous\DataTypes\Integer::class) # MyLanous/DataTypes/Integer.php
            ->Size(255)
            ->AutoIncrement(true)
            ->Constraints(Primary: true);

        $this->AddColumn("first_name")
            ->DataType(\MyLanous\DataTypes\Varchar::class) # MyLanous/DataTypes/Varchar.php
            ->Size(255)
            ->Constraints(not_null: true);

        $this->AddColumn("last_name")
            ->DataType(\MyLanous\DataTypes\Varchar::class) # MyLanous/DataTypes/Varchar.php
            ->Size(255)
            ->Constraints(not_null: true);

        $this->AddColumn("password")
            ->DataType(\MyLanous\DataTypes\Varchar::class) # MyLanous/DataTypes/Varchar.php
            ->Size(255)
            ->Constraints(not_null: true);

        // Automatic encryption - decryption (base64)
        $this->Injection(self::password)
            ->Edit(fn($data) => base64_encode($data));
        $this->Extract(self::password)
            ->Edit(fn($data) => base64_decode($data));
    }
}
```
After creating the class and saving the file, the process of table creation will be done automatically.

Continue reading the descriptions related to data types to expand them.

## Data Type Development

After creating project folders, another folder named DataTypes is automatically generated. All data types that need to be defined for columns are created here. These data types implement \Lanous\db\Structure\DataType.

The DataType class requires four functions for definition:

``__construct``: Data is initially sent to this function when constructing the DataType class. If you intend to extend the data type, store it in a private property.

``Injection``: Before data enters the database (whether through an INSERT or an UPDATE), it is sent to this function. The output of this function becomes the column value. If the data remains unchanged, remember to use ``return $data;``.

``Extraction``: After data is extracted and before it reaches your hands, it enters this function, and the output is sent to you. Similar to before, if no modifications are needed, don’t forget to use ``return $data;``.

``Validation``: Use this function to prevent certain data from entering the database. **It is only called;** consider using exceptions.

Additionally, any function you define other than these is accessible in the output data via the methods property. Any property you add to this class is also accessible alongside the main output (value, methods).
### Consider the following examples:
```php
<?php
namespace MyLanous\DataTypes;
class ArrayData implements \Lanous\db\Structure\DataType {
    const Query = "JSON";
    private $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function Injection($data) {
        return json_encode($data);
    }
    public function Extraction($data) {
        return json_decode($data,1);
    }
    public function Validation($data): bool {
        return is_array($data);
    }
}
```
This is a data type that specifies a column as being of type **JSON** (``const Query = "JSON";``) (since PHP arrays cannot be directly stored in tables). This class encodes input data to JSON and decodes the output. As a result, you don’t need to encode and decode your data during the project process (which means your project code becomes more concise). Additionally, by using validations, we prevent data other than PHP arrays from entering.

**Pay attention to these examples:**

```php
<?php
namespace MyLanous\DataTypes;
class Varchar implements \Lanous\db\Structure\DataType {
    const Query = "varchar";
    private $data;
    public function __construct($data) { $this->data = $data; }
    public function Injection($data) { return $data; }
    public function Extraction($data) { return $data; }
    public function Validation($data): bool { return true; }
    public function test($a,$b) : string {
        return "Hello ".$this->data." p.a = ".$a." and p.b = ".$b;
    }
}
```

And when outputting:

```
$data->LastRow($data::ObjectType)->first_name->methods->test("foo","bar");
// Hello mohammad p.a = foo and p.b = bar
```

> [!NOTE]
> Don’t worry, the tutorial for extracting data from tables will be explained soon.

# Data injection

To enter data into a data table, we use the OpenTable method. This method has several sub-methods, including:
- SubMethod :
    - **Select**: Retrieves data from the table.
    - **Update**: Modifies existing data.
    - **Insert**: Adds new data to the table.
    - **Delete**: Removes data from the table.
    - **Describe**: Provides information about the table structure.
    - **QuickFind**: Searches for specific data.
    - **Order**: Sorts data.
    - **Where**: Filters data based on specified conditions.
  
For inserting data into the database, we use the ``Insert()`` method. This class utilizes two additional methods: ``Set()`` and ``Push()``.

**Set**: Assigns data to a specific column.
**Push**: Injects the data into the table.
The output of the Set method is the same as that of Insert, which means you can use multiple Set calls simultaneously.
Consider the following example to see how data is inserted into a table:

```php
$Table = $database->OpenTable (MyLanous\Tables\Users::class);
$UsersTable->Insert()
    ->Set("first_name","Mohammad")
    ->Set("last_name","Azad")
    ->Set("password","123456789")
->Push();
```
> [!IMPORTANT]
> The input “OpenTable” refers to the name of the data table class. Do not mistakenly enter the table name itself. If you use a different name instead of the class name, you will likely encounter this error.

```php
$Table = $database->OpenTable ("Users");
```
> [!CAUTION]
> PHP Fatal error:  Uncaught Lanous\db\Exceptions\Structure: The class you entered as a data table either does not exist or probably has a structural problem.

> [!IMPORTANT]
> In the data table structure, we have defined an ``Injection Edit`` for **first_name and last_name**. Therefore, the inputs (**Mohammad** and **Azad**) are stored as **mohammad** and **azad** (strtolower), respectively. Additionally, for the **password**, we have used both **Injection Edit and Extract Edit**. This means that throughout the project, the **output for the password remains 123456789, but in the database, it is stored in base64_encode format**.
```php
// {project_name}/Tables/Users.php
        # ---------- Data Handling
        $this->Injection("first_name")
            ->Edit(fn($data) => strtolower($data));
        $this->Injection("last_name")
            ->Edit(fn($data) => strtolower($data));

        # Base64 encode/decode password
        $this->Injection("password")
            ->Edit(fn($data) => base64_encode($data));
        $this->Extract("password")
            ->Edit(fn($data) => base64_decode($data));
```
**Table Row**

![image](https://github.com/lanous/db/assets/158297225/eb0bfd6b-6d47-4272-86e1-3d1db0182c13)

**In the code:**
```php
...
    $data->LastRow()["password"];
    // 123456789
...
```

# extracting output from a table.

Similar to before, you first need to open the data table (although if you’ve already opened the table for inserting data into a variable, you don’t need to repeat this step!!!).

After opening the table, use the Select sub-method. Essentially, by using Select, you choose a row and extract output from it. The Select method accepts several optional parameters:
- Parameters
    - Column: Specify the column(s) from which you want to retrieve data. Separate column names with commas. For example, first_name, last_name. If you want all columns, use * (which is the default).
    - Distinct: If you want the output data to be non-repetitive, set the value of distinct to true.
    - Order_By: To sort the output.
    - Limit: If you want to limit the number of output rows (e.g., retrieve only the first 100 rows), specify a numeric value for this parameter.
    - Offset: If you’ve set a limit, you can also use offset.

If you want to use ORDER BY, you should use the specific method for it, which is Order. This method is a sub-method of OpenTable:

```php
Order(array $columns, string $direction = null)
```

Consider an example of how to create an Order:

```php

$Order = $Table::Order([
    "first_name" => Lanous\db\Lanous::ORDER_ASC,
    "last_name" => Lanous\db\Lanous::ORDER_DESC
]);

```

n this example, Order sorts the names in ascending order (ASC) based on first name and then in descending order (DESC) based on last name. Additionally, if you only specify column names, you can set the sorting order for those columns. For instance:

```php
$Order = $Table::Order(["first_name", "last_name"], Lanous\db\Lanous::ORDER_ASC);
```

After creating the Order object, you can pass it as a parameter to Select. In general, a Select statement can be written as follows:

```php
$Table->Select(
    column: "*",
    distinct: false,
    order_by: $Order,
    limit: 100,
    offset: 0
);
```

Note that we are currently using default parameters, so you don’t need to specify all the options:

```php
$Table = $database->OpenTable (MyLanous\Tables\Users::class);
$Select = $Table->Select();
// To write the code more cleanly, it is better to write it like this
// $Select = $Table->Select(column: "*");
```

After configuring the Select parameters, use the Extract sub-method to retrieve your data.
```php
Extract (Where $where = null, $primary_value = null): false | RowReturn
```

Similar to Order, you need to create an object for Where using the Where sub-method (which is a sub-method of OpenTable).

If you want to find a column via its primary key, do not use Where. Instead, provide the primary key value as the second parameter. For example, I’ve set the ``id`` column as the **primary key**, and now I want to access other columns using the user’s id. Consider the following example:

```php
$Find_ID1 = $Select->Extract(primary_value: 1);
```

Using this parameter, we specify that we want to find the primary key with an ID of 1.

Otherwise, if you want to manually perform these actions (e.g., finding a data entry via first_name and last_name), we use the Where object. For example:
```php
$Where = $Table->Where("first_name", "=", "mohammad")
                            ->AND("last_name", "=", "azad");
```
The Where sub-methods include ``AND`` and ``OR``, which have similar structures and can be combined as needed.

After creating $Where, you can pass it as a parameter to the Extract method:

```php
$Find_MohammadAzad = $Select->Extract(where: $Where);
```

Outputting Data in Various Forms and Modes Is Possible

``Values``: This mode only outputs the values. For example:

```php
$row[0] == "1";
$row[1] == "mohammad";
$row[2] == "azad";
```

``Methods``: In this mode, only the methods defined in the data type are output as an object. For example:

```php
$row->first_name->test($a, $b);
```

``Keys``: This mode displays only the column names. For example:

```php
$row[0] == "id";
$row[1] == "first_name";
$row[2] == "last_name";
```

``ArrayType`` (**Default**): In this mode, the keys of the array correspond to column names, and their values match the values in the table columns. For example:

```php
$row["first_name"] == "mohammad";
```

``ObjectType``: This mode is used to access data type properties, methods, and values. Each column’s output includes these features:

``value;``: The actual value.

``methods->{METHOD_NAME}(PARAMETERS);``: Access to methods.

``{PropertiesDataType};``: Data type of properties.

```php
$row->first_name->value == "mohammad"
$row->first_name->methods->test('foo', 'bar')
$row->first_name->my_property == "i'm varchar"
```

To retrieve all data from the Rows feature, we use:

```php
$Table->Select(column: "*")->Extract($Where)->Rows;
```

By default, the output data type from this operation is an Array.

For using other forms and models, we utilize two additional sub-methods:

```php
FirstRow(int $mode=self::ArrayType)
```

To obtain the oldest recorded data from the results.

```php
LastRow(int $mode=self::ArrayType)
```

To retrieve the most recent data from the results.

Please refer to the following examples:

**Values Example**
```php
$UsersTable = $database->OpenTable (MyLanous\Tables\Users::class);
$User = $UsersTable->Select(column: "*")->Extract(primary_value: 1);
$UserValues = $User->LastRow(\Lanous\db\Table\RowReturn::Values);
return $UserValues[1];
// mohammad
```

**Methods Example**
```php
$UsersTable = $database->OpenTable (MyLanous\Tables\Users::class);
$User = $UsersTable->Select(column: "*")->Extract(primary_value: 1);
$UserMethods = $User->LastRow(\Lanous\db\Table\RowReturn::Methods);
return $UserMethods->first_name->test('foo','bar');
// Hello mohammad p.a = foo and p.b = bar
```

**Keys Example**
```php
$UsersTable = $database->OpenTable (MyLanous\Tables\Users::class);
$User = $UsersTable->Select(column: "*")->Extract(primary_value: 1);
$UserColumns = $User->LastRow(\Lanous\db\Table\RowReturn::Keys);
return $UserColumns[1];
// first_name
```

**ArrayType (Default) Example**
```php
$UsersTable = $database->OpenTable (MyLanous\Tables\Users::class);
$User = $UsersTable->Select(column: "*")->Extract(primary_value: 1);
$UserColumns = $User->LastRow(\Lanous\db\Table\RowReturn::ArrayType);
return $UserColumns["first_name"];
// mohammad
```

**ObjectType Methods**
```php
$UsersTable = $database->OpenTable (MyLanous\Tables\Users::class);
$User = $UsersTable->Select(column: "*")->Extract(primary_value: 1);
$UserData = $User->LastRow(\Lanous\db\Table\RowReturn::ObjectType);
$UserData->first_name->value; # mohammad
$UserData->first_name->my_property; # i'm varchar
$UserData->first_name->methods->test('foo','bar'); # Hello mohammad p.a = foo and p.b = bar
```

# How to update a row/rows?

Just like the previous explanation, this is another method that exists in OpenTable:

```php
Update() : Update
```

The Update method includes the following sub-methods:

```php
Edit(string $column_name, mixed $to) : Update
```

and

```php
Push(Where $where = null, $primary_value = null)
```

**example**

```php
$Table = $database->OpenTable (MyLanous\Tables\Users::class);
$Table->Update()
    ->Edit("password", "987654321")
    ->Edit("first_name", "new_name")
    ->Edit("last_name", "new_lastname")
->Push(primary_value: 1);
```

# Exceptions
During the program, we encounter a set of errors that are categorized as follows:

**PHP Fatal error**: Uncaught Lanous\db\Exceptions\{``type``}: ``ERROR MESSAGE`` [``ERROR_CODE``]

![image](https://github.com/lanous/db/assets/158297225/de8f2381-e9c9-45ad-8717-2653df7fb2b6)

https://github.com/lanous/db/assets/158297225/b46a739c-71df-4144-9490-9864989bbbe2

## Config Errors
type: ``Config``
- **ERR_CGCLSIC**: Occurs when your configuration structure is incorrect (e.g., some constants are not defined or have version-related issues).
## Initialization Errors
type: ``init``
- **ERR_CNCTERR**: Indicates that the database connection was unsuccessful.
## Jobs error
- **ERR_RECOVERY**: Encountered during data recovery.
- **ERR_NOCHANGE**: Everything is correct, but data in one of the columns hasn't changed.
- **ERR_EXPERROR**: Error during row editing.
- **ERR_DUPLICATE**: Duplicate data received and stored in another variable.
- **ERR_CANTFIND**: The requested data does not exist.
## Non-Support Errors
- **ERR_DBSM**: You are using a DBSM that is not defined in the project.
## Structure Errors
- **ERR_TABLEND**: The chosen name is not a class, or the class is not defined correctly (e.g., incorrect class location or missing namespaces).
- **ERR_ORDERDC**: Typically occurs during ordering (ASC/DESC) operations.
- **ERR_NMESPCE**: The configured project name as a namespace does not match file and class names.
- **ERR_VLDDTYP**: Issued by DataType when the data provided is not acceptable (e.g., passing a string instead of an array for ArrayData).
- **ERR_CLUMNND**: The column you're looking for does not exist. Use constants for column names to avoid typing mistakes.
- **ERR_CLASSNF**: The specified data type does not exist. Ensure file and class names match and implement \Lanous\db\Structure\DataType.
- **ERR_MPLEPKY**: You cannot define two or more columns as primary key, primary key is only one key!
- **ERR_DTYPEIC**: Incorrect data type structure.
- **ERR_NOTENUM**: When using ENUM in a table class, the parameter provided is not an ENUM.
- **ERR_IFEINPR**: Parameter conflict during input (e.g., using both primary_value and where simultaneously).
- **ERR_PKNOTST**: Primary Key not set (usually when setting a data parameter as a primary key)
- **ERR_RFRNCNF**: Related to Foreign keys (further explanation to follow).

## How to Handle Errors
```php
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
        // If the error code is $error::ERR_RECOVERY, it means that the data recovery has encountered an error
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
```
## Examples of errors occurring
### ERR_CGCLSIC
```php
class LanousConfig {
    // const hostname = '127.0.0.1';
    ...
}
```
> [!CAUTION]
>  PHP Fatal error:  Uncaught Lanous\db\Exceptions\Config: The configuration structure has not been written correctly;
>      please review the documentation carefully [$e::ERR_CGCLSIC]
### ERR_CNCTERR
```php
class LanousConfig {
    ...
    const username = 'incorrect_username';
    const password = 'incorrect_password';
    ...
}
```
> [!CAUTION]
>  PHP Fatal error:  Uncaught Lanous\db\Exceptions\init: The database connection encountered an issue;
>      the configuration information (including host, username, password, and database name) is incorrect! [$e::ERR_CNCTERR]
### ERR_CLUMNND
```php
$Table->Insert()
    ->Set("unknown_column","Mohammad")
->Push();
```
> [!CAUTION]
> PHP Fatal error:  Uncaught Lanous\db\Exceptions\Structure: The column you intend to work on is undefined and unknown in the data table class. [$e::ERR_CLUMNND]

# utility methods
One of the advantages of using the Lanous project is the existence of useful methods. These methods are separate from the main project topic but contribute to project improvement and enhance the clarity of your code.

In the following section, we will introduce these useful functions:

## Jobs
This method is used for data storage and recovery in case of errors. When editing user information, if an error occurs during any of the processes, the data will revert to their previous state. For example, imagine you are exchanging a financial unit between two users. Let’s say you want to transfer $5000 between two users. Subtract $5000 from the first user’s account and add $5000 to the second user’s wallet. If, after deducting from the first user’s account, the process of adding to the second user’s wallet encounters an error, the first user loses their balance, and the second user does not receive the funds. What is the solution to this problem?

```php
Connect -> function NewJob () : Jobs\Job
```

### Example of Financial Transfer Between Two Users (with Full Details)
```php
try {

    $Job = $database->NewJob();
    $Job->Sensitivity(level: 3);

    $Wallet1 = $Job->Get(MyLanous\Tables\Wallet::class,1);
    $USD1 = $Wallet1->data['usd']->value;
    $Wallet2 = $Job->Get(MyLanous\Tables\Wallet::class,2);
    $USD2 = $Wallet2->data['usd']->value;

    if ($USD2 < 5000)
        throw new \Exception("The balance of the first user is insufficient for the transfer",100);

    $Job->Edit($Wallet1,"usd",$USD1 + 5000);
    $Job->Edit($Wallet2,"usd",$USD2 - 5000);
    
} catch (\Lanous\db\Exceptions\Jobs $error) {

    if ($error->getCode() == $error::ERR_RECOVERY) {
        // -- Be sure to specify this case in the catch --
        // If the error code is ERR_RECOVERY, it means that the data recovery has encountered an error
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

} catch (\Exception $e) {
    if ($e->getCode() == 100) {
        echo ("Your inventory is insufficient.");
    }
}
```

The try block is divided into four sections.

The **first section** pertains to the **configuration of the Job**.

It contains sub-methods related to configuration.
One of these sub-methods is ``Sensitivity``:

```php
function Sensitivity($level=3)
```

which sets the sensitivity level for this job.
- Levels:
    - Level 1: If none of the columns change, the data is retrieved.
    - Level 2: If there are issues during changes, the data is retrieved.
    - Level 3: Simultaneous use of both Level 1 and Level 2.

The **secound section** pertains to the **Fetching User Data**.

This is done through the ``Get`` method.

```php
function Get ($table_class,$primary_value) : object
```

The first parameter specifies the class name of the data table, and the second parameter provides the primary value for the data table.

> [!NOTE]
> The table must have a primary key for it to be usable in the Job.

 
The **third section** pertains to the **project-specific exceptions**.

These exceptions can include checks on user balances, access levels, and other custom conditions.


The **fourth section** pertains to the **data editing**.

This is done through the ``Edit`` method.

```php
function Edit (object $row,string $key,$value)
```

The **first parameter** essentially corresponds to the **output of the Get** method. The **second parameter** represents the **key name** that you intend to edit, and the **third parameter** is the **value** you want to assign to that column.

The other aspects are automatically controlled.

The only manual action required is for handling ``ERR_RECOVERY``.

> [!CAUTION]
> This error occurs when editing one of the columns (in essence, one of the Edit functions) encounters an issue (based on sensitivity level). After data recovery, if the problem persists, the data is lost.

When this error occurs, a new property called ``data`` is added to the exception. This property contains all the recovered data (in case manual recovery is needed). You can save this data in a JSON file.

Consider the following example:

```php
...
} catch (\Lanous\db\Exceptions\Jobs $e) {
    if ($error->getCode() == $error::ERR_RECOVERY) {
        $recovery_data = $error->data;
        $ErrorDetails = $error->getMessage();
        $time = time();
        file_put_contents($time."_data_losted.json",json_encode($recovery_data,128|256));
        print("recovery error!");
        // Both users should be disabled in this section.
        // The file and $Error should be sent to the site administrator via email.
        unlink($time."_data_losted.json"); // After the data is sent to the email, delete the file
        // After manually recovering the data or resolving the issue, re-enable the users
    }
...
```
**{time}_data_losted.json**
```json
{
    "MyLanous\\Tables\\Wallet": {
        "1": {
            "user_id": {
                ...
                "value": 1
            },
            "usd": {
                ...
                "value": 160000
            }
        },
        "2": {
            "user_id": {
                ...
                "value": 2
            },
            "usd": {
                ...
                "value": 30000
            }
        }
    }
}
```

> [!TIP]
> If the table structures have been correctly created and the data in the tables do not have any issues, the likelihood of encountering this error is very low, so don’t worry.
> better safe than sorry :people_hugging:
