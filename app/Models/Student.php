<?php

namespace App\Models;

use \PDO;
use PDOException;

/**
 * A student object that handle all database operations relating to Students
 *
 */
class Student extends Model
{
    private ?int $id;
    private string $user_id;

    public function __construct(object $student = null)
    {
        if(isset($student)) {
            $this->id = isset($student->id)? $student->id : null;
            $this->user_id = $student->user_id;
        }
    }

    /**
     * Get student by user id
     * 
     * @param int $user_id User id of the student
     * @return object|false Returns an object containing the student's details
     * or false on failure.
     */
    public function getStudentByUserId(int $user_id)
    {
        $sql = "
            SELECT 
                user.id AS user_id,
                user.first_name,
                user.last_name,
                user.email,
                user.picture,
                student.id AS student_id
            FROM 
                student
            JOIN 
                user ON student.user_id = user.id
            WHERE 
                user.id = :user_id
        ";
        $data = ['user_id' => $user_id];
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($data);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return $result;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }


    /**
     * Get courses for a specific student
     * 
     * @param int $user_id User id of the specific student
     * @return  array|false Returns an array containing list of student's courses
     * or false on failure.
     */
    public function courses(int $user_id) : array|false
    {
        $sql = "
            SELECT
                student_course.id AS enrollment_id,
                student_course.student_id,
                course.id AS course_id,
                course.title AS course_title,
                course.about AS course_about,
                student_course.enrolled_at
            FROM
                student_course
            INNER JOIN student ON student_course.student_id = student.id
            INNER JOIN user ON student.user_id = user.id
            INNER JOIN course ON student_course.course_id = course.id
            WHERE
                user.id = :user_id
            ORDER BY
                course.title;
        ";
        $data = ['user_id' => $user_id];
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($data);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            return $result;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    /**
     * Get labs for a specific student
     * 
     * @param int $user_id User id of the specific student
     * @return  array|false Returns an array containing list of student's labs
     * or false on failure.
     */
    public function labs(int $user_id) : array|false
    {
        $sql = "
            SELECT
            student_lab.id AS student_lab_id,
            student_lab.student_id,
            course.id AS course_id,
            course.title AS course_title,
            course.about AS course_about,
            lab.id AS lab_id,
            lab.title AS lab_title,
            student_lab.started_at,
            student_lab.submission,
            student_lab.score,
            student_lab.saved_state,
            student_lab.updated_at
        FROM
            student_lab
        INNER JOIN student ON student_lab.student_id = student.id
        INNER JOIN user ON student.user_id = user.id
        INNER JOIN course ON student_lab.course_id = course.id
        INNER JOIN lab ON student_lab.lab_id = lab.id
        WHERE
            user.id = :user_id
        ORDER BY
            student_lab.started_at DESC;
        ";
        $data = ['user_id' => $user_id];
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($data);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            return $result;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    /**
     * Get lab stats for a specific student
     * 
     * @param int $user_id User id of the specific student
     * @return  object|false Returns an object containing student's labs stats
     * or false on failure.
     */
    public function stats(int $user_id) : object|false
    {
        $sql = "
            SELECT 
                u.id AS user_id,
                CONCAT(u.first_name, ' ', u.last_name) AS full_name,
                u.email,
                COUNT(DISTINCT sc.course_id) AS total_enrolled_courses,
                ROUND(
                    (SUM(CASE WHEN sl.score IS NOT NULL THEN 1 ELSE 0 END) / COUNT(sl.lab_id)) * 100,
                    2
                ) AS lab_progress_percentage,
                ROUND(AVG(sl.score), 2) AS average_performance,
                COUNT(DISTINCT l.id) AS total_enrolled_labs
            FROM 
                user u
                LEFT JOIN student s ON s.user_id = u.id
                LEFT JOIN student_course sc ON sc.student_id = s.id
                LEFT JOIN course c ON c.id = sc.course_id
                LEFT JOIN lab l ON l.course_id = c.id
                LEFT JOIN student_lab sl ON sl.lab_id = l.id AND sl.student_id = s.id
            WHERE 
                u.is_admin = FALSE 
                AND u.id = :user_id
            GROUP BY 
                u.id;
            ";
        $data = ['user_id' => $user_id];
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($data);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return $result;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    /**
     * Get a specific lab for a student
     * 
     * @param int $user_id User id of the specific student
     * @param int $lab_id Lab id of the specific lab
     * @return  array|false Returns an object containing the lab detail
     * or false on failure.
     */
    public function getLabById(int $user_id, int $lab_id) : object|false
    {
        $sql = "
            SELECT
                student_lab.id AS student_lab_id,
                student_lab.student_id,
                user.first_name,
                user.last_name,
                course.id AS course_id,
                course.title AS course_title,
                lab.id AS lab_id,
                lab.title AS lab_title,
                lab.initial_state,
                student_lab.started_at,
                student_lab.submission,
                student_lab.saved_state,
                student_lab.score,
                student_lab.updated_at
            FROM
                student_lab
            INNER JOIN student ON student_lab.student_id = student.id
            INNER JOIN user ON student.user_id = user.id
            INNER JOIN course ON student_lab.course_id = course.id
            INNER JOIN lab ON student_lab.lab_id = lab.id
            WHERE
                user.id = :user_id AND
                lab.id = :lab_id
            ORDER BY
                student_lab.started_at DESC;
        ";
        $data = ['user_id' => $user_id, 'lab_id' => $lab_id];
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($data);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return $result;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    /**
     * Update a specific lab for a student
     * 
     * @param array $lab details of the lab to be updated
     * @return  bool Returns true if successful 
     * otherwise return false
     */
    public function updateLab(int $user_id, array $lab)
    {
        $sql = "
            UPDATE student_lab
            JOIN student ON student_lab.student_id = student.id
            JOIN user ON student.user_id = user.id
            SET 
                student_lab.submission = :submission,
                student_lab.score = :score,
                student_lab.saved_state = :saved_state,
                student_lab.updated_at = CURRENT_TIMESTAMP
            WHERE
                user.id = :user_id AND
                student_lab.lab_id = :lab_id;
        ";
        $data = [
            'user_id' => $user_id, 
            'lab_id' => $lab['lab_id'],
            'score' => $lab['score'],
            'saved_state' => $lab['saved_state'],
            'submission' => $lab['submission']
        ];
        $db = new DbConnection();
        $dbConnection = $db->connect();
        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($data);
            $db = null;
            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }
}