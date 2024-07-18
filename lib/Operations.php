<?php

namespace MiniCli;
use PDOException;
use App\Config\Settings;

class Operations
{
    public static function createTables()
    {
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $sql = file_get_contents(__DIR__ . '/../db/tables.sql');
            echo "Creating database tables ... \n";
            $dbConnection->exec($sql);
            echo "Database tables created successfully ... \n";
        } catch (PDOException $e) {
            echo "Table creation failed, see reasons below ... \n";
            echo $e->getMessage() . "\n";
        }
    }

    public static function seedDatabase()
    {
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $sql = file_get_contents(__DIR__ . '/../db/seed_data.sql');
            echo "Inserting test data into database tables ... \n";
            $dbConnection->exec($sql);
            echo "Test data inserted successfully ... \n";
        } catch (PDOException $e) {
            echo "Test data insertion failed, see reasons below ... \n";
            echo $e->getMessage() . "\n";
        }
    }

    public static function dropDb()
    {
        $db = new DbConnection();
        $dbConfig = Settings::get('database');
        $dbname = $dbConfig['name'];        
                
        if($db->dbHasData()) {
            // Database is exists and is not empty, please verify delete
            $response = explode(' ', readline("Are you sure you want to delete the database '$dbname' along with its data? [Yes/No]: "));
            if (strtolower($response[0]) != "yes") {
                exit("Database operation aborted ... \n");
            }
        }
        
        $dbConnection = $db->connect();
        try {
            $sql = "DROP DATABASE IF EXISTS $dbname;";
            echo "Dropping database $dbname ... \n";
            $dbConnection->exec($sql);
            echo "Database dropped successfully ... \n";
        } catch (PDOException $e) {
            echo "Failed to drop database, see reasons below ... \n";
            echo $e->getMessage() . "\n";
        }
    }
}