<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * Get details of currently logged in student
     *
     * @param Request $request Incoming request object
     * @param Response $response Outgoing response object
     * @return Response Processed Outgoing response object
     * 
     */
    public function getLoggedInStudent(Request $request, Response $response)
    {
        $userId = $request->getAttribute('userId');
        $student = new Student();
        $details = $student->getStudentByUserId((int)$userId);
        $payload = json_encode(['student' => $details]);
        $response->getBody()->write($payload);
        return $response->withStatus(200);
    }

    /**
     * Get courses for a specific student
     *
     * @param Request $request Incoming request object
     * @param Response $response Outgoing response object
     * @return Response Processed Outgoing response object
     * 
     */
    public function courses(Request $request, Response $response)
    {
        $userId = $request->getAttribute('userId');
        $student = new Student();
        $courses = $student->courses((int)$userId);
        $payload = json_encode(['courses' => $courses]);
        $response->getBody()->write($payload);
        return $response->withStatus(200);
    }

    /**
     * Get labs for a specific student
     *
     * @param Request $request Incoming request object
     * @param Response $response Outgoing response object
     * @return Response Processed Outgoing response object
     * 
     */
    public function labs(Request $request, Response $response)
    {
        $userId = $request->getAttribute('userId');
        $student = new Student();
        $labs = $student->labs((int)$userId);
        $payload = json_encode(['labs' => $labs]);
        $response->getBody()->write($payload);
        return $response->withStatus(200);
    }

    /**
     * Get a specific lab for a student
     *
     * @param Request $request Incoming request object
     * @param Response $response Outgoing response object
     * @return Response Processed Outgoing response object
     * 
     */
    public function getLabById(Request $request, Response $response, array $args)
    {
        $labId = (int)$args['labId'];
        $userId = $request->getAttribute('userId');
        $student = new Student();
        $lab = $student->getLabById($userId, $labId);
        $payload = json_encode(['lab' => $lab]);
        $response->getBody()->write($payload);
        return $response->withStatus(200);
    }

    /**
     * Get student lab stats
     *
     * @param Request $request Incoming request object
     * @param Response $response Outgoing response object
     * @return Response Processed Outgoing response object
     * 
     */
    public function stats(Request $request, Response $response, array $args)
    {
        $userId = $request->getAttribute('userId');
        $student = new Student();
        $stats = $student->stats((int)$userId);
        $payload = json_encode(['stats' => $stats]);
        $response->getBody()->write($payload);
        return $response->withStatus(200);
    }

    /**
     * Get a specific lab for a student
     *
     * @param Request $request Incoming request object
     * @param Response $response Outgoing response object
     * @return Response Processed Outgoing response object
     * 
     */
    public function updateLab(Request $request, Response $response, array $args)
    {
        // Parse request data
        $lab = $request->getParsedBody();
        $lab['lab_id'] = (int)$args['labId'];
        $lab['submission'] = json_encode($lab['submission']);
        $lab['saved_state'] = json_encode($lab['saved_state']);
        $userId = $request->getAttribute('userId');
        // TODO: Check if user owns the lab
        $student = new Student();
        $result = $student->updateLab((int)$userId, $lab);
        if(!$result) {
            // Return a success message
            $payload = json_encode([
                'error' => [
                    'message' => 'Update failed.'
                ]
            ]);
            $response->getBody()->write($payload);
            return $response->withStatus(500);
        }
        // Return a success message
        $payload = json_encode([
            'success' => [
                'message' => 'Updated Lab successfully.'
            ]
        ]);
        $response->getBody()->write($payload);
        return $response->withStatus(200);
    }
}