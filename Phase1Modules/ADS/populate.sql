DROP TABLE applications;
DROP TABLE advises;
DROP TABLE course_prereq;
DROP TABLE course_status;
DROP TABLE alumni_course_status;
DROP TABLE alumni;
DROP TABLE degrees;
DROP TABLE students;
DROP TABLE faculty;
DROP TABLE courses;
DROP TABLE grade_calc;
DROP TABLE login;


CREATE TABLE login (
	username varchar(16),
	password varchar(16) NOT NULL,
	role varchar(20) NOT NULL,
	PRIMARY KEY (username)
);

CREATE TABLE students (
	firstname varchar(30),
	lastname varchar(30),
	gwid varchar(20),
	ssn varchar(20),
	major varchar(10),
	cleared int,
	address varchar(50),
	email varchar(50),
	username varchar(16) NOT NULL,
	PRIMARY KEY (gwid),
	FOREIGN KEY (username) REFERENCES login(username)
);

CREATE TABLE applications (
	firstname varchar(30),
	lastname varchar(30),
	gwid varchar(20),
	crn varchar(10),
	PRIMARY KEY(crn)
);

CREATE TABLE faculty (
	firstname varchar(30),
	lastname varchar(30),
	fid varchar(20),
	ssn varchar(20),
	address varchar(50),
	username varchar(16) NOT NULL,
	PRIMARY KEY (fid),
	FOREIGN KEY (username) REFERENCES login(username)
);	

CREATE TABLE courses (
	coursenum int,
	crn varchar(10),
	title varchar(30),
	credithours int, 
	day char(1),
	_time varchar(30),
	PRIMARY KEY(coursenum)
);

CREATE TABLE advises (
	gwid varchar(20),
	fid varchar(20),
	hold varchar(30),
	PRIMARY KEY (gwid, fid),
	FOREIGN KEY (gwid) REFERENCES students(gwid),
	FOREIGN KEY (fid) REFERENCES faculty(fid)
);

CREATE TABLE course_prereq(
	coursenum int,
	main_prereq int,
	second_prereq int,
	PRIMARY KEY (coursenum),
	FOREIGN KEY (coursenum) REFERENCES courses(coursenum),
	FOREIGN KEY (main_prereq) REFERENCES courses(coursenum),
	FOREIGN KEY (second_prereq) REFERENCES courses(coursenum)
);

CREATE TABLE course_status (
	coursenum int,
	gwid varchar(20),
	grade varchar(2),
	PRIMARY KEY (coursenum, gwid),
	FOREIGN KEY (coursenum) REFERENCES courses(coursenum),
	FOREIGN KEY (gwid) REFERENCES students(gwid)
);

CREATE TABLE degrees (
	degree_name varchar(30),
	core1 int,
	core2 int,
	core3 int,
	PRIMARY KEY (degree_name),
	FOREIGN KEY (core1) REFERENCES courses(coursenum),
	FOREIGN KEY (core2) REFERENCES courses(coursenum),
	FOREIGN KEY (core3) REFERENCES courses(coursenum)
);

CREATE TABLE alumni (
	firstname varchar(30),
	lastname varchar(30),
	gwid varchar(20),
	ssn varchar(20),
	degree_name varchar(30),
	year int, 
	address varchar(50),
	email varchar(50),
	username varchar(16) NOT NULL,
	PRIMARY KEY (gwid),
	FOREIGN KEY (degree_name) REFERENCES degrees(degree_name),
	FOREIGN KEY (username) REFERENCES login(username)
);

CREATE TABLE alumni_course_status (
	coursenum int,
	gwid varchar(20),
	grade varchar(2),
	PRIMARY KEY (coursenum, gwid),
	FOREIGN KEY (coursenum) REFERENCES courses(coursenum),
	FOREIGN KEY (gwid) REFERENCES alumni(gwid)
);

CREATE TABLE grade_calc (
	grade varchar(2),
	qualitypoints decimal(4,3)
);

INSERT INTO grade_calc(grade, qualitypoints)
VALUES 
('A',4.00),
('A-',3.70),
('B+',3.30),
('B',3.00),
('B-',2.70),
('C+',2.30),
('C',2.00),
('F',0.00);

INSERT INTO courses(coursenum, crn, title, credithours, day, _time)
VALUES 
(1, 'CS6221', 'Software Paradigms', 3, 'M', '3-5:30pm'),
(2, 'CS6461', 'Computer Architecture', 3, 'T', '3-5:30pm'),
(3, 'CS6212', 'Algorithms', 3, 'W', '3-5:30pm'),
(4, 'CS6225', 'Data Compression', 3, 'R', '3-5:30pm'),
(5, 'CS6232', 'Networks 1', 3, 'M', '6-8:30pm'),
(6, 'CS6233', 'Networks 2', 3, 'T', '6-8:30pm'),
(7, 'CS6241', 'Database 1', 3, 'W', '6-8:30pm'),
(8, 'CS6242', 'Database 2', 3, 'R', '6-8:30pm'),
(9, 'CS6246', 'Compilers', 3, 'T', '3-5:30pm'),
(10, 'CS6251', 'Distributed Systems', 3, 'M', '6-8:30pm'),
(11, 'CS6254', 'Software Engineering', 3, 'M', '3-5:30pm'),
(12, 'CS6260', 'Multimedia', 3, 'R', '6-8:30pm'),
(13, 'CS6262', 'Graphics 1', 3, 'W', '6-8:30pm'),
(14, 'CS6283', 'Security 1', 3, 'T', '6-8:30pm'),
(15, 'CS6284', 'Cryptography', 3, 'M', '6-8:30pm'),
(16, 'CS6286', 'Network Security', 3, 'W', '6-8:30pm'),
(17, 'CS6325', 'Advanced Algorithms', 2, 'R', '4-6:30pm'),
(18, 'CS6339', 'Embedded Systems', 2, 'T', '3-5:30pm'),
(19, 'CS6384', 'Advanced Crypto', 3, 'W', '4-6:30pm'),
(20, 'EE6243', 'Communication Systems', 3, 'M', '6-8:30pm'),
(21, 'EE6244', 'Information Theory', 2, 'T', '6-8:30pm'),
(22, 'Math6210', 'Logic', 2, 'W', '6-8:30pm'), 
(23, "CS6220", null, 3, null, null), /* 6220 might be 6225, if so delete this line */ 
(24, "CS6384", null, 3, null, null);


INSERT INTO course_prereq VALUES (1, null, null);
INSERT INTO course_prereq VALUES (2, null, null);
INSERT INTO course_prereq VALUES (3, null, null);
INSERT INTO course_prereq VALUES (23, null, null); /* might be 6225, if so change to 4 */
INSERT INTO course_prereq VALUES (5, null, null);
INSERT INTO course_prereq VALUES (6, 5, null);
INSERT INTO course_prereq VALUES (7, null, null);
INSERT INTO course_prereq VALUES (8, 7, null);
INSERT INTO course_prereq VALUES (9, 7, 3);
INSERT INTO course_prereq VALUES (12, 1, null);
INSERT INTO course_prereq VALUES (10, 2, null);
INSERT INTO course_prereq VALUES (11, 1, null);
INSERT INTO course_prereq VALUES (13, null, null);
INSERT INTO course_prereq VALUES (14, 3, null);
INSERT INTO course_prereq VALUES (16, 14, 5);
INSERT INTO course_prereq VALUES (15, 3, null);
INSERT INTO course_prereq VALUES (17, 3, null);
INSERT INTO course_prereq VALUES (24, 15, 14);
INSERT INTO course_prereq VALUES (18, 2, 3);
INSERT INTO course_prereq VALUES (20, null, null);
INSERT INTO course_prereq VALUES (21, null, null);
INSERT INTO course_prereq VALUES (22, null, null);

INSERT INTO degrees(degree_name,core1,core2,core3)
VALUES ('ms_cs',1,2,3);

INSERT INTO login (username, password, role) VALUES 
('PaulMcCartney', 'Paul', 'STUDENT'),
('GeorgeHarrison', 'George', 'STUDENT'),
('EricClapton', 'Eric', 'ALUMNI'),
('Wood', 'wood', 'FACULTY_ADVISOR'),
('admin', 'password', 'SYSTEM_ADMIN'),
('Narahari', 'Narahari', 'GRAD_SECRETARY');


INSERT INTO students(firstname, lastname, gwid, ssn, major, cleared, username, address, email)
VALUES ('Paul','McCartney','G78798726','333-11-1111','CS',0, 'PaulMcCartney', 'address1', 'email1@gmail.com'),
('George','Harrison','G78798732', '444-11-1111','CS',0, 'GeorgeHarrison', 'address2', 'email2@gmail.com');

INSERT INTO alumni(firstname,lastname,gwid,ssn, degree_name,year,username, address, email)
VALUES ('Eric','Clapton','G34798778','555-11-1111',
	'ms_cs',2006, 'EricClapton', 'address3', 'email3@gmail.com');


INSERT INTO faculty 
VALUES ('Tim', 'Wood', 'G22222222','111-11-1111', '123 Fake Street', 'Wood');

INSERT INTO advises (gwid, fid) 
VALUES 
('G78798726', 'G22222222'),
('G78798732', 'G22222222');

INSERT INTO course_status(coursenum,gwid,grade)
VALUES (1,'G78798726','A'),
(2,'G78798726','A'),
(3,'G78798726','A'),
(23,'G78798726','A'),
(5,'G78798726','A'),
(6,'G78798726','B'),
(7,'G78798726','B'),
(9,'G78798726','B'),
(13,'G78798726','B'),
(14,'G78798726','B'),
(1,'G78798732','B'),
(2,'G78798732','B'),
(3,'G78798732','B'),
(5,'G78798732','B'),
(6,'G78798732','B'),
(7,'G78798732','B'),
(8,'G78798732','B'),
(21,'G78798732','C'),
(14,'G78798732','B');

INSERT INTO alumni_course_status(coursenum,gwid,grade)
VALUES (1,'G34798778','B'),
(2,'G34798778','B'),
(3,'G34798778','B'),
(5,'G34798778','B'),
(6,'G34798778','B'),
(7,'G34798778','B'),
(8,'G34798778','B'),
(14,'G34798778','A'),
(16,'G34798778','A'),
(11,'G34798778','A');

