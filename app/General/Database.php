<?php

/**
 * BASIC PDO database class
 *
 * @copyright  2016 Tom Koggel
 * @version    Release: 1.8
 * @link       https://github.com/blacktoko/PDO-Databaseclass/blob/master/db.class.php
 */

namespace General;

use Symfony\Component\Yaml\Yaml;

class Database {

    /**
     * @var string Hostname
     **/
    private $host;

    /**
     * @var string Username
     **/
    private $user;

    /**
     * @var string Password
     **/
    private $pass;

    /**
     * @var string Databasename
     **/
    private $dbname;

    /**
     * @var \PDO Database handler
     **/
    private $dbh;

    /**
     * @var string Errorstring
     **/
    private $error;

    /**
     * @var string MySQL statement
     **/
    private $stmt;

    /**
     * @var Databases All databases from config
     */
    private $databases;

    /**
     * Construct the database connection.
     */
    public function __construct($name)
    {
        $this->databases = Yaml::parse(file_get_contents(dirname(dirname(__DIR__)) . '/config/databases.yml'));

        if ( ! array_key_exists($name, $this->databases)) {
            throw new \Exception('Unable to load database, it does not appears in the config file.');
        }

        $this->host = $this->databases[$name]['database_host'];
        $this->user = $this->databases[$name]['database_user'];
        $this->pass = $this->databases[$name]['database_password'];
        $this->dbname = $this->databases[$name]['database_name'];

        //Set the Database Source Name
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

        //Set the options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );

        //Cerate a new PDO instance
        try {
            $this->dbh = new PDO( $dsn, $this->user, $this->pass, $options);
        }
        //Catch any errors
        catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    /**
     * Prepare a query for execution
     * @param  string $query The to prepared query
     * @return Fills the stmt variable with the query
     */
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    /**
     * Bind the variable to the query for execution
     * @param  string $param The parameter named in the query
     * @param  mixed  $value The value for the parameter
     * @param  string $type  The type of the parameter
     * @return Adds the parameters to the stmt query
     */
    public function bind($param, $value, $type = null)
    {
        if(is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Executes the prepared and filled query
     * @return the query awnser object
     */
    public function execute()
    {
        return $this->stmt->execute();
    }

    /**
     * Fetch a set of results
     * @return array The resultset as an assoc array
     */
    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns one single value
     * @return array the value of the query
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns one single value but as an object
     * @return Object the value of the query
     */
    public function singleObject()
    {
        $this->execute();
        return $this->stmt->fetchObject();
    }

    /**
     * Returns the number of affected rows
     * @return Int number of rows affected
     */
    public function rowCount()
    {
        $this->execute();
        return $this->stmt->rowCount();
    }

    /**
     * Returns the last inserted id
     * @return INT Last inserted id
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * Begins a transaction for multiple updates
     */
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * Closes the transaction at the end of the querys
     */
    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    /**
     * Cancel the transaction on falure
     */
    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    /**
     * Returns the $stmt debug values
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}
?>
