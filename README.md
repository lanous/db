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
$UserData = $User->LastRow(\Lanous\db\Table\RowReturn::Values);
return $UserData[1];
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
