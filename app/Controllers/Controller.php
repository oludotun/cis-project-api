<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Provide features that us universal to all Controllers
 *
 */ 
abstract class Controller
{
    /**
     * General response for preflight check (options request)
     * 
     * @return  Response Response with 204 status
     */
    public function options(Request $request, Response $response)
    {
        return $response->withStatus(204);
    }

    /**
     * Check if email is valid
     * 
     * @param string $email
     * @return  string|false Returns the email if it's valid or false.
     */
    public function validateEmail(string $email): string|false {
        // Remove all illegal characters from email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // Validate e-mail
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) return $email;
        return false;
    }

    /**
     * Check if supplied value is a valid string
     * 
     * @param string $value
     * @return  string|false Returns the string if it's valid or false.
     */
    public function validateString(string $value): string|false {
        $value  = $this->inputFilter($value);
        if($value && is_string($value)) return $value; 
        return false;
    }

    private function inputFilter(string $value): string|false {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $empty = empty($value);
        if ($empty) return false;
        return $value;
    }
}