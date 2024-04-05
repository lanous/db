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
