<?php

namespace MiniCli;

use \PDO;
use PDOException;
use App\Config\Settings;

class DbConnection
{
    private $host;
    private $user;
    private $password;
    private $dbname;

    public function __construct() 
    {
        $dbConfig = Settings::get('database');
        $this->host = $dbConfig['host'];
        $this->user = $dbConfig['username'];
        $this->password = $dbConfig['password'];
        $this->dbname = $dbConfig['name'];
    }

    public function dbHasData() : bool
    {
        try {
            $conn_str = "mysql:host=$this->host";
            $conn = new PDO($conn_str, $this->user, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT COUNT(DISTINCT `table_name`) AS `table_count` FROM `information_schema`.`columns` WHERE `table_schema` = :dbname";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['dbname' => $this->dbname]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $tableCount = $result->table_count;
            echo "The database '$this->dbname' has a total of '$tableCount' table(s) \n";
            return $tableCount > 0;
        } catch (PDOException $e) {
            echo "Could not check if database already has data, see reasons below ... \n";
            echo $e->getMessage() . "\n";
            return true;
        }
        return true;
    }

    private function createDb() : PDO 
    {
        try {
            $conn_str = "mysql:host=$this->host";
            $conn = new PDO($conn_str, $this->user, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE $this->dbname";
            $conn->exec($sql);
            echo "A database named '$this->dbname' was created successfully \n";
            return $this->connect();
        } catch (PDOException $e) {
            echo "Database creation failed, see reasons below ... \n";
            echo $e->getMessage() . "\n";
        }
    }

    public function connect() : PDO {
        try {
            $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
            $conn = new PDO($conn_str, $this->user, $this->password);
            $conn->exec("SET time_zone='+00:00';");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);

            // Register a shutdown function to release the connection when the application terminates
            register_shutdown_function(function () use ($conn) {
                $conn = null; // Release the connection
            });

            return $conn;
        } catch (PDOException $e) {
            if($e->getCode() === 1049) {
                // Data Base does not exist, create one
                echo "Could not find database named '$this->dbname' \n";
                // echo "Please create a database with the same name as set in the env.php file and run the command again \n";
                echo "Creating a new database ... \n";
                return $this->createDb();
            }
            // echo $e->getCode() . "\n";
            echo $e->getMessage() . "\n";
        }
    }
}