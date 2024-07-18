-- DROP DATABASE tt_sim;
-- CREATE DATABASE tt_sim;
-- USE tt_sim;

CREATE TABLE IF NOT EXISTS user (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(150) NOT NULL,
    last_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    picture VARCHAR(150) NULL DEFAULT NULL,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uc_user_email (email)
);

CREATE TABLE IF NOT EXISTS student (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS instructor (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS course (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    about TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS lab (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    course_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    answer JSON NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES course(id)
);

-- Store Student Enrolled Courses
CREATE TABLE IF NOT EXISTS student_course (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    student_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(id),
    FOREIGN KEY (course_id) REFERENCES course(id)
);

CREATE TABLE IF NOT EXISTS instructor_course (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    instructor_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES instructor(id),
    FOREIGN KEY (course_id) REFERENCES course(id)
);

-- Store info about labs students started working on
-- Stored info include scores
CREATE TABLE IF NOT EXISTS student_lab (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    student_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    lab_id INT UNSIGNED NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    submission JSON NOT NULL,
    score INT NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(id),
    FOREIGN KEY (course_id) REFERENCES course(id),
    FOREIGN KEY (lab_id) REFERENCES lab(id)
);

-- Indexes
CREATE INDEX idx_student_user_id ON student(user_id);
CREATE INDEX idx_lab_course_id ON lab(course_id);
CREATE INDEX idx_student_course_student_id ON student_course(student_id);
CREATE INDEX idx_student_course_course_id ON student_course(course_id);
CREATE INDEX idx_student_lab_student_id ON student_lab(student_id);
CREATE INDEX idx_student_lab_course_id ON student_lab(course_id);
CREATE INDEX idx_student_lab_lab_id ON student_lab(lab_id);