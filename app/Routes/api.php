<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

use App\Controllers\PingController;
use App\Controllers\UserController;

return function (App $app) {
    $app->group('/api', function (RouteCollectorProxy $group) {
        // Test Endpoint
        $group->get('/ping', [PingController::class, 'ping']);

        // Authentication Endpoints
        $group->group('/auth', function (RouteCollectorProxy $auth) {
            $auth->post('/login', [UserController::class, 'login']);
        });
        
        // User Endpoints
        $group->group('/users', function (RouteCollectorProxy $user) {
            $user->post('', [UserController::class, 'register']);
            $user->get('/{id}', [UserController::class, 'getById']);
        });

        // Student Endpoints
        $group->group('/students', function (RouteCollectorProxy $student) {
            $student->get('/{id}', [StudentController::class, 'getById']);
            $student->post('', [StudentController::class, 'create']);
        });

        // Instructor Endpoints
        $group->group('/instructors', function (RouteCollectorProxy $instructor) {
            $instructor->get('/{id}', [InstructorController::class, 'getById']);
            $instructor->post('', [InstructorController::class, 'create']);
        });

        // Course Endpoints
        $group->group('/courses', function (RouteCollectorProxy $course) {
            $course->get('', [CourseController::class, 'getAll']);
            $course->get('/{id}', [CourseController::class, 'getById']);
            $course->post('', [CourseController::class, 'create']);
            $course->put('/{id}', [CourseController::class, 'update']);
            $course->delete('/{id}', [CourseController::class, 'delete']);
        });

        // Lab Endpoints
        $group->group('/labs', function (RouteCollectorProxy $lab) {
            $lab->get('', [LabController::class, 'getAll']);
            $lab->get('/{id}', [LabController::class, 'getById']);
            $lab->post('', [LabController::class, 'create']);
            $lab->put('/{id}', [LabController::class, 'update']);
            $lab->delete('/{id}', [LabController::class, 'delete']);
        });

        // Student Course Enrollment Endpoints
        $group->group('/student-courses', function (RouteCollectorProxy $studentCourse) {
            $studentCourse->get('/{id}', [StudentCourseController::class, 'getById']);
            $studentCourse->post('', [StudentCourseController::class, 'create']);
            $studentCourse->put('/{id}', [StudentCourseController::class, 'update']);
            $studentCourse->delete('/{id}', [StudentCourseController::class, 'delete']);
        });

        // Instructor Course Assignment Endpoints
        $group->group('/instructor-courses', function (RouteCollectorProxy $instructorCourse) {
            $instructorCourse->get('/{id}', [InstructorCourseController::class, 'getById']);
            $instructorCourse->post('', [InstructorCourseController::class, 'create']);
            $instructorCourse->put('/{id}', [InstructorCourseController::class, 'update']);
            $instructorCourse->delete('/{id}', [InstructorCourseController::class, 'delete']);
        });

        // Student Lab Activity Endpoints
        $group->group('/student-labs', function (RouteCollectorProxy $studentLab) {
            $studentLab->get('/{id}', [StudentLabController::class, 'getById']);
            $studentLab->post('', [StudentLabController::class, 'create']);
            $studentLab->put('/{id}', [StudentLabController::class, 'update']);
            $studentLab->delete('/{id}', [StudentLabController::class, 'delete']);
        });
    });
};