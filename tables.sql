DROP TABLE IF EXISTS newstudentadvisingform CASCADE;

DROP TABLE IF EXISTS gradecalc CASCADE;

DROP TABLE IF EXISTS degreerequirements CASCADE;

DROP TABLE IF EXISTS graduationapplication CASCADE;

DROP TABLE IF EXISTS advises CASCADE;

DROP TABLE IF EXISTS prereqs CASCADE;

DROP TABLE IF EXISTS transcripts CASCADE;

DROP TABLE IF EXISTS courses CASCADE;

DROP TABLE IF EXISTS academicinfo CASCADE;

DROP TABLE IF EXISTS documentstatus CASCADE;

DROP TABLE IF EXISTS recommendation CASCADE;

DROP TABLE IF EXISTS review CASCADE;

DROP TABLE IF EXISTS admissionsapplication CASCADE;

DROP TABLE IF EXISTS personalinfo CASCADE;

DROP TABLE IF EXISTS alumnipersonalinfo CASCADE;

DROP TABLE IF EXISTS applicantpersonalinfo CASCADE;

DROP TABLE IF EXISTS roles CASCADE;

DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
    email       VARCHAR(254) PRIMARY KEY,
    password    VARCHAR(255) NOT NULL,
    id          INT NOT NULL UNIQUE AUTO_INCREMENT
);

CREATE TABLE roles (
    id      INT,
    role    VARCHAR(30),
    PRIMARY KEY (id, role),
    FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE personalinfo (
    id                INT PRIMARY KEY,
    firstname         VARCHAR(30),
    lastname          VARCHAR(30),
    dob               DATE,
    address           VARCHAR (100),
    ssn               VARCHAR (11),
    FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE alumnipersonalinfo (
    id                INT PRIMARY KEY,
    firstname         VARCHAR(30),
    lastname          VARCHAR(30),
    dob               DATE,
    address           VARCHAR (100),
    graduationyear    INT,
    degreename        VARCHAR(30),
    ssn               VARCHAR (11),
    FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE applicantpersonalinfo (
    id                INT PRIMARY KEY,
    firstname         VARCHAR(30),
    lastname          VARCHAR(30),
    dob               DATE,
    address           VARCHAR (100),
    ssn               VARCHAR (11),
    FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE admissionsapplication (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    reviewerusername    VARCHAR(254),
    status              VARCHAR(20) NOT NULL,
    finaldecision       INT NOT NULL,
    semester            VARCHAR(10),
    year                YEAR,
    FOREIGN KEY (id) REFERENCES users(id),
    FOREIGN KEY (reviewerusername) REFERENCES users(email)
);

CREATE TABLE review (
    applicationid   INT PRIMARY KEY,
    decision        INT,
    defcourse       VARCHAR(100),
    comments        VARCHAR(100),
    reasons         VARCHAR(100),
    FOREIGN KEY (applicationid) REFERENCES admissionsapplication(id)
);

CREATE TABLE recommendation (
    recommendationid    INT PRIMARY KEY AUTO_INCREMENT,
    applicationid       INT NOT NULL,
    writername          VARCHAR(30),
    writeremail         VARCHAR(254),
    affiliation         VARCHAR(30),
    rating              INT CHECK (Rating >= 1 AND Rating <= 6),
    genericrating       BOOLEAN,
    crediblerating      BOOLEAN,
    FOREIGN KEY (applicationid) REFERENCES admissionsapplication(id)
);

CREATE TABLE documentstatus (
    applicationid           INT PRIMARY KEY,
    applicationsubmitted    BOOLEAN,
    transcriptrecieved      BOOLEAN,
    letterofrecrecieved     BOOLEAN,
    personalinfosubmitted   BOOLEAN,
    FOREIGN KEY (applicationid) REFERENCES admissionsapplication(id)
);

CREATE TABLE academicinfo (
    applicationid       INT PRIMARY KEY,
    degreeapplyingfor   VARCHAR(30),
    gretotal            INT CHECK (gretotal >= 0 AND gretotal <= 340),
    greverbal           INT CHECK (greverbal >= 130 AND greverbal <= 170),
    greanalytical       DECIMAL(2,1) CHECK (greanalytical >= 0 AND greanalytical <= 6),
    grequantitive       INT CHECK (grequantitive >= 130 AND grequantitive <= 170),
    gredate             DATE,
    greadvscore         INT CHECK (greadvscore >= 0 AND greadvScore <= 1000),
    gresubj             VARCHAR(30),
    greadvdate          DATE,
    toeflscore          INT,
    toefldate           DATE,
    bachgpa             VARCHAR(4),
    bachmajor           VARCHAR(30),
    bachyear            YEAR,
    bachuni             VARCHAR(30),
    masgpa              VARCHAR(4),
    masmajor            VARCHAR(30),
    masyear             YEAR,
    masuni              VARCHAR(30),
    areaofint           VARCHAR(300),
    experience          VARCHAR(300),
    FOREIGN KEY (applicationid) REFERENCES admissionsapplication(id)
);

CREATE TABLE courses (
    courseid       INT PRIMARY KEY,
    dept           VARCHAR(4),
    coursenum      INT,
    section        INT,
    title          VARCHAR(60),
    credithours    INT CHECK (credithours <= 3),
    day            CHAR(1),
    time           TIME,
    year           YEAR,
    semester       VARCHAR(30),
    professorid    INT,
    FOREIGN KEY (professorid) REFERENCES users(id)
);

CREATE TABLE transcripts (
    studentid      INT,
    dept           VARCHAR(4),
    coursenum      INT CHECK (coursenum >= 0 AND coursenum <= 9999),
    professorid    INT,
    year           YEAR,
    semester       VARCHAR(30),
    grade          VARCHAR(2),
    title          VARCHAR(60),
    PRIMARY KEY (studentid, dept, coursenum, year, semester),
    FOREIGN KEY (studentid) REFERENCES users(id),
    FOREIGN KEY (professorid) REFERENCES users(id)
);

CREATE TABLE prereqs (
    courseid    INT,
    prereqid    INT,
    PRIMARY KEY (courseid, prereqid),
    FOREIGN KEY (courseid) REFERENCES courses(courseid),
    FOREIGN KEY (prereqid) REFERENCES courses(courseid)
);

CREATE TABLE advises (
    studentid   INT PRIMARY KEY,
    facultyid   INT,
    hold        VARCHAR(30),
    degreename  VARCHAR(30),
    admityear   YEAR,
    FOREIGN KEY (studentid) REFERENCES users(id),
    FOREIGN KEY (facultyid) REFERENCES users(id)
);

CREATE TABLE graduationapplication (
    studentid   INT,
    courseid    INT,
    year        YEAR,
    cleared INT,
    PRIMARY KEY (studentid, courseid),
    FOREIGN KEY (courseid) REFERENCES courses(courseid),
    FOREIGN KEY (studentid) REFERENCES users(id)
);

CREATE TABLE degreerequirements (
    degreename  VARCHAR(30),
    courseid    INT,
    PRIMARY KEY (degreename, courseid),
    FOREIGN KEY (courseid) REFERENCES courses(courseid)
);

CREATE TABLE gradecalc (
    grade           VARCHAR(2) PRIMARY KEY,
    qualitypoints   DECIMAL(4,3)
);
CREATE TABLE newstudentadvisingform (
    studentid INT, 
    courseid INT NOT NULL,
    facultyid INT,
    PRIMARY KEY(studentid, courseid),
    FOREIGN KEY (courseid) REFERENCES courses(courseid),
    FOREIGN KEY (facultyid) REFERENCES users(id),
    FOREIGN KEY (studentid) REFERENCES users(id)
);


/* admin */
INSERT INTO users (email, password, id) VALUES ('admin@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 1);
INSERT INTO roles (id, role) VALUES (1, "ADMIN");

/* gs */
INSERT INTO users (email, password, id) VALUES ('gs@gwu.edu', '$2y$10$Dntyy58tka97rsnu94W0yOk6/hqWFcRJ42fb/nIl2/j5D7oaB0Fvm', 2);
INSERT INTO roles (id, role) VALUES (2, "GS");

/* instructor 1 */
INSERT INTO users (email, password, id) VALUES ('simha@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 3);
INSERT INTO roles (id, role) VALUES (3, "INSTRUCTOR");
INSERT INTO roles (id, role) VALUES (3, "CAC");
INSERT INTO roles (id, role) VALUES (3, "USER");
INSERT INTO personalinfo VALUES('3', 'r', 'simha', '2000-01-01', '123 address st', '123-45-6789');

/* instructor 2 */
/* advisor 1 */
INSERT INTO users (email, password, id) VALUES ('heller@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 4);
INSERT INTO roles (id, role) VALUES (4, "INSTRUCTOR");
INSERT INTO roles (id, role) VALUES (4, "ADVISOR");
INSERT INTO roles (id, role) VALUES (4, "USER");
INSERT INTO personalinfo VALUES('4', 'r', 'heller', '2000-01-01', '123 address st', '023-45-6789');

/* instructor 3 */
/* advisor 2 */
INSERT INTO users (email, password, id) VALUES ('parmer@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 5);
INSERT INTO roles (id, role) VALUES (5, "INSTRUCTOR");
INSERT INTO roles (id, role) VALUES (5, "ADVISOR");
INSERT INTO roles (id, role) VALUES (5, "USER");
INSERT INTO personalinfo VALUES('5', 'g', 'parmer', '2000-01-01', '123 address st', '013-45-6789');

/* instructor 4 */
/* reviewer 1 */
INSERT INTO users (email, password, id) VALUES ('pless@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 6);
INSERT INTO roles (id, role) VALUES (6, "INSTRUCTOR");
INSERT INTO roles (id, role) VALUES (6, "REVIEWER");
INSERT INTO roles (id, role) VALUES (6, "USER");
INSERT INTO personalinfo VALUES('6', 'r', 'pless', '2000-01-01', '123 address st', '012-45-6789');

/* instructor 5 */
/* reviewer 2 */
/* advisor 3 */
INSERT INTO users (email, password, id) VALUES ('narahari@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 7);
INSERT INTO roles (id, role) VALUES (7, "INSTRUCTOR");
INSERT INTO roles (id, role) VALUES (7, "ADVISOR");
INSERT INTO roles (id, role) VALUES (7, "REVIEWER");
INSERT INTO roles (id, role) VALUES (7, "USER");
INSERT INTO personalinfo VALUES('7', 'b', 'narahari', '2000-01-01', '123 address st', '012-35-6789');

/* add courses */
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('1','6221', 'CS', '3', '2018','spring', '10', '3','M', '15:00:00', 'Software Paradigms');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('2','6461', 'CS', '4', '2018','spring', '10','3', 'T', '15:00:00', 'Computer Architecture');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('3','6212', 'CS', '5', '2018','spring', '10','3', 'W', '15:00:00', 'Algorithms');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('4','6225', 'CS', '6', '2018','spring', '10','3', 'R', '15:00:00', 'Data Compression');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('5','6232', 'CS', '7', '2018','spring', '10', '3','M','18:00:00', 'Networks 1');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('6','6233', 'CS', '3', '2018','spring', '10', '3','T', '18:00:00', 'Networks 2');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('7','6241', 'CS', '4', '2018','spring', '10','3', 'W', '18:00:00', 'Database 1');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('8','6242', 'CS', '5', '2018','spring', '10','3', 'R', '18:00:00', 'Database 2');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('9','6246', 'CS', '6', '2018','spring', '10', '3','T', '15:00:00', 'Compilers');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('10','6251', 'CS', '3', '2018','spring', '10', '3','M', '18:00:00', 'Distributed Systems');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('11','6254', 'CS', '7', '2018','spring', '10','3', 'M', '15:00:00', 'Software Engineering');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('12','6260', 'CS', '4', '2018','spring', '10', '3','R', '18:00:00', 'Multimedia');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('13','6262', 'CS', '5', '2018','spring', '10','3', 'W', '18:00:00', 'Graphics 1');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('14','6283', 'CS', '6', '2018','spring', '10', '3','T', '18:00:00', 'Security 1');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('15','6284', 'CS', '4', '2018','spring', '10', '3','M', '18:00:00', 'Cryptography');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('16','6286', 'CS', '7', '2018','spring', '10', '3','W', '18:00:00', 'Network Security');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('17','6325', 'CS', '3', '2018','spring', '10','2', 'R', '16:00:00', 'Advanced Algorithms');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('18','6339', 'CS', '5', '2018','spring', '10', '2','T', '15:00:00', 'Embedded Systems');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('19','6384', 'CS', '6', '2018','spring', '10', '3','W', '16:00:00', 'Advanced Crypto');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('20','6243', 'EE', '5', '2018','spring', '10', '3','M', '18:00:00', 'Communication Systems');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('21','6244', 'EE', '4', '2018','spring', '10', '2','T', '18:00:00', 'Information Theory');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('22','6210', 'Math', '3', '2018','spring', '10', '2','W', '18:00:00', 'Logic');

/* add prereqs */
INSERT INTO prereqs VALUES (6, 5);
INSERT INTO prereqs VALUES (8, 7);
INSERT INTO prereqs VALUES (9, 2);
INSERT INTO prereqs VALUES (9, 3);
INSERT INTO prereqs VALUES (10, 2);
INSERT INTO prereqs VALUES (11, 1);
INSERT INTO prereqs VALUES (12, 1);
INSERT INTO prereqs VALUES (14, 3);
INSERT INTO prereqs VALUES (15, 3);
INSERT INTO prereqs VALUES (16, 5);
INSERT INTO prereqs VALUES (16, 14);
INSERT INTO prereqs VALUES (17, 3);
INSERT INTO prereqs VALUES (18, 2);
INSERT INTO prereqs VALUES (18, 3);
INSERT INTO prereqs VALUES (19, 14);
INSERT INTO prereqs VALUES (19, 15);


/* student 1 */
INSERT INTO users (email, password, id) VALUES ('mccartney@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 8);
INSERT INTO roles (id, role) VALUES (8, "STUDENT");
INSERT INTO roles (id, role) VALUES (8, "USER");
INSERT INTO personalinfo VALUES('8', 'paul', 'mccartney', '2000-01-01', '123 address st', '333-11-1111');

INSERT INTO transcripts VALUES('8', 'CS', '6221', '3', '2017', 'fall', 'A', 'Software Paradigms');
INSERT INTO transcripts VALUES('8', 'CS', '6461', '4', '2017', 'fall', 'A', 'Computer Architecture');
INSERT INTO transcripts VALUES('8', 'CS', '6212', '5', '2017', 'fall', 'B', 'Algorithims');
INSERT INTO transcripts VALUES('8', 'CS', '6225', '6', '2017', 'fall', 'B', 'Data Compression');
INSERT INTO transcripts VALUES('8', 'CS', '6232', '7', '2017', 'fall', 'B', 'Networks 1');
INSERT INTO transcripts VALUES('8', 'CS', '6233', '3', '2018', 'spring', 'A', 'Networks 2');
INSERT INTO transcripts VALUES('8', 'CS', '6241', '4', '2018', 'spring', 'A', 'Databases 1');
INSERT INTO transcripts VALUES('8', 'CS', '6246', '6', '2018', 'spring', 'A', 'Compilers');
INSERT INTO transcripts VALUES('8', 'CS', '6262', '5', '2018', 'spring', 'B', 'Graphics 1');
INSERT INTO transcripts VALUES('8', 'CS', '6283', '6', '2018', 'spring', 'B', 'Security 1');

INSERT INTO advises VALUES('8', '7', NULL, 'MS_CS', 2017);

/* student 2 */
INSERT INTO users (email, password, id) VALUES ('harrison@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 9);
INSERT INTO roles (id, role) VALUES (9, "STUDENT");
INSERT INTO roles (id, role) VALUES (9, "USER");
INSERT INTO personalinfo VALUES('9', 'george', 'harrison', '2000-01-01', '123 address st', '444-11-1111');

INSERT INTO transcripts VALUES('9', 'CS', '6221', '3', '2017', 'fall', 'B', 'Software Paradigms');
INSERT INTO transcripts VALUES('9', 'CS', '6461', '4', '2017', 'fall', 'B', 'Computer Architecture');
INSERT INTO transcripts VALUES('9', 'CS', '6212', '5', '2017', 'fall', 'B', 'Algorithims');
INSERT INTO transcripts VALUES('9', 'CS', '6232', '7', '2017', 'fall', 'B', 'Networks 1');
INSERT INTO transcripts VALUES('9', 'CS', '6241', '4', '2017', 'fall', 'B', 'Databases 1');
INSERT INTO transcripts VALUES('9', 'CS', '6233', '3', '2018', 'spring', 'B', 'Networks 2');
INSERT INTO transcripts VALUES('9', 'CS', '6242', '5', '2018', 'spring', 'B', 'Databases 2');
INSERT INTO transcripts VALUES('9', 'EE', '6244', '4', '2018', 'spring', 'C', 'Information Theory');
INSERT INTO transcripts VALUES('9', 'CS', '6283', '6', '2018', 'spring', 'B', 'Security 1');

INSERT INTO advises VALUES('9', '5', NULL, 'MS_CS', 2017);

/* alumni 1 */
INSERT INTO users (email, password, id) VALUES ('clapton@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 10);
INSERT INTO roles (id, role) VALUES (10, "ALUMNI");
INSERT INTO roles (id, role) VALUES (10, "USER");
INSERT INTO alumnipersonalinfo VALUES('10', 'eric', 'clapton', '2000-01-01', '123 address st', 2006, 'MS_CS', '555-11-1111');

INSERT INTO transcripts VALUES('10', 'CS', '6221', '3', '2005', 'fall', 'B', 'Software Paradigms');
INSERT INTO transcripts VALUES('10', 'CS', '6461', '4', '2005', 'fall', 'B', 'Computer Architecture');
INSERT INTO transcripts VALUES('10', 'CS', '6212', '5', '2005', 'fall', 'B', 'Algorithims');
INSERT INTO transcripts VALUES('10', 'CS', '6232', '7', '2005', 'fall', 'B', 'Networks 1');
INSERT INTO transcripts VALUES('10', 'CS', '6241', '4', '2005', 'fall', 'B', 'Databases 1');
INSERT INTO transcripts VALUES('10', 'CS', '6233', '3', '2006', 'spring', 'B', 'Networks 2');
INSERT INTO transcripts VALUES('10', 'CS', '6242', '5', '2006', 'spring', 'B', 'Databases 2');
INSERT INTO transcripts VALUES('10', 'CS', '6283', '6', '2006', 'spring', 'A', 'Security 1');
INSERT INTO transcripts VALUES('10', 'CS', '6286', '7', '2006', 'spring', 'A', 'Network Security');
INSERT INTO transcripts VALUES('10', 'CS', '6254', '7', '2006', 'spring', 'A', 'Software Engineering');

/* Applicant 1 (complete with no reviews) */
INSERT INTO users (email, password, id) VALUES ('lennon@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 11);
INSERT INTO roles (id, role) VALUES (11, "APPLICANT");
INSERT INTO roles (id, role) VALUES (11, "USER");
INSERT INTO applicantpersonalinfo VALUES(11, 'John', 'Lennon', '2000-01-01', '1800 22nd St NW, Washington, DC', 2018, '111-11-1111');
INSERT INTO admissionsapplication (id, status, finaldecision, semester, year) VALUE (11, 'Complete', 0, 'Fall', 2018);
INSERT INTO documentstatus (applicationid, applicationsubmitted, transcriptrecieved, letterofrecrecieved, personalinfosubmitted) VALUE (11, 1, 1, 1, 1);
INSERT INTO academicinfo (applicationid, degreeapplyingfor, gretotal, greverbal, greanalytical, grequantitive, gredate, bachgpa, bachmajor, bachyear, bachuni) VALUE (11, 'Ph.D.', 300, 150, 4, 150, '2018-01-05', '3.9', 'Computer Science', 2018, 'George Washington University');
INSERT INTO recommendation (applicationid, writername, writeremail, affiliation) VALUE (11, 'John Smith', 'smith@gwu.edu', 'Advisor');
INSERT INTO recommendation (applicationid, writername, writeremail, affiliation) VALUE (11, 'Jill Peters', 'jpeters@gwu.edu', 'Professor');
INSERT INTO recommendation (applicationid, writername, writeremail, affiliation) VALUE (11, 'Peter Jills', 'pjills@gwu.edu', 'Professor');

/* Applicant 2 (incomplete, missing transcripts) */
INSERT INTO users (email, password, id) VALUES ('starr@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 12);
INSERT INTO roles (id, role) VALUES (12, "APPLICANT");
INSERT INTO roles (id, role) VALUES (12, "USER");
INSERT INTO applicantpersonalinfo VALUES (12, 'Ringo', 'Starr', '2000-01-01', '2100 H St NW, Washington, DC', 2018, '222-11-1111');
INSERT INTO admissionsapplication (id) VALUE (12);
INSERT INTO academicinfo (applicationid) VALUE (12);
INSERT INTO recommendation (applicationid) VALUE (12);
INSERT INTO recommendation (applicationid) VALUE (12);
INSERT INTO recommendation (applicationid) VALUE (12);

/* fill grade calc table */
INSERT INTO gradecalc(grade,qualitypoints)
VALUES
('A',4.00),
('A-',3.70),
('B+',3.30),
('B',3.00),
('B-',2.70),
('C+',2.30),
('C',2.00),
('F',0.00),
('IP',0.00);

--DELIMITER CHANGE BELOW;

---TRIGGERS---

delimiter $$
CREATE TRIGGER statuscheck after
  UPDATE
  ON documentstatus FOR EACH row begin IF (new.applicationsubmitted = 1
  AND    new.letterofrecrecieved = 1
  AND    new.personalinfosubmitted = 1
  AND    new.transcriptrecieved = 1) THEN
  UPDATE admissionsapplication
  SET    status = 'Complete'
  WHERE  id = new.applicationid;
  ELSE
  UPDATE admissionsapplication
  SET    status = 'Incomplete'
  WHERE  id = new.applicationid;END IF;END$$
delimiter ;

--STOP
