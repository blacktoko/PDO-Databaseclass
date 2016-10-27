<h1>Basic PDO Database class</h1>
Simpel class for PDO beginners. The basic PDO features are Available in this class.

<h2>Setup Config</h2>
Fill the yaml file as the config example.
```yaml
name:
  database_host:      localhost
  database_name:      dbname
  database_user:      root
  database_password:  password
```
The name is the handle name to call the connection.

<h2>Setup</h2>
* Run composer update to fetch the yaml class.
* include the vendor/autoload.php in your php file.

<h2>New php classes and files</h2>
When you extend this project in your own. Update the psr-4 piece in the composer.json file.
After updating and giving the namespaces run ```composer dump-autoload``` to update the autoloader files.

<h2>Create connection</h2>
```php
$database = new Database('handlename');
```
The connection is setup according to the handle name given with the variables in the config file.

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
   
   
