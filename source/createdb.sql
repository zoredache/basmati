  CREATE TABLE CLIENTS (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,				
    client_id		varchar(80) NOT NULL,
    client_pw		varchar(16),
    client_school	varchar(16),
    client_expdate	date,
    client_fullname	varchar(50),
    webinfo			mediumtext,
    webinfodate		date,
    LastUpdate		TIMESTAMP    
    );

 CREATE TABLE COURSEINFO (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cc			varchar(30) NOT NULL,
    schoolid		varchar(16) NOT NULL,
    facultyname 	varchar(50),
    facultycode		varchar(50),
    period		varchar(8),
    email		varchar(50),
    phone		varchar(20),
    misc		varchar(80),
    coursename		varchar(50),
    assignlist		mediumtext,
    assignvals		mediumtext,
    ealr		mediumtext,
    modified	varchar(50),
    type		varchar(2),
    LastUpdate	TIMESTAMP
    );


CREATE TABLE GMSCORES (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sid 		integer NOT NULL,
    cc			varchar(30) NOT NULL,
    schoolid		varchar(16) NOT NULL,
    scores		mediumtext,
    percent		real,
    grade		varchar(50),
    comments		varchar(50),
    LastUpdate	TIMESTAMP
    );


CREATE TABLE PRIVNOTES (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sid		integer NOT NULL,
    cc		varchar(30) NOT NULL,
    schoolid	varchar(16) NOT NULL,
    notes	mediumtext,
    LastUpdate  TIMESTAMP
);
	  

CREATE TABLE GROUPS (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    groupname 		varchar(20),
    groupsids	 	mediumtext,
    grouppw		varchar(16),
    schoolid		varchar(16),
    LastUpdate		TIMESTAMP
    );

CREATE TABLE PERSONAL (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sid 		integer NOT NULL,
    schoolid	 	varchar(16) NOT NULL,
    last		varchar(24),
    first		varchar(20),
    grade		varchar(2),
    password		varchar(16),
    did			mediumint,
    emailaddress	varchar(50),
    LastUpdate	TIMESTAMP   
    );

CREATE TABLE SCHOOLS (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    school_id 		varchar(16) NOT NULL,
    school_name	 	varchar(50),
    school_state	varchar(2),
    school_city		varchar(50),
    school_url		varchar(80),
    LastUpdate		TIMESTAMP
    );

CREATE TABLE EVENTLOG (
    id		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    eventid		varchar(6),
    schoolid		varchar(16),
    user		varchar(80),
    ipaddr		varchar(20),
    LastUpdate		TIMESTAMP
    );		

CREATE TABLE COMMENTLIST(
	id			INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	schoolid	varchar(16),
	commentnum	int,
	commenttxt	mediumtext,
	LastUpdate	TIMESTAMP
);

CREATE INDEX client_id_index ON CLIENTS(client_id);
CREATE INDEX courseinfo_cc_index ON COURSEINFO(cc);
CREATE INDEX courseinfo_schoolid_index ON COURSEINFO(schoolid);
CREATE INDEX gmscores_sid_index ON GMSCORES(sid);
CREATE INDEX gmscores_cc_index ON GMSCORES(cc);
CREATE INDEX gmscores_schoolid_index ON GMSCORES(schoolid);
CREATE INDEX personal_sid_index ON PERSONAL(sid);
CREATE INDEX personal_schoolid_index ON PERSONAL(schoolid);
CREATE INDEX schools_school_id_index ON SCHOOLS(school_id);
