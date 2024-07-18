<?php

namespace App\Controllers;

use App\Config\Settings;
use App\Exceptions\HttpConflictException;
use App\Exceptions\HttpUnprocessableEntityException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use App\Models\User;
use DateTime;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;

class UserController extends Controller
{
    /**
     * Handles new user's registration requests
     *
     * @param Request $request Incoming request object
     * @param Response $response Outgoing response object
     * @return Response Processed Outgoing response object
     * 
     */
    public function register(Request $request, Response $response)
    {
        // Parse request data
        $data = $request->getParsedBody();

        // Validate input data
        //TODO: add more validation logic and return JSON
        if (empty($data['password']) || empty($data['email'])) {
            throw new HttpUnprocessableEntityException($request, 'Cannot create user, Invalid input data');
        }
        
        // Hash user's password before storage
        $plainPassword = $data['password'];
        $data['password'] = password_hash($plainPassword, PASSWORD_DEFAULT);
        $user = new User((object)$data);
        
        // Check if user already exists
        $userExists = $user->exists();
        if ($userExists) {
            throw new HttpConflictException($request, 'Cannot create user. User already exists.');
        }

        //save user to database
        $userId = $user->save();

        // Return error if database operation failed
        if(!$userId) {
            throw new HttpInternalServerErrorException($request);
        }

        // Return a success message
        $payload = json_encode([
            'success' => [
                'message' => 'User created successfully.',
                'userId' => $userId
            ]
        ]);
        $response->getBody()->write($payload);
        return $response->withStatus(200);
    }

    /**
     * Handles user login requests
     *
     * @param Request $request Incoming request object
     * @param Response $response Outgoing response object
     * @return Response Processed Outgoing response object
     * 
     */
    public function login(Request $request, Response $response)
    {
        // Parse request data
        $data = $request->getParsedBody();

        // Validate input data
        if (!$this->validateString($data['password']) || !$this->validateEmail($data['email'])) {
            throw new HttpUnprocessableEntityException($request, 'Could not process your request, invalid email or password format');
        }

        // Find user in the database
        $user = (new User())->findByEmail($data['email']);
        if(!$user) {
            throw new HttpUnauthorizedException($request, 'Incorrect email or password');
        }

        $user = $user->getObject(true);
        $hash = $user->password;
        $user->password = null;
        if(password_verify($data['password'], $hash)) {
            $key = Settings::get('app', 'secret_key');
            $payload = [
                'id' => $user->id,
                'iat' => (new DateTime())->getTimestamp()
            ];
            $jwt = JWT::encode($payload, $key, 'HS256');
            $user->token = $jwt;
            $response->getBody()->write(json_encode(['user' => $user]));
            return $response->withStatus(200);
        }

        throw new HttpUnauthorizedException($request, 'Incorrect email or password');
    }
}