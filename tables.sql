CREATE TABLE users ( 
    email    VARCHAR(254) PRIMARY KEY, 
    password VARCHAR(255) NOT NULL, 
    id       INT NOT NULL UNIQUE AUTO_INCREMENT
); 
  
CREATE TABLE roles ( 
    id   INT,
    role VARCHAR(30),
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

CREATE TABLE admissions_application ( 
    id               INT PRIMARY KEY AUTO_INCREMENT, 
    email            VARCHAR(254) NOT NULL, 
    reviewerusername VARCHAR(254), 
    status           VARCHAR(20) NOT NULL, 
    finaldecision    INT NOT NULL, 
    semester         VARCHAR(10), 
    year             YEAR 
    FOREIGN KEY (email) REFERENCES users(email) 
); 

CREATE TABLE review ( 
    applicationid INT PRIMARY KEY, 
    decision      INT, 
    defcourse     VARCHAR(100), 
    comments      VARCHAR(100), 
    reasons       VARCHAR(100), 
    FOREIGN KEY (applicationid) REFERENCES admissions_application(id) 
); 

CREATE TABLE recommendation (  
    recommendationid INT PRIMARY KEY AUTO_INCREMENT, 
    applicationid    INT NOT NULL,
    writername       VARCHAR(30), 
    writeremail      VARCHAR(254), 
    affiliation      VARCHAR(30), 
    rating           INT CHECK (Rating >= 1 AND Rating <= 6), 
    genericrating    BOOLEAN, 
    crediblerating   BOOLEAN, 
    FOREIGN KEY (applicationid) REFERENCES admissions_application(id) 
); 

CREATE TABLE documentstatus ( 
    applicationid         INT NOT NULL PRIMARY KEY, 
    applicationsubmitted  BOOLEAN,
    transcriptrecieved    BOOLEAN, 
    letterofrecrecieved   BOOLEAN, 
    personalinfosubmitted BOOLEAN, 
    FOREIGN KEY (applicationid) REFERENCES admissions_application(id) 
); 

CREATE TABLE academicinfo ( 
    degreeapplyingfor VARCHAR(30), 
    gretotal          INT CHECK (GREtotal >= 0 AND GREtotal <= 340), 
    greverbal         INT CHECK (GREverbal >= 130 AND GREverbal <= 170), 
    greanalytical     DECIMAL(2,1) CHECK (GREanalytical >= 0 AND GREanalytical <= 6), 
    grequantitive     INT CHECK (GREquantitive >= 130 AND GREquantitive <= 170), 
    gredate           DATE, 
    greadvscore       INT CHECK (GREadvScore >= 0 AND GREadvScore <= 1000), 
    gresubj           VARCHAR(30), 
    greadvdate        DATE, 
    toeflscore        INT, 
    toefldate         DATE, 
    bachgpa           VARCHAR(4), 
    bachmajor         VARCHAR(30), 
    bachyear          YEAR, 
    bachuni           VARCHAR(30), 
    masgpa            VARCHAR(4), 
    masmajor          VARCHAR(30), 
    masyear           YEAR, 
    masuni            VARCHAR(30), 
    areaofint         VARCHAR(300), 
    experience        VARCHAR(300), 
    applicationid     INT NOT NULL PRIMARY KEY, 
    FOREIGN KEY (applicationid) REFERENCES admissions_application(id) 
); 

CREATE TABLE courses (
    courseid       INT PRIMARY KEY;
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
    studentid      INT
    dept           VARCHAR(4),
    coursenum      INT,
    professorid    INT,
    year           YEAR,
    semester       VARCHAR(30),
    grade          VARCHAR(2),
);

CREATE TABLE prereqs (
    courseid    INT,
    prereqid    INT,
    FOREIGN KEY (courseid) REFERENCES courses(courseid),
    FOREIGN KEY (prereqid) REFERENCES courses(courseid)    
);

CREATE TABLE advises (
    studentid   INT,
    facultyid   INT,
    hold        VARCHAR(30),
    degree_name VARCHAR(30),
    PRIMARY KEY (studentid),
    FOREIGN KEY (studentid) REFERENCES personalinfo(id),
    FOREIGN KEY (facultyid) REFERENCES personalinfo(id)
);

CREATE TABLE graduation_application (
    firstname VARCHAR(30),
    lastname  VARCHAR(30),
    studentid INT,
    courseid  VARCHAR(10),
    year      YEAR,
    PRIMARY KEY(courseid)
    FOREIGN KEY(courseid) REFERENCES courses(courseid)
    FOREIGN KEY(studentid) REFERENCES users(id) 
);

CREATE TABLE degree_requirements (
    degree_name VARCHAR(30),
    courseid    INT,
    FOREIGN KEY(courseid) REFERENCES courses(courseid)
);
