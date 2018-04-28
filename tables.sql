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

INSERT INTO users (email, password, id) VALUES ('admin@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 1);
INSERT INTO roles (id, role) VALUES (1, "ADMIN");

INSERT INTO users (email, password, id) VALUES ('gs@gwu.edu', '$2y$10$Dntyy58tka97rsnu94W0yOk6/hqWFcRJ42fb/nIl2/j5D7oaB0Fvm', 2);
INSERT INTO roles (id, role) VALUES (2, "GS");

INSERT INTO users (email, password, id) VALUES ('instructor@gwu.edu', '$2y$10$Dntyy58tka97rsnu94W0yOk6/hqWFcRJ42fb/nIl2/j5D7oaB0Fvm', 3);
INSERT INTO roles (id, role) VALUES (3, "INSTRUCTOR");
INSERT INTO personalinfo VALUES('3', 'b', 'narahari', '2000-01-01', '123 address st', '123-45-6789');

INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('1','6221', 'CS', '3', '2018','spring', '10', '3','M', '15:00:00', 'Software Paradigms');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('2','6461', 'CS', '3', '2018','spring', '10','3', 'T', '15:00:00', 'Computer Architecture');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('3','6212', 'CS', '3', '2018','spring', '10','3', 'W', '15:00:00', 'Algorithms');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('4','6225', 'CS', '3', '2018','spring', '10','3', 'R', '15:00:00', 'Data Compression');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('5','6232', 'CS', '3', '2018','spring', '10', '3','M','18:00:00', 'Networks 1');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('6','6233', 'CS', '3', '2018','spring', '10', '3','T', '18:00:00', 'Networks 2');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('7','6241', 'CS', '3', '2018','spring', '10','3', 'W', '18:00:00', 'Database 1');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('8','6242', 'CS', '3', '2018','spring', '10','3', 'R', '18:00:00', 'Database 2');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('9','6246', 'CS', '3', '2018','spring', '10', '3','T', '15:00:00', 'Compilers');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('10','6251', 'CS', '3', '2018','spring', '10', '3','M', '18:00:00', 'Distributed Systems');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('11','6254', 'CS', '3', '2018','spring', '10','3', 'M', '15:00:00', 'Software Engineering');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('12','6260', 'CS', '3', '2018','spring', '10', '3','R', '18:00:00', 'Multimedia');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('13','6262', 'CS', '3', '2018','spring', '10','3', 'W', '18:00:00', 'Graphics 1');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('14','6283', 'CS', '3', '2018','spring', '10', '3','T', '18:00:00', 'Security 1');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('15','6284', 'CS', '3', '2018','spring', '10', '3','M', '18:00:00', 'Cryptography');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('16','6286', 'CS', '3', '2018','spring', '10', '3','W', '18:00:00', 'Network Security');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('17','6325', 'CS', '3', '2018','spring', '10','2', 'R', '16:00:00', 'Advanced Algorithms');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('18','6339', 'CS', '3', '2018','spring', '10', '2','T', '15:00:00', 'Embedded Systems');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('19','6384', 'CS', '3', '2018','spring', '10', '3','W', '16:00:00', 'Advanced Crypto');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('20','6243', 'EE', '3', '2018','spring', '10', '3','M', '18:00:00', 'Communication Systems');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('21','6244', 'EE', '3', '2018','spring', '10', '2','T', '18:00:00', 'Information Theory');
INSERT INTO courses (courseid, coursenum, dept, professorid, year, semester, section, credithours, day, time, title) VALUES ('22','6210', 'Math', '3', '2018','spring', '10', '2','W', '18:00:00', 'Logic');

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



INSERT INTO users (email, password, id) VALUES ('billy@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 4);
INSERT INTO roles (id, role) VALUES (4, "STUDENT");
INSERT INTO roles (id, role) VALUES (4, "USER");
INSERT INTO personalinfo VALUES('4', 'billy', 'miller', '2000-01-01', '123 address st', '123-45-6789');

INSERT INTO transcripts VALUES('4', 'CS', '6221', '3', '2018', 'spring', 'A');

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
