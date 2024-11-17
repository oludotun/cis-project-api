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
INSERT INTO lab (course_id, title, initial_state, answer, description, created_at, updated_at)
VALUES 
(1, 'Ohm\'s Law Lab', '{"devices": [{"id": "device1", "type": "Contactor", "data": {"name": "Contactor", "ref": "k1"}, "position": {"x": 0, "y": 50}}, {"id": "device2", "type": "PowerSource", "data": {"value": "24", "type": "DC"}, "position": {"x": -200, "y": 200}}, {"id": "device3", "type": "Lamp", "data": {"name": "Lamp", "ref": "L1"}, "position": {"x": 200, "y": 200}}, {"id": "pushbutton1", "type": "PushButton", "data": {"defaultState": "N/O", "ref": "SW1"}, "position": {"x": -200, "y": 50}}]}', '{"correct_answer": "V=IR"}', 'Lab to understand Ohm\'s Law.', CURRENT_TIMESTAMP, NULL),
(2, 'Version Control Lab', '{"devices": [{"id": "device1", "type": "Contactor", "data": {"name": "Contactor", "ref": "k1"}, "position": {"x": 0, "y": 50}}, {"id": "device2", "type": "PowerSource", "data": {"value": "24", "type": "DC"}, "position": {"x": -200, "y": 200}}, {"id": "device3", "type": "Lamp", "data": {"name": "Lamp", "ref": "L1"}, "position": {"x": 200, "y": 200}}]}', '{"correct_answer": "GitHub"}', 'Lab to practice version control with Git.', CURRENT_TIMESTAMP, NULL),
(3, 'Linear Regression Lab', '{"devices": [{"id": "device1", "type": "Contactor", "data": {"name": "Contactor", "ref": "k1"}, "position": {"x": 0, "y": 50}}, {"id": "device2", "type": "PowerSource", "data": {"value": "24", "type": "DC"}, "position": {"x": -200, "y": 200}}, {"id": "device3", "type": "Lamp", "data": {"name": "Lamp", "ref": "L1"}, "position": {"x": 200, "y": 200}}]}', '{"correct_answer": "y=mx+b"}', 'Lab to implement linear regression.', CURRENT_TIMESTAMP, NULL),
(4, 'Sorting Algorithms Lab', '{"devices": [{"id": "device1", "type": "Contactor", "data": {"name": "Contactor", "ref": "k1"}, "position": {"x": 0, "y": 50}}, {"id": "device2", "type": "PowerSource", "data": {"value": "24", "type": "DC"}, "position": {"x": -200, "y": 200}}, {"id": "device3", "type": "Lamp", "data": {"name": "Lamp", "ref": "L1"}, "position": {"x": 200, "y": 200}}]}', '{"correct_answer": "QuickSort"}', 'Lab to learn about sorting algorithms.', CURRENT_TIMESTAMP, NULL),
(5, 'AC Circuit Analysis Lab', '{"devices": [{"id": "device1", "type": "Contactor", "data": {"name": "Relay", "ref": "RLY1"}, "position": {"x": -20, "y": 50}}, {"id": "device2", "type": "PowerSource", "data": {"value": "12", "type": "AC"}, "position": {"x": -200, "y": 200}}, {"id": "device3", "type": "Lamp", "data": {"name": "Buzzer", "ref": "BZ1"}, "position": {"x": 200, "y": 200}}, {"id": "device4", "type": "Lamp", "data": {"name": "Buzzer", "ref": "BZ2"}, "position": {"x": 0, "y": 200}}]}', '{"correct_answer": "Impedance"}', 'Lab to analyze AC circuits.', CURRENT_TIMESTAMP, NULL);

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
INSERT INTO student_lab (student_id, course_id, lab_id, started_at, score, updated_at)
VALUES 
(1, 1, 1, CURRENT_TIMESTAMP, 100, NULL),
(2, 2, 2, CURRENT_TIMESTAMP, 95, NULL),
(3, 3, 3, CURRENT_TIMESTAMP, 90, NULL),
(4, 4, 4, CURRENT_TIMESTAMP, 85, NULL),
(1, 5, 5, CURRENT_TIMESTAMP, 80, NULL);
