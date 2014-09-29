<h1>Basic PDO Database class</h1>

<h2>Create connection</h2>
```php
require_once 'db.class.php';
$database = new Database('user', 'password', 'database', 'localhost');
```

<h2>Select one line from database</h2>
```php
$query = 'SELECT * FROM `table` WHERE `name`=:name LIMIT 1';

$database->query($query);
$database->bind(':name', 'John');

$objects = $database->single();
```

<h2>Select multiple lines from database</h2>
```php
$query = 'SELECT * FROM `table`';

$database->query($query);
$objects = $database->resultset();
```
<h2>Insert into database</h2>
```php
$query = '
  INSERT INTO
    `table`
    (`name`, `age`)
  VALUES
    (:name, :age)';
$database->query($query);
$database->bind(':name', 'John');
$database->bind(':age', 28);
$database->execute();
```
   
   
