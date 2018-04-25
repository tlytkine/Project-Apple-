DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS transcripts;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS personalinfo;
DROP TABLE IF EXISTS admissions_application;
DROP TABLE IF EXISTS review;
DROP TABLE IF EXISTS recommendation;
DROP TABLE IF EXISTS documentstatus;
DROP TABLE IF EXISTS academicinfo;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS transcripts;
DROP TABLE IF EXISTS prereqs;

CREATE TABLE users (
    email    VARCHAR(254) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    id		 INT NOT NULL UNIQUE AUTO_INCREMENT
  );

insert into users values('gs@gs.com', 'password', '1');
insert into users values('admin@admin.com', 'password', '2');
insert into users values('prof@prof.com', 'password', '3');
insert into users values('student@student.com', 'password', '4');

CREATE TABLE roles (
	id   INT,
	role VARCHAR(30)
	/*FOREIGN KEY (id) REFERENCES users(id)*/
);

insert into roles values('1', 'gs');
insert into roles values('2', 'admin');
insert into roles values('3', 'professor');
insert into roles values('4', 'student');

CREATE TABLE personalinfo (
	id                INT PRIMARY KEY,
    firstname         VARCHAR(30),
    lastname          VARCHAR(30),
    dob               DATE,
    address           VARCHAR (100),
    ssn               VARCHAR (11)
    /*FOREIGN KEY (id) REFERENCES users(id)*/
);

insert into personalinfo values('3', 'b', 'narahari', '2000-01-01', '123 address st', '123-45-6789');
insert into personalinfo values('4', 'billy', 'miller', '2000-01-01', '123 address st', '123-45-6789');

CREATE TABLE admissions_application (
    id               INT PRIMARY KEY AUTO_INCREMENT,
    email            VARCHAR(254) NOT NULL,
    reviewerusername VARCHAR(254),
    status           VARCHAR(20) NOT NULL,
    finaldecision    INT NOT NULL,
    semester         VARCHAR(10),
    year             YEAR
    /*FOREIGN KEY (email) REFERENCES users(email)*/
);

CREATE TABLE review (
    applicationid INT PRIMARY KEY,
    decision      INT,
    defcourse     VARCHAR(100),
    comments      VARCHAR(100),
    reasons       VARCHAR(100)
    /*FOREIGN KEY (applicationid) REFERENCES admissions_application(id)*/
);

CREATE TABLE recommendation (
    recommendationid INT PRIMARY KEY AUTO_INCREMENT,
    applicationid    INT NOT NULL,
    writername       VARCHAR(30),
    writeremail      VARCHAR(254),
    affiliation      VARCHAR(30),
    rating           INT CHECK (Rating >= 1 AND Rating <= 6),
    genericrating    BOOLEAN,
    crediblerating   BOOLEAN
    /*FOREIGN KEY (applicationid) REFERENCES admissions_application(id)*/
);

CREATE TABLE documentstatus (
    applicationid         INT NOT NULL PRIMARY KEY,
    applicationsubmitted  BOOLEAN,
    transcriptrecieved    BOOLEAN,
    letterofrecrecieved   BOOLEAN,
    personalinfosubmitted BOOLEAN
    /*FOREIGN KEY (applicationid) REFERENCES admissions_application(id)*/
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
    applicationid     INT NOT NULL PRIMARY KEY
    /*FOREIGN KEY (applicationid) REFERENCES admissions_application(id)*/
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
    professorid    INT
    /*FOREIGN KEY (professorid) REFERENCES users(id)*/
);

insert into courses values ('1', 'CS','6221', '1', 'class', '3', 'M', '15:00:00', '2018','spring', '3');
insert into courses values ('2', 'CS','6222', '1', 'class', '3', 'M', '12:00:00', '2018','spring', '3');


CREATE TABLE transcripts (
    studentid      INT,
    dept           VARCHAR(4),
    coursenum      INT,
    professorid    INT,
    year           YEAR,
    semester       VARCHAR(30),
    grade          VARCHAR(2)
);

insert into transcripts values('4', 'CS', '6221', '3', '2018', 'spring', 'A');

CREATE TABLE prereqs (
	courseid       INT,
	prereqid	   INT
);

insert into prereqs values('2', '1');
