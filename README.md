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

namespace MyLanous\Table;

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
