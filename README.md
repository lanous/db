# Introduction

The primary objective of this project is to seamlessly interact with various database systems without the need for in-depth knowledge of their intricate structures and complex commands. Its inherent flexibility empowers developers, and its potential for further enhancement is substantial. These accompanying documents cater specifically to users of this project, while development-related documentation resides within the ``src`` directory.

# Navigating Complexity

At first glance, the project’s initial complexity might deter some, leading them to question its suitability for open-source endeavors. However, let us delve into the reasons why this library can be an invaluable asset, especially for personal projects:

**Automatic Data Encryption**: The project handles data encryption seamlessly, ensuring that sensitive information remains secure.

**Extensible Data Type Structures**: The data type structures are designed to be extensible, accommodating diverse use cases.

**Abstraction from Database Complexity**: By shielding developers from the intricacies of database management, this project simplifies interactions.

**Scalability**: The architecture is inherently scalable, allowing seamless growth as your project evolves.


In summary, while the initial learning curve may be challenging, the rewards of using this project far outweigh the effort invested. Whether you’re embarking on a personal project or contributing to open-source initiatives, consider leveraging this powerful tool to streamline your database interactions.

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

The elegance of this project lies in its adherence to object-oriented principles. As you’ve astutely observed, everything is meticulously organized within class structures. Specifically, your data tables should be encapsulated within well-defined class hierarchies, following established rules and principles. Let’s delve into the key aspects:

1. **Class-Based Data Tables**: Each data table corresponds to a class. This encapsulation ensures that data remains neatly compartmentalized, enhancing readability and maintainability.
2. **Principled Design**: Define your classes with care. Consider the relationships between tables, inheritance hierarchies, and the appropriate access modifiers. A thoughtful design ensures efficient data manipulation.
3. **Effective Data Handling**: By leveraging class methods, you can perform data operations seamlessly. Whether it’s querying, updating, or inserting records, encapsulating these actions within class methods promotes consistency and reduces redundancy.
4. **Abstraction and Encapsulation**: Hide implementation details behind well-defined interfaces. This abstraction shields the rest of your project from the intricacies of data handling, promoting modularity.
5. **Data Integrity and Validation**: Implement validation checks within your class methods. Ensure that data adheres to predefined rules before it enters the database. This prevents erroneous entries and maintains data integrity.
 
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
## General
### Database
A database serves as a structured repository for a collection of data. It provides accessibility and editability, acting as a vital foundation for various applications and systems.
### Database Management System (DBSM)
A Database Management System (DBMS) is sophisticated software equipped with a suite of tools for efficiently handling database data. It serves as the bridge between applications and the underlying database, facilitating seamless communication and interaction.
### Tables: Organized Data Repositories
Data within a database is systematically organized into tables. Each table represents a distinct category of related information. For instance, consider the “user information” table, which houses essential identity details such as names, surnames, and birthdates. This structured approach ensures that data finds its rightful place within the system.
### Columns: The Essence of Data
Columns lie at the heart of data representation. They define the nature of the information stored. Within a table, columns hold specific data points. For example, the “last name” column neatly captures users’ surnames. Each column boasts unique attributes, including data type, size, constraints, and other relevant properties.
##### A. Primary Columns: Uniqueness and Identification
When a column assumes the role of a primary key, it becomes a distinctive identifier—a digital fingerprint—for individual data rows. By enforcing uniqueness, it prevents duplicate values within that column. Typically, the primary key column bears the name “id.” Throughout the program, this ID serves as the universal means of identifying users.
##### B. Foreign Key Columns: Building Relationships
A foreign key establishes a crucial relationship between tables. By referencing the primary key of another table, it ensures data consistency. This linkage allows data to flow seamlessly across related tables, fostering integrity and coherence.

# Setting Up the Lanous\db Package: A Step-by-Step Guide
## 1. Installation via Composer
Begin by installing the Lanous\db package using Composer.

```shell
composer require lanous\db
```
## 2. Autoloading
Once the installation is complete, it’s time to seamlessly integrate the package into your project.

in your main project file (often named ``index.php``), include the Composer-generated autoload.php. This file acts as the gateway, ensuring that all necessary classes and dependencies are loaded:

```php
<?php
// index.php

include("vendor/autoload.php");

use Lanous\db as Database;

// Your project logic begins here...
// Initialize the Lanous\db package and start interacting with your database.

// Example:
$database = new Database();
// Perform database operations using $database...

?>
```
## Embrace the Power of Lanous\db
With the package seamlessly integrated, you’re now equipped to harness the capabilities of Lanous\db. Whether you’re querying, updating, or managing data, let this elegant solution simplify your database interactions.

Remember, elegance lies not only in functionality but also in the meticulous steps taken to achieve it. Happy coding!

## Setting Up Database Connectivity: A Pragmatic Approach
Once your project is successfully loaded, the next crucial step is establishing a seamless connection to the database. This pivotal task is accomplished through the Connect class within your project. However, before diving into the connection process, let’s lay the groundwork by creating a robust configuration class

## The Config Class: Constants for Clarity
Your Config class serves as the cornerstone of your database setup.

It encapsulates essential constants that dictate how your project interacts with the database. These constants include:

``hostname``: The database server’s address (e.g., '127.0.0.1').

``username``: The authorized database user (e.g., 'root').

``password``: The secret key granting access.

``database``: The specific database name to connect to (e.g., 'lanous').

``dbsm``: The Database Management System (DBMS) in use (e.g., 'mysql').

``project_name``: A descriptive identifier for your project (e.g., 'MyLanous').

``project_dir``: The absolute path to your project directory (automatically determined).

Here’s an illustrative example of a well-structured LanousConfig class:
```php
class LanousConfig {
    const hostname = '127.0.0.1';
    const username = 'root';
    const password = '';
    const database = 'lanous';
    const dbsm = 'mysql';
    const project_name = 'MyLanous';
    const project_dir = __DIR__;
}
```
### Leveraging Library Constants: Clarity and Safety
To configure the DBMS effectively, utilize library constants. These constants not only prevent typographical errors but also serve as informative markers for supported DBMS options. Consider this approach:
```php
    // ...
    const dbsm = Database\Lanous::DBSM_Mysql; // Choose the appropriate constant
    // ...
```

## Initiating the Connection
With your configuration class in place, it’s time to forge the connection:
```php
$database = new Database\Connect(new LanousConfig);
```
And just like that, your project gains access to the database, ready to perform its data magic.

## Final code

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

Upon the initial execution, a directory is meticulously crafted within the configured folder (denoted by project_dir). This directory bears the name specified in the configuration class (project_name).

Now, let’s delve into the essential steps for examining each of these directories:

## Data Table Structure: The Heart of Your Project

The ‘Tables’ folder serves as the repository for your data table structures. Within this hallowed directory, filenames play a pivotal role—they essentially mirror the class and table names. Therefore, consistency across all three components—filename, class name, and table name—is paramount.

To create a new data table, follow this precise procedure:

Namespace Formation: Establish a namespace that aligns with your project name. This namespace encapsulates the essence of your data table structure.
Inheritance ...: Inherit from the ``\Lanous\db\Structure\Table`` class. This inheritance lays the foundation for your data table, imbuing it with essential properties and methods.

As you craft your data tables, let clarity and consistency be your guiding stars. :star:

### Creating Data Table Structures: A Methodical Approach
Behold the general structure:

#### General Structure
```php
namespace {project_name}\Tables;

class {table_name} extends \Lanous\db\Structure\Table {

    // Your table-specific code resides here...

}
```
#### Completing the Table Structure: Adding Columns
With the foundation laid, it’s time to breathe life into your data table. Columns play a starring role, and we invoke functions from the parent class to define their essence.

Let’s explore the functions related to columns:

```php
AddColumn(string $name)
```
This function specifies the **name of the column**. Within its realm, several sub-methods come into play:

- ``DataType($object)``: Determines the data type (refer to the list of supported data types in DataTypes\x.php).
- ``Size($size)``: Sets the data type size.
- ``AutoIncrement(bool $value)``: Enables auto-increment if set to true.
- ``Enum($class)``: Establishes an enum as the column’s value.
- ``Constraints(bool $Primary=false, bool $not_null=false, bool $UNIQUE=false, $default=false, $check=false)``: Configures constraints for the column.

```php
Injection($column_name)
```
Before data enters the database, it passes through this function. Sub-methods include:

- ``Evaluation(callable $callback)``: Validates data.
- ``Edit(callable $callback)``: Allows data editing via a callable.

```php
Extract($column_name)
```
After data emerges from the database, it undergoes further processing. Sub-methods include:

- ``Evaluation(callable $callback)``: Validates retrieved data.
- ``Edit(callable $callback)``: Facilitates data editing using a callable.

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

## Project Folder Structure and Data Type Definitions

Upon the initiation of project directories, a supplementary folder designated as DataTypes is autonomously generated. This folder serves as a repository for all requisite data types pertinent to the column definitions. These data types are instantiated from the ``\Lanous\db\Structure\DataType`` interface.

DataType Class Essentials

Constructor (``__construct``): This function is invoked during the instantiation of the DataType class, receiving the initial data payload. Should there be a necessity to augment the data type, it is advisable to preserve it within a private attribute.

Data Injection (``Injection``): Prior to the database assimilation of data (be it via INSERT or UPDATE operations), the data is processed through this function. The resultant output is then allocated as the column’s value. In scenarios where the data remains unaltered, it is imperative to employ ``return $data;``.

Data Extraction (``Extraction``): Subsequent to data retrieval and antecedent to user reception, the data is channeled through this function, with the output being conveyed to the user. Analogous to the previous function, should there be no requisite modifications, the utilization of ``return $data;`` is essential.

Data Validation (``Validation``): This function serves as a gatekeeper, precluding the entry of specific data into the database. It is customarily invoked; the deployment of exceptions is recommended for optimal efficacy.

Furthermore, any function delineated beyond the aforementioned is accessible within the output data through the methods property. Correspondingly, any attribute appended to this class is attainable in conjunction with the principal output (value, methods).

Illustrative Example
```php
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
        return json_decode($data, true);
    }

    public function Validation($data): bool {
        return is_array($data);
    }
}
```
This exemplar delineates a data type that designates a column as conforming to the JSON type (const Query = "JSON";). Given that PHP arrays are not natively storable within tables, this class encodes the incoming data into JSON format and decodes the retrieved data accordingly.

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
> but better safe than sorry :people_hugging:

## Callback
Edit all data via callable You can retrieve and edit data using the callback method.

Your callback function should accept two parameters: the column name (parameter one) and the column value (parameter two) sent to you. The output of your function will be set as the value.

```php
RowReturn : function Callback (callable $callback)
```

Examples:

```php
$Users = $database->OpenTable (MyLanous\Tables\Users::class);
$UserID1 = $Users->Select(column: "*")->Extract(primary_value: 1);
$UserID1->Callback(function ($column_name,$value) {
    echo "Column name: $column_name - Column value: $value".PHP_EOL;
});
/*
Column name: id - Column value: 1
Column name: first_name - Column value: mohammad
Column name: last_name - Column value: azad
...
*/
```

> [!NOTE]
> data that changes via the callback is only applied locally and is not sent to the database

```php
$Users = $database->OpenTable (MyLanous\Tables\Users::class);
$UserID1 = $Users->Select(column: "*")->Extract(primary_value: 1);
$callback_test = $UserID1->Callback(function ($column_name,$value) {
    if ($column_name == "first_name"){
        return "new name";
    }
});
$callback_test->FirstRow(RowReturn::ArrayType);
/*
array(4) {
  ["id"]=> int(1)
  ["first_name"]=> string(8) "new name"
  ["last_name"]=> string(4) "azad"
  ["password"]=> string(0) ""
}
*/
```

```php
$Users = $database->OpenTable (MyLanous\Tables\Users::class);
$UserID1 = $Users->Select(column: "*")->Extract(primary_value: 1);
$callback_test = $UserID1->Callback(function ($column_name,$value) {
    if ($column_name == "first_name"){
        return "new name";
    }
});
$callback_in_callback = $callback_test->Callback(function ($column_name,$value) {
    if ($column_name == "last_name"){
        return "new last name";
    }
});

$callback_in_callback->FirstRow();
/*
array(4) {
  ["id"]=> int(1)
  ["first_name"]=> string(8) "new name"
  ["last_name"]=> string(4) "new last name"
  ["password"]=> string(0) ""
}
*/
$callback_test->FirstRow();
/*
array(4) {
  ["id"]=> int(1)
  ["first_name"]=> string(8) "new name"
  ["last_name"]=> string(4) "azad"
  ["password"]=> string(0) ""
}
*/
```

## Foreign Key | Parent-Child tables

By using Foreign keys and configuring them, you can link data from one column to another, creating data dependencies. For example, you can store user identity information in a table and link other tables (such as access levels, transactions, etc.) to this identity table. In essence, the identity table and the linked tables are recognized as parent-child relationships.

```php
$database
    ->Setting # Open Settings
        ->Table(MyLanous\Tables\Wallet::class) # Navigate to the Wallet table settings.
            ->FOREIGN_KEY("user_id",MyLanous\Tables\Users::class,"id");
            # Link the user_id column (associated with the wallet table) to the id column of the Users table.
# The Users table is considered the parent in this relationship.
```

Now, during data retrieval, extract data from another table using the Parent and Child methods

```php
RowReturn : function Child ($table_class) : RowReturn
```
```php
RowReturn : function Parent() : RowReturn
```

When you are **working with the Users table**, you can access the Wallet table through the **Child** method.

And when you are **working with the Wallet table**, you can access the Users table through the Parent method.


```php
$Wallet = $database->OpenTable (MyLanous\Tables\Wallet::class);
$UserID1 = $Wallet->Select(column: "*")->Extract(primary_value: 1);
$Users = $UserID1->Parent()->LastRow();


$Users = $database->OpenTable (MyLanous\Tables\Users::class);
$UserID1 = $Users->Select(column: "*")->Extract(primary_value: 1);
$Wallet = $UserID1->Child(MyLanous\Tables\Wallet::class)->LastRow();
```

## Plugins

Plugins are classes to which you can send data and create functions that perform processing outside the main part of your project. This approach can prevent redundancy in project components and also make your project more modular.

When you run the project, a folder named Plugins is automatically created in your project directory. The files in this folder are executed automatically, and the database is passed to it (no need for configuration or explicit inclusion).

You are not allowed to create a constructor for this class after inheriting from it. After inheritance, you have access to three functions:
```php
OpenTable(string $table_class) : Table
```
Used to open a table and perform processing within it.

```php
Call(string $plugin_class, $data=null)
```
Used to include another plugin.

```php
NewJob() : Job
```
Used to create a new job.

Plugin classes must inherit from ``\Lanous\db\Structure\Plugins``

Consider the following example:

```php
<?php
#location: {project_name}/Plugins/Test.php

namespace MyLanous\Plugins;

class Test extends \Lanous\db\Structure\Plugins {
    public function GetName ($id) {
        $Users = $this->OpenTable(\MyLanous\Tables\Users::class);
        $Find = $Users->Where("id","=",$id);
        $User = $Users->Select("*")->Extract($Find)->LastRow();
        return $User['first_name'];
    }
}
```

In your project files, you use the ``LoadPlugin`` method to load plugins. The method signature is as follows:
```php
function LoadPlugin(string $plugin_class, mixed $data=null) : {plugin_class}
```

In the **first parameter**, pass the **name of the plugin class**.
In the **second parameter**, if necessary, you can provide **additional data**.
The data within the plugin class can be accessed via ``$this->data``

```php
<?php
#location: index.php

$UsersPlugin = $database->LoadPlugin(MyLanous\Plugins\Test::class);

echo "Hi ".$UsersPlugin->GetName (1);
# Hi mohammad
```
