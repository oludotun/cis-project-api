-- Seed data for user
INSERT INTO user (first_name, last_name, email, password, is_admin, picture, email_verified_at, created_at, last_login_at)
VALUES 
('John', 'Doe', 'john.doe@example.com', '$2y$10$kDAQHwqE5djMGhxtWk3P4uXwnRL4iT.2FGbl30CTnhIuZjQVHeao6', TRUE, NULL, NULL, CURRENT_TIMESTAMP, NULL),
('Jane', 'Smith', 'jane.smith@example.com', '$2y$10$kDAQHwqE5djMGhxtWk3P4uXwnRL4iT.2FGbl30CTnhIuZjQVHeao6', FALSE, NULL, NULL, CURRENT_TIMESTAMP, NULL),
('Alice', 'Johnson', 'alice.johnson@example.com', '$2y$10$kDAQHwqE5djMGhxtWk3P4uXwnRL4iT.2FGbl30CTnhIuZjQVHeao6', FALSE, NULL, NULL, CURRENT_TIMESTAMP, NULL),
('Bob', 'Brown', 'bob.brown@example.com', '$2y$10$kDAQHwqE5djMGhxtWk3P4uXwnRL4iT.2FGbl30CTnhIuZjQVHeao6', FALSE, NULL, NULL, CURRENT_TIMESTAMP, NULL),
('Charlie', 'Davis', 'charlie.davis@example.com', '$2y$10$kDAQHwqE5djMGhxtWk3P4uXwnRL4iT.2FGbl30CTnhIuZjQVHeao6', FALSE, NULL, NULL, CURRENT_TIMESTAMP, NULL);

-- Seed data for student
INSERT INTO student (user_id)
VALUES 
(2),
(3),
(4),
(5);

-- Seed data for instructor
INSERT INTO instructor (user_id)
VALUES 
(1);

-- Seed data for course
INSERT INTO course (title, about, created_at, updated_at)
VALUES 
('Introduction to Electrical Engineering', 'Basics of Electrical Engineering concepts.', CURRENT_TIMESTAMP, NULL),
('Advanced Software Development', 'In-depth software development practices.', CURRENT_TIMESTAMP, NULL),
('Machine Learning 101', 'Introduction to machine learning principles.', CURRENT_TIMESTAMP, NULL),
('Data Structures and Algorithms', 'Fundamentals of data structures and algorithms.', CURRENT_TIMESTAMP, NULL),
('Circuit Analysis', 'Understanding the principles of circuit analysis.', CURRENT_TIMESTAMP, NULL);

-- Seed data for lab
INSERT INTO lab (course_id, title, answer, description, created_at, updated_at)
VALUES 
(1, 'Ohm\'s Law Lab', '{"correct_answer": "V=IR"}', 'Lab to understand Ohm\'s Law.', CURRENT_TIMESTAMP, NULL),
(2, 'Version Control Lab', '{"correct_answer": "GitHub"}', 'Lab to practice version control with Git.', CURRENT_TIMESTAMP, NULL),
(3, 'Linear Regression Lab', '{"correct_answer": "y=mx+b"}', 'Lab to implement linear regression.', CURRENT_TIMESTAMP, NULL),
(4, 'Sorting Algorithms Lab', '{"correct_answer": "QuickSort"}', 'Lab to learn about sorting algorithms.', CURRENT_TIMESTAMP, NULL),
(5, 'AC Circuit Analysis Lab', '{"correct_answer": "Impedance"}', 'Lab to analyze AC circuits.', CURRENT_TIMESTAMP, NULL);

-- Seed data for student_course
INSERT INTO student_course (student_id, course_id, enrolled_at)
VALUES 
(1, 1, CURRENT_TIMESTAMP),
(2, 2, CURRENT_TIMESTAMP),
(3, 3, CURRENT_TIMESTAMP),
(4, 4, CURRENT_TIMESTAMP),
(1, 5, CURRENT_TIMESTAMP);

-- Seed data for instructor_course
INSERT INTO instructor_course (instructor_id, course_id, assigned_at)
VALUES 
(1, 1, CURRENT_TIMESTAMP),
(1, 2, CURRENT_TIMESTAMP),
(1, 3, CURRENT_TIMESTAMP),
(1, 4, CURRENT_TIMESTAMP),
(1, 5, CURRENT_TIMESTAMP);

-- Seed data for student_lab
INSERT INTO student_lab (student_id, course_id, lab_id, started_at, submission, score, updated_at)
VALUES 
(1, 1, 1, CURRENT_TIMESTAMP, '{"answer": "V=IR"}', 100, NULL),
(2, 2, 2, CURRENT_TIMESTAMP, '{"answer": "GitHub"}', 95, NULL),
(3, 3, 3, CURRENT_TIMESTAMP, '{"answer": "y=mx+b"}', 90, NULL),
(4, 4, 4, CURRENT_TIMESTAMP, '{"answer": "QuickSort"}', 85, NULL),
(1, 5, 5, CURRENT_TIMESTAMP, '{"answer": "Impedance"}', 80, NULL);
