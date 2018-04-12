DROP TABLE IF EXISTS prereq1;
DROP TABLE IF EXISTS prereq2;
DROP TABLE IF EXISTS transcripts;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS professors;
DROP TABLE IF EXISTS users;


CREATE TABLE IF NOT EXISTS users(
    username varchar(15),
    password varchar(80) NOT NULL,
    role varchar(10) NOT NULL,
    primary key (username),
    constraint chk_role check (role IN ('student', 'gs', 'professor', 'admin', 'inactive'))
);

CREATE TABLE IF NOT EXISTS students (
	SSN INT (15) NOT NULL,
	ID INT (15) NOT NULL,
  	fname Varchar(20),
   	lname Varchar(20) NOT NULL,
  	street Varchar(50),
	city Varchar(20),
	email Varchar(50),
	degree Varchar (50),
	username Varchar(50)
);

CREATE TABLE IF NOT EXISTS professors (
	ID INT (5) NOT NULL,
	name Varchar(50),
	email Varchar(50),
	username Varchar(50)
);

CREATE TABLE IF NOT EXISTS courses (
	CRN INT (5),
	CID INT (5) NOT NULL,
	dept Varchar (50) NOT NULL,
	profID INT (5),
	year INT(5),
	semester Varchar(30),
	sectionNum INT(5),
	cHours INT(5),
	day Varchar(20) NOT NULL,
	classTime TIME,
	CHECK (cHours <= 3)
);

CREATE TABLE IF NOT EXISTS transcripts (
	SID INT (5) NOT NULL,
	CID INT (5) NOT NULL,
	dept Varchar (50) NOT NULL,
	profID INT (5),
	year INT(5) NOT NULL,
	semester Varchar(30) NOT NULL,
	sectionNum INT(5),
	grade Varchar(30) NOT NULL,
	constraint chk_grade check (grade IN ('A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'F', 'IP'))
);

CREATE TABLE IF NOT EXISTS prereq1 (
	CRN INT (5) NOT NULL,
	preCRN INT (5) NOT NULL
);

CREATE TABLE IF NOT EXISTS prereq2 (
	CRN INT (5) NOT NULL,
	preCRN INT (5) NOT NULL
);

/*primary key*/
ALTER TABLE students
ADD PRIMARY KEY (ID);

ALTER TABLE professors
ADD PRIMARY KEY (ID);

ALTER TABLE courses
ADD PRIMARY KEY (CRN);

ALTER TABLE transcripts
ADD CONSTRAINT PK_transcripts PRIMARY KEY (SID, CID, dept, year, semester);

ALTER TABLE prereq1
ADD PRIMARY KEY (CRN);

ALTER TABLE prereq2
ADD PRIMARY KEY (CRN);

/*foreign key*/
ALTER TABLE transcripts
ADD FOREIGN KEY(SID) REFERENCES students(ID);

ALTER TABLE transcripts
ADD FOREIGN KEY(profID) REFERENCES professors(ID);

ALTER TABLE courses
ADD FOREIGN KEY(profID) REFERENCES professors(ID);

ALTER TABLE prereq1
ADD FOREIGN KEY (CRN) REFERENCES courses (CRN);

ALTER TABLE professors
ADD FOREIGN KEY (username) REFERENCES users (username);

ALTER TABLE students
ADD FOREIGN KEY (username) REFERENCES users (username);


ALTER TABLE prereq2
ADD FOREIGN KEY (CRN) REFERENCES courses (CRN);


insert into users values('allison', 'password', 'student');
insert into users values('billy', 'password', 'student');
insert into users values('braden', 'password', 'student');
insert into users values('beatleboy1', 'password', 'student');
insert into users values('beatleboy2', 'password', 'student');

insert into users values('narahari', 'password', 'professor');
insert into users values('cutello', 'password', 'professor');
insert into users values('simha', 'password', 'professor');

insert into users values('gs', 'password', 'gs');
insert into users values('admin', 'password', 'admin');

insert into students values ('111223333','111111111', 'Allison', 'DeCicco', '4th Place', 'Windermere', 'ad@gwu.edu', 'MS', 'allison');
insert into students values ('111224444','111111112', 'Billy', 'Miller', '5th Place', 'Windermere', 'bm@gwu.edu', 'MS', 'billy');
insert into students values ('111225555','111111113', 'Braden', 'Meyer', '6th Place', 'Windermere', 'bam@gwu.edu', 'MS', 'braden');
insert into students values ('111226666','333111111', 'Paul', 'McCartney', '13th Place', 'London', 'pm@gwu.edu', 'MS', 'beatleboy1');
insert into students values ('111227777','444111111', 'George', 'Harrison', '14th Place', 'London', 'gh@gwu.edu', 'MS', 'beatleboy2');

insert into professors values ('1', 'Narahari', 'narahari@gw.edu', 'narahari');
insert into professors values ('2', 'Cutello', 'cutello@gw.edu', 'cutello');
insert into professors values ('3', 'Simha', 'simha@gw.edu', 'simha');


insert into courses values ('1','6221', 'CS', '1', '2018','spring', '10', '3','M', '15:00:00');
insert into courses values ('2','6461', 'CS', '1', '2018','spring', '10','3', 'T', '15:00:00');
insert into courses values ('3','6212', 'CS', '1', '2018','spring', '10','3', 'W', '15:00:00');
insert into courses values ('4','6220', 'CS', '1', '2018','spring', '10','3', 'R', '15:00:00');
insert into courses values ('5','6232', 'CS', '1', '2018','spring', '10', '3','M','18:00:00');
insert into courses values ('6','6233', 'CS', '1', '2018','spring', '10', '3','T', '18:00:00');
insert into courses values ('7','6241', 'CS', '1', '2018','spring', '10','3', 'W', '18:00:00');
insert into courses values ('8','6242', 'CS', '1', '2018','spring', '10','3', 'R', '18:00:00');
insert into courses values ('9','6246', 'CS', '2', '2018','spring', '10', '3','T', '15:00:00');
insert into courses values ('10','6251', 'CS', '2', '2018','spring', '10', '3','M', '18:00:00');
insert into courses values ('11','6254', 'CS', '2', '2018','spring', '10','3', 'M', '15:00:00');
insert into courses values ('12','6260', 'CS', '2', '2018','spring', '10', '3','R', '18:00:00');
insert into courses values ('13','6262', 'CS', '2', '2018','spring', '10','3', 'W', '18:00:00');
insert into courses values ('14','6283', 'CS', '2', '2018','spring', '10', '3','T', '18:00:00');
insert into courses values ('15','6284', 'CS', '2', '2018','spring', '10', '3','M', '18:00:00');
insert into courses values ('16','6286', 'CS', '2', '2018','spring', '10', '3','W', '18:00:00');
insert into courses values ('17','6325', 'CS', '2', '2018','spring', '10','2', 'R', '16:00:00');
insert into courses values ('18','6339', 'CS', '3', '2018','spring', '10', '2','T', '15:00:00');
insert into courses values ('19','6384', 'CS', '3', '2018','spring', '10', '3','W', '16:00:00');
insert into courses values ('20','6243', 'EE', '3', '2018','spring', '10', '3','M', '18:00:00');
insert into courses values ('21','6244', 'EE', '3', '2018','spring', '10', '2','T', '18:00:00');
insert into courses values ('22','6210', 'Math', '3', '2018','spring', '10', '2','W', '18:00:00');

insert into transcripts values('333111111','6221','CS','1','2018','fall','10','A');
insert into transcripts values('333111111','6461','CS','1','2018','fall','10','A');
insert into transcripts values('333111111','6212','CS','1','2018','fall','10','A');
insert into transcripts values('333111111','6220','CS','1','2018','fall','10','A');
insert into transcripts values('333111111','6232','CS','1','2018','fall','10','A');
insert into transcripts values('333111111','6233','CS','1','2018','fall','10','B');
insert into transcripts values('333111111','6241','CS','1','2018','fall','10','B');
insert into transcripts values('333111111','6246','CS','2','2018','fall','10','B');
insert into transcripts values('333111111','6262','CS','2','2018','fall','10','B');
insert into transcripts values('333111111','6283','CS','2','2018','fall','10','B');
insert into transcripts values('444111111','6221','CS','1','2018','fall','10','B');
insert into transcripts values('444111111','6461','CS','1','2018','fall','10','B');
insert into transcripts values('444111111','6212','CS','1','2018','fall','10','B');
insert into transcripts values('444111111','6232','CS','1','2018','fall','10','B');
insert into transcripts values('444111111','6233','CS','1','2018','fall','10','B');
insert into transcripts values('444111111','6241','CS','1','2018','fall','10','B');
insert into transcripts values('444111111','6242','CS','1','2018','fall','10','B');
insert into transcripts values('444111111','6244','EE','3','2018','fall','10','C');
insert into transcripts values('444111111','6283','CS','2','2018','fall','10','B');

insert into prereq1 values('6','5');
insert into prereq1 values('8','7');
insert into prereq1 values('12','1');
insert into prereq1 values('10','2');
insert into prereq1 values('11','1');
insert into prereq1 values('14','3');
insert into prereq1 values('15','3');
insert into prereq1 values('17','3');
insert into prereq1 values('9','2');
insert into prereq1 values('16','14');
insert into prereq1 values('19','15');
insert into prereq1 values('18','2');

insert into prereq2 values('9','3');
insert into prereq2 values('16','5');
insert into prereq2 values('19','14');
insert into prereq2 values('18','3');
