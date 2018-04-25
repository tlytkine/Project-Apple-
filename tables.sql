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
    email               VARCHAR(254) NOT NULL,
    reviewerusername    VARCHAR(254),
    status              VARCHAR(20) NOT NULL,
    finaldecision       INT NOT NULL,
    semester            VARCHAR(10),
    year                YEAR,
    FOREIGN KEY (id) REFERENCES users(id),
    FOREIGN KEY (email) REFERENCES users(email),
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

INSERT INTO courses VALUES ('1', 'CS','6221', '1', 'class', '3', 'M', '15:00:00', '2018','spring', '3');
INSERT INTO courses VALUES ('2', 'CS','6222', '1', 'class', '3', 'M', '12:00:00', '2018','spring', '3');

INSERT INTO users (email, password, id) VALUES ('billy@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 4);
INSERT INTO roles (id, role) VALUES (4, "STUDENT");
INSERT INTO personalinfo VALUES('4', 'billy', 'miller', '2000-01-01', '123 address st', '123-45-6789');

INSERT INTO transcripts VALUES('4', 'CS', '6221', '3', '2018', 'spring', 'A');

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
