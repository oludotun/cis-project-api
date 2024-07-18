<?php

namespace App\Models;

use \PDO;
use PDOException;

/**
 * A user object that handle all database operations relating to Users
 *
 */
class User extends Model
{
    private ?int $id;
    private string $first_name;
    private string $last_name;
    private string $email;
    private string $password;
    private bool $is_admin;
    
    public function __construct(object $user = null)
    {
        if(isset($user)) {
            $this->id = isset($user->id)? $user->id : null;
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
            $this->email = $user->email;
            $this->password = $user->password;
            $this->is_admin = isset($user->is_admin)? $user->is_admin : false;
        }
    }

    /**
     * Return user detail as an object
     *
     * @param bool $includePassword If this is true the user's password
     * hash will be returned with the object
     * @return object
     */
    public function getObject(bool $includePassword = false) : object {
        $user = (object)[];
        $user->id = $this->id;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->is_admin = $this->is_admin;
        if($includePassword) $user->password = $this->password;
        return $user;
    }

    /**
     * Store new user's details to the database
     *
     * @return  int The new user's id or 0 when operation fails
     */
    public function save() : int {
        $sql = "INSERT INTO user (first_name, last_name, email, password)
                VALUES (:first_name, :last_name, :email, :password)";
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([
                "first_name" => $this->first_name,
                "last_name" => $this->last_name,
                "email" => $this->email,
                "password" => $this->password
            ]);
            $id = $dbConnection->lastInsertId();
            $db = null;
            return intval($id);
        } catch (PDOException $e) {
            $this->logError($e);
            return 0;
        }
    }

    /**
     * Check if user already exist in the database
     * 
     * @return  bool Returns true if the user is found in the database.
     */
    public function exists() : bool 
    {
        $sql = "SELECT COUNT(*) AS user_count
                FROM user
                WHERE email = :email;
                ";
        $data = ['email' => $this->email];
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($data);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            if($result->user_count > 0) return true;
            return false;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    /**
     * Find user by the supplied email
     * 
     * @param string $email User email to search for in the database
     * @return  User|false Returns a User object if user exists or false.
     */
    public function findByEmail(string $email) : User|false
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        $data = ['email' => $email];
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($data);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if(!$result) return false;
            $db = null;
            $user = new User($result);
            return $user;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }
}