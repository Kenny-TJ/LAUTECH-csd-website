-- =====================================================
-- LAUTECH CS Department Website — Database Setup
-- Database: lautech_csd
-- =====================================================
-- HOW TO USE:
-- 1. Open phpMyAdmin: http://localhost/phpmyadmin/
-- 2. Create a database named: lautech_csd
--    Collation: utf8mb4_general_ci
-- 3. Click the lautech_csd database in the left panel
-- 4. Click the SQL tab
-- 5. Paste this entire file and click Go
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ── ANNOUNCEMENTS ─────────────────────────────────
CREATE TABLE IF NOT EXISTS announcements (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    message    VARCHAR(300) NOT NULL,
    link_text  VARCHAR(80),
    link_url   VARCHAR(200),
    is_active  TINYINT(1) DEFAULT 1,
    expires_at DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO announcements (message, link_text, link_url, expires_at) VALUES
('2024/2025 First Semester examination timetable is now available.', 'View Timetable', '/lautech_csd/calendar.php', '2025-12-31'),
('Late registration closes Friday 20th September. Visit the portal immediately.', NULL, NULL, '2025-09-20');

-- ── NEWS ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS news (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(200) NOT NULL,
    body         TEXT         NOT NULL,
    category     VARCHAR(60)  DEFAULT 'General',
    author       VARCHAR(100) DEFAULT 'Department',
    is_published TINYINT(1)   DEFAULT 1,
    published_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO news (title, body, category, published_at) VALUES
('2024/2025 First Semester Results Released',
 'The Senate-approved results for the 2024/2025 First Semester examinations have been released. Students are advised to check the student portal for their results. Any complaints should be directed to the Examinations Officer within two weeks of this publication.',
 'Results', '2025-02-10 09:00:00'),
('Departmental Seminar: AI and the Future of Computing',
 'The Department of Computer Science and Engineering will host its quarterly seminar on Artificial Intelligence and the Future of Computing. The event is open to all students and staff. Date and venue will be communicated shortly via the notice board.',
 'Events', '2025-01-20 10:00:00'),
('Industrial Training Placement — 300 Level Students',
 'All 300 Level students are reminded to submit their IT placement letters to the IT Coordinator on or before the deadline. Students who have not secured placement are advised to visit the departmental notice board for the list of available partner organisations.',
 'Notice', '2025-01-15 08:00:00');

-- ── COURSES ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS courses (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    level         TINYINT      NOT NULL,
    semester      TINYINT      NOT NULL,
    code          VARCHAR(10)  NOT NULL,
    title         VARCHAR(150) NOT NULL,
    units         TINYINT      NOT NULL,
    is_compulsory TINYINT(1)   DEFAULT 1,
    created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- 100 Level
INSERT INTO courses (level, semester, code, title, units, is_compulsory) VALUES
(100, 1, 'CSE 101', 'Introduction to Computing', 3, 1),
(100, 1, 'CSE 103', 'Computer Programming I', 3, 1),
(100, 1, 'MTH 101', 'Elementary Mathematics I', 3, 1),
(100, 1, 'PHY 101', 'General Physics I', 3, 1),
(100, 1, 'CHM 101', 'General Chemistry I', 3, 1),
(100, 1, 'GST 101', 'Use of English I', 2, 1),
(100, 2, 'CSE 102', 'Introduction to Problem Solving', 3, 1),
(100, 2, 'CSE 104', 'Computer Programming II', 3, 1),
(100, 2, 'MTH 102', 'Elementary Mathematics II', 3, 1),
(100, 2, 'PHY 102', 'General Physics II', 3, 1),
(100, 2, 'GST 102', 'Use of English II', 2, 1);

-- 200 Level
INSERT INTO courses (level, semester, code, title, units, is_compulsory) VALUES
(200, 1, 'CSE 201', 'Discrete Mathematics', 3, 1),
(200, 1, 'CSE 203', 'Data Structures & Algorithms', 3, 1),
(200, 1, 'CSE 205', 'Digital Logic Design', 3, 1),
(200, 1, 'CSE 207', 'Object-Oriented Programming', 3, 1),
(200, 1, 'MTH 201', 'Mathematical Methods I', 3, 1),
(200, 1, 'GST 201', 'Introduction to Entrepreneurship', 2, 1),
(200, 2, 'CSE 202', 'Computer Architecture', 3, 1),
(200, 2, 'CSE 204', 'Database Management Systems', 3, 1),
(200, 2, 'CSE 206', 'Operating Systems I', 3, 1),
(200, 2, 'CSE 208', 'Web Technologies', 3, 1),
(200, 2, 'MTH 202', 'Mathematical Methods II', 3, 1),
(200, 2, 'GST 202', 'Society & Environment', 2, 0);

-- 300 Level
INSERT INTO courses (level, semester, code, title, units, is_compulsory) VALUES
(300, 1, 'CSE 301', 'Theory of Computation', 3, 1),
(300, 1, 'CSE 303', 'Software Engineering I', 3, 1),
(300, 1, 'CSE 305', 'Computer Networks I', 3, 1),
(300, 1, 'CSE 307', 'Artificial Intelligence', 3, 1),
(300, 1, 'CSE 309', 'Systems Programming', 3, 1),
(300, 1, 'CSE 311', 'Industrial Training Preparation', 1, 1),
(300, 2, 'CSE 398', 'Industrial Training', 6, 1);

-- 400 Level
INSERT INTO courses (level, semester, code, title, units, is_compulsory) VALUES
(400, 1, 'CSE 401', 'Software Engineering II', 3, 1),
(400, 1, 'CSE 403', 'Computer Networks II', 3, 1),
(400, 1, 'CSE 405', 'Information Security', 3, 1),
(400, 1, 'CSE 407', 'Human-Computer Interaction', 3, 1),
(400, 1, 'CSE 409', 'Compiler Construction', 3, 1),
(400, 1, 'CSE 411', 'Machine Learning', 3, 0),
(400, 2, 'CSE 402', 'Distributed Systems', 3, 1),
(400, 2, 'CSE 404', 'Mobile Application Development', 3, 0),
(400, 2, 'CSE 406', 'Research Methodology', 3, 1),
(400, 2, 'CSE 408', 'Computer Graphics', 3, 0),
(400, 2, 'CSE 410', 'Embedded Systems', 3, 0);

-- 500 Level
INSERT INTO courses (level, semester, code, title, units, is_compulsory) VALUES
(500, 1, 'CSE 501', 'Advanced Algorithms', 3, 1),
(500, 1, 'CSE 503', 'Cloud Computing', 3, 1),
(500, 1, 'CSE 505', 'Project Management', 2, 1),
(500, 1, 'CSE 507', 'Final Year Project I', 3, 1),
(500, 1, 'CSE 509', 'Entrepreneurship in ICT', 2, 1),
(500, 2, 'CSE 502', 'Advanced Database Systems', 3, 1),
(500, 2, 'CSE 504', 'IT Ethics & Professionalism', 2, 1),
(500, 2, 'CSE 506', 'Final Year Project II', 6, 1),
(500, 2, 'CSE 508', 'Seminar', 1, 1);

-- ── STAFF ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS staff (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    full_name   VARCHAR(100) NOT NULL,
    title       VARCHAR(30)  NOT NULL,
    designation VARCHAR(100) NOT NULL,
    role        VARCHAR(100),
    email       VARCHAR(100),
    phone       VARCHAR(20),
    photo       VARCHAR(200) DEFAULT 'default-staff.png',
    bio         TEXT,
    is_active   TINYINT(1)  DEFAULT 1,
    sort_order  INT          DEFAULT 0,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO staff (full_name, title, designation, role, email, sort_order) VALUES
('Mohammed Ibrahim',  'Dr.',   'Associate Professor',     'Head of Department',       'hod.cse@lautech.edu.ng',      1),
('Adefila Akinola',  'Prof.', 'Professor',                'Director of Programmes',   'adefila.cse@lautech.edu.ng',  2),
('Adekunle Bello',   'Dr.',   'Senior Lecturer',          'Examinations Officer',     'abello.cse@lautech.edu.ng',   3),
('Funmilayo Okafor', 'Dr.',   'Senior Lecturer',          'Students Affairs Officer', 'fokafor.cse@lautech.edu.ng',  4),
('Babatunde Yusuf',  'Mr.',   'Lecturer I',               'Laboratory Coordinator',   'byusuf.cse@lautech.edu.ng',   5),
('Ngozi Adeyemi',    'Mrs.',  'Lecturer II',              NULL,                       'nadeyemi.cse@lautech.edu.ng', 6),
('Chukwuemeka Eze',  'Dr.',   'Lecturer I',               NULL,                       'ceze.cse@lautech.edu.ng',     7),
('Aisha Musa',       'Miss',  'Assistant Lecturer',       NULL,                       'amusa.cse@lautech.edu.ng',    8),
('Seun Adebayo',     'Mr.',   'Graduate Assistant',       NULL,                       'sadebayo.cse@lautech.edu.ng', 9),
('Grace Okonkwo',    'Mrs.',  'Administrative Secretary', 'Dept. Secretary',          'gokonkwo.cse@lautech.edu.ng', 10);

-- ── ACADEMIC CALENDAR ────────────────────────────
CREATE TABLE IF NOT EXISTS academic_calendar (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    session_year VARCHAR(20)  NOT NULL,
    semester     TINYINT      NOT NULL,
    activity     VARCHAR(200) NOT NULL,
    start_date   DATE         NOT NULL,
    end_date     DATE,
    category     VARCHAR(50)  DEFAULT 'General',
    created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO academic_calendar (session_year, semester, activity, start_date, end_date, category) VALUES
('2024/2025', 1, 'Late Registration / Change of Course', '2024-09-02', '2024-09-06', 'Registration'),
('2024/2025', 1, 'Lectures Begin', '2024-09-09', NULL, 'Academic'),
('2024/2025', 1, 'Deadline for Course Form Submission', '2024-09-20', NULL, 'Registration'),
('2024/2025', 1, 'Mid-Semester Break', '2024-11-04', '2024-11-08', 'Holiday'),
('2024/2025', 1, 'Lectures End / Revision Week', '2024-11-25', '2024-11-29', 'Academic'),
('2024/2025', 1, 'First Semester Examinations Begin', '2024-12-02', NULL, 'Examination'),
('2024/2025', 1, 'First Semester Examinations End', '2024-12-20', NULL, 'Examination'),
('2024/2025', 1, 'Christmas & New Year Break', '2024-12-23', '2025-01-03', 'Holiday'),
('2024/2025', 2, 'Second Semester Registration Opens', '2025-01-20', '2025-01-24', 'Registration'),
('2024/2025', 2, 'Lectures Begin', '2025-01-27', NULL, 'Academic'),
('2024/2025', 2, 'Deadline for Course Form Submission', '2025-02-07', NULL, 'Registration'),
('2024/2025', 2, 'Mid-Semester Break', '2025-03-24', '2025-03-28', 'Holiday'),
('2024/2025', 2, 'Lectures End / Revision Week', '2025-05-12', '2025-05-16', 'Academic'),
('2024/2025', 2, 'Second Semester Examinations Begin', '2025-05-19', NULL, 'Examination'),
('2024/2025', 2, 'Second Semester Examinations End', '2025-06-06', NULL, 'Examination'),
('2024/2025', 2, 'Senate Approved Results Released', '2025-07-14', NULL, 'Academic'),
('2024/2025', 2, 'Long Vacation', '2025-07-21', '2025-09-05', 'Holiday');

-- ── REGULATIONS ───────────────────────────────────
CREATE TABLE IF NOT EXISTS regulations (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    category   VARCHAR(80)  NOT NULL,
    title      VARCHAR(200) NOT NULL,
    content    TEXT         NOT NULL,
    sort_order INT          DEFAULT 0,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO regulations (category, title, content, sort_order) VALUES
('General Rules', 'Attendance Policy', 'Students are required to attend a minimum of 75% of all lectures, tutorials, and laboratory sessions for each course. Failure to meet this threshold may result in the student being barred from the end-of-semester examination for the affected course.', 1),
('General Rules', 'Dress Code', 'Students are expected to dress decently and in accordance with university standards at all times within the department. Indecent dressing such as sagging trousers, transparent clothing, and sleeveless tops for male students is strictly prohibited.', 2),
('General Rules', 'Use of Mobile Devices', 'Mobile phones and other electronic devices must be switched off or set to silent mode during lectures and examinations. Violation during examinations shall be treated as examination malpractice.', 3),
('General Rules', 'Student Conduct', 'Students must treat all members of staff, fellow students, and visitors with respect. Acts of misconduct including verbal abuse, intimidation, and vandalism of departmental property are offences subject to disciplinary action.', 4),
('Examination Rules', 'Eligibility to Sit Examinations', 'A student must have registered for a course, paid all prescribed fees, and met the minimum attendance requirement to be eligible to sit the examination for that course. Students who do not fulfil these conditions will not be allowed into the examination hall.', 1),
('Examination Rules', 'Examination Malpractice', 'Any student found engaging in examination malpractice — including bringing unauthorised materials into the hall, communication with other candidates, or impersonation — shall be reported to the Examination Malpractice Committee. Penalties range from cancellation of results to outright expulsion from the university.', 2),
('Examination Rules', 'Lateness to Examinations', 'Students who arrive more than 30 minutes after an examination has commenced shall not be permitted to enter the hall. No extra time shall be granted to any student who arrives late.', 3),
('Examination Rules', 'Script Submission', 'All answer booklets must be submitted to the invigilator before leaving the examination hall. A student who leaves the hall without submitting their script shall be deemed to have not sat the examination.', 4),
('Result Computation', 'Grade Point System', 'The department operates a 5-point grading system. Letter grades and their corresponding grade points are as follows: A (70–100%) = 5 points; B (60–69%) = 4 points; C (50–59%) = 3 points; D (45–49%) = 2 points; E (40–44%) = 1 point; F (0–39%) = 0 points.', 1),
('Result Computation', 'Cumulative Grade Point Average (CGPA)', 'The CGPA is computed as the total weighted grade points earned divided by the total number of credit units registered. Only courses with passing grades contribute positively to the CGPA. The CGPA determines a student\'s class of degree at graduation.', 2),
('Result Computation', 'Class of Degree', 'Final degree classification is based on CGPA as follows: First Class (4.50–5.00); Second Class Upper (3.50–4.49); Second Class Lower (2.40–3.49); Third Class (1.50–2.39); Pass (1.00–1.49). Students with CGPA below 1.00 after exhausting carry-over provisions shall be withdrawn from the programme.', 3),
('Result Computation', 'Carry-Over Courses', 'A student who fails a compulsory course must carry it over and retake it in the next available semester. The maximum grade obtainable on a carry-over attempt is a C (50–59%). An elective course that is failed may be substituted with another elective.', 4),
('Faculty Rules', 'Change of Programme', 'A student wishing to change their programme of study must apply formally to the Dean of the Faculty within the first four weeks of the first semester. Change of programme is subject to availability of space, meeting minimum entry requirements of the new programme, and senate approval.', 1),
('Faculty Rules', 'Deferment of Admission', 'A student may apply to defer their admission for a maximum of one academic session on grounds of ill-health, financial hardship, or other cogent reasons acceptable to the senate. Deferment must be applied for before the end of the first month of the session.', 2),
('Faculty Rules', 'Maximum Duration of Study', 'The maximum duration allowed to complete the B.Tech degree programme is ten (10) semesters, excluding any approved deferment period. A student who does not graduate within this period shall be withdrawn from the university.', 3);

-- ── STUDENTS ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS students (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    matric_no     VARCHAR(20)  NOT NULL UNIQUE,
    full_name     VARCHAR(150) NOT NULL,
    level         SMALLINT     NOT NULL,
    email         VARCHAR(150) NOT NULL,
    phone         VARCHAR(20),
    session_year  VARCHAR(20)  NOT NULL,
    submitted_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ── SUBMISSIONS ───────────────────────────────────
CREATE TABLE IF NOT EXISTS submissions (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    student_id   INT          NOT NULL,
    file_name    VARCHAR(200) NOT NULL,
    file_path    VARCHAR(200) NOT NULL,
    file_type    VARCHAR(10)  NOT NULL,
    file_size    INT          NOT NULL,
    submitted_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- Setup complete. Open http://localhost/lautech_csd/
-- =====================================================
