<?php

namespace App\Models;

use \PDO;
use \App\Config\Settings;
use PDOException;

class DbConnection
{
    private $host;
    private $user;
    private $password;
    private $dbname;

    public function __construct() {
        $dbConfig = Settings::get('database');
        $this->host = $dbConfig['host'];
        $this->user = $dbConfig['username'];
        $this->password = $dbConfig['password'];
        $this->dbname = $dbConfig['name'];
    }

    public function connect() {
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
            // throw new HttpInternalServerErrorException($request, $e->getMessage());
            //TODO: Log error and throw Exception  
            return $e;          
        }
        
    }
}