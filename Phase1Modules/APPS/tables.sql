DROP TABLE IF EXISTS users CASCADE; 

DROP TABLE IF EXISTS review CASCADE; 

DROP TABLE IF EXISTS recommendation CASCADE; 

DROP TABLE IF EXISTS documentstatus CASCADE; 

DROP TABLE IF EXISTS personalinfo CASCADE; 

DROP TABLE IF EXISTS academicinfo CASCADE; 

DROP TABLE IF EXISTS application CASCADE; 

CREATE TABLE users 
  ( 
     email    VARCHAR(254) NOT NULL PRIMARY KEY, 
     password VARCHAR(255) NOT NULL, 
     type     VARCHAR(30) 
  ); 

CREATE TABLE application 
  ( 
     id               INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
     email            VARCHAR(254) NOT NULL, 
     reviewerusername VARCHAR(254), 
     status           VARCHAR(20) NOT NULL, 
     finaldecision    INT NOT NULL, 
     semester         VARCHAR(10), 
     year             YEAR 
  ); 

CREATE TABLE review 
  ( 
     decision      INT, 
     applicationid INT NOT NULL PRIMARY KEY, 
     defcourse     VARCHAR(100), 
     comments      VARCHAR(100), 
     reasons       VARCHAR(100), 
     FOREIGN KEY (applicationid) REFERENCES application(id) 
  ); 

CREATE TABLE recommendation 
  ( 
     applicationid    INT NOT NULL, 
     recommendationid INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
     writername       VARCHAR(30), 
     writeremail      VARCHAR(254), 
     affiliation      VARCHAR(30), 
     rating           INT CHECK (Rating >= 1 AND Rating <= 6), 
     genericrating    BOOLEAN, 
     crediblerating   BOOLEAN, 
     FOREIGN KEY (applicationid) REFERENCES application(id) 
  ); 

CREATE TABLE documentstatus 
  ( 
     applicationid         INT NOT NULL PRIMARY KEY, 
     applicationsubmitted  BOOLEAN,
     transcriptrecieved    BOOLEAN, 
     letterofrecrecieved   BOOLEAN, 
     personalinfosubmitted BOOLEAN, 
     FOREIGN KEY (applicationid) REFERENCES application(id) 
  ); 
  
CREATE TABLE personalinfo 
  ( 
     firstname         VARCHAR(30), 
     lastname          VARCHAR(30), 
     dob               DATE, 
     address           VARCHAR (100), 
     ssn               VARCHAR (11),
     applicationid     INT NOT NULL PRIMARY KEY, 
     FOREIGN KEY (applicationid) REFERENCES application(id) 
  ); 

CREATE TABLE academicinfo 
  ( 
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
     FOREIGN KEY (applicationid) REFERENCES application(id) 
  ); 

INSERT INTO users (email, password, type) VALUE('admin@gwu.edu', '$2y$10$K7xpP4XPPWkgYJ0/I4XOtehbRigHUqpmXer99/Ftx1fERDU.JZObC', 'System Administrator');

INSERT INTO users (email, password, type) VALUE ('johnlennon@gwu.edu', '$2y$10$fa75JvRBVhb9MlviEi0.COVVFkyxf10hOaJggCOYY5MgERIAK.RHm', 'Applicant');

INSERT INTO application (id, email, status, finaldecision, semester, year) VALUE (1, 'johnlennon@gwu.edu', 'Complete', 0, 'Fall', 2018);

INSERT INTO documentstatus (applicationid, applicationsubmitted, transcriptrecieved, letterofrecrecieved, personalinfosubmitted) VALUE (1, 1, 1, 1, 1);

INSERT INTO personalinfo (firstname, lastname, dob, address, ssn, applicationid) VALUE ('John', 'Lennon', '1995-03-17', '800 22nd St NW, Washington, DC', '111-11-1111', 1);

INSERT INTO academicinfo (degreeapplyingfor, gretotal, greverbal, greanalytical, grequantitive, gredate, bachgpa, bachmajor, bachyear, bachuni, applicationid) VALUE ('Ph.D.', 300, 150, 4, 150, '2018-01-05', '3.9', 'Computer Science', 2018, 'George Washington University', 1);

INSERT INTO recommendation (applicationid, recommendationid, writername, writeremail, affiliation) VALUE (1, NULL, 'John Smith', 'smith@gwu.edu', 'Advisor');

INSERT INTO recommendation (applicationid, recommendationid, writername, writeremail, affiliation) VALUE (1, NULL, 'Jill Peters', 'jpeters@gwu.edu', 'Professor');

INSERT INTO recommendation (applicationid, recommendationid, writername, writeremail, affiliation) VALUE (1, NULL, 'Peter Jills', 'pjills@gwu.edu', 'Professor');

INSERT INTO users (email, password, type) VALUE ('rstarr@gwu.edu', '$2y$10$ocPe8Z1Vr1hMF7MwJmhQYuz./LH5WsR28JWw1yESK7dY6uMXVCbxe', 'Applicant');

INSERT INTO application (id, email, status, finaldecision) VALUE (2, 'rstarr@gwu.edu', 'Incomplete', 0);

INSERT INTO documentstatus (applicationid, applicationsubmitted, transcriptrecieved, letterofrecrecieved, personalinfosubmitted) VALUE (2, 0, 0, 0, 1);

INSERT INTO personalinfo (firstname, lastname, dob, address, ssn, applicationid) VALUE ('Ringo', 'Starr', '1993-05-24', '2100 H St NW, Washington, DC', '222-11-1111', 2);

INSERT INTO academicinfo (applicationid) VALUE (2);

INSERT INTO recommendation (applicationid) VALUE (2);

INSERT INTO recommendation (applicationid) VALUE (2);

INSERT INTO recommendation (applicationid) VALUE (2);

INSERT INTO users (email, password, type) VALUE ('gs@gwu.edu', '$2y$10$Dntyy58tka97rsnu94W0yOk6/hqWFcRJ42fb/nIl2/j5D7oaB0Fvm', 'Grad Secretary');

INSERT INTO users (email, password, type) VALUE ('narahari@gwu.edu', '$2y$10$KsVpKAQb3hUxEI/ZG8l8kuHGnKpEARzA394t2XpTIwB/SAYCy9rWe', 'Faculty Reviewer');

INSERT INTO users (email, password, type) VALUE ('timwood@gwu.edu', '$2y$10$qfiC.rg3soLUuWloj.p.h.KvjbbsMeI17ASSxHQrJyIVzLeWFM2Da', 'Faculty Reviewer');

INSERT INTO users (email, password, type) VALUE ('cac@gwu.edu', '$2y$10$1DLEPkpZOz6oO2j5mqBQyuSEfqXeIhLTY1JCdgdzXXNTJ4KBjrX9W', 'CAC');

--DELIMITER CHANGE BELOW;

---TRIGGERS---

delimiter $$ 
CREATE TRIGGER statuscheck after 
  UPDATE 
  ON documentstatus FOR EACH row begin IF (new.applicationsubmitted = 1 
  AND    new.letterofrecrecieved = 1 
  AND    new.personalinfosubmitted = 1 
  AND    new.transcriptrecieved = 1) THEN 
  UPDATE application 
  SET    status = 'Complete' 
  WHERE  id = new.applicationid; 
  ELSE 
  UPDATE application 
  SET    status = 'Incomplete' 
  WHERE  id = new.applicationid;END IF;END$$ 
delimiter ;

--STOP
