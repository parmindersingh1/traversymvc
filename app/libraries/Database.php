<?php

/*
 * PDO Database Class
 * connect to database
 * create prepared statements
 * Bind Values
 * Return rows and results
 */

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try {
            $this->dbh = new PDO ($dsn, $this->user, $this->pass, $options);
        }        // Catch any errors
        catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Prepare statement with query
    public function query(string $query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    // Bind Values
    public function bind(string $param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value) :
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value) :
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value) :
                    $type = PDO::PARAM_NULL;
                    break;
                default :
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultset(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get result single record as object
    public function single(): object
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }


    public function lastInsertId(): int
    {
        return $this->dbh->lastInsertId();
    }


    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }


    public function endTransaction()
    {
        return $this->dbh->commit();
    }


    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }
}