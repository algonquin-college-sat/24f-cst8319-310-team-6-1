/* SQL2 */
	
  DROP DATABASE IF EXISTS project1;
  CREATE DATABASE project1;
  
  GRANT ALL  PRIVILEGES ON project1.* TO "root"@"localhost";

  USE project1;

  /* Create Users Table */
DROP TABLE IF EXISTS employer;
DROP TABLE IF EXISTS gigworker;
  CREATE TABLE IF NOT EXISTS employert (
    id int NOT NULL AUTO_INCREMENT,
    phone varchar(128) NOT NULL,
    country varchar(128),
    city varchar(128),
    province varchar(128),
    domain varchar(128),
    description1 varchar(500),
    PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
   CREATE TABLE IF NOT EXISTS gigworkert (
    id int NOT NULL AUTO_INCREMENT,
	phone varchar(128) NOT NULL,
    country varchar(128),
    city varchar(128),
    province varchar(128),
    skills varchar(128),
    experience varchar(128),
    domain varchar(128),
    availability varchar(128),
	wage int,
    PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  CREATE TABLE IF NOT EXISTS account (
    userName varchar(128) NOT NULL,
    userEmail varchar(128) NOT NULL,
    userPWD varchar(128) NOT NULL,
    userType char(1) NOT NULL,
    url varchar (128),
    profile varchar (128),
    gigworkertKey int,
    employertKey int,
    FOREIGN KEY (gigworkertKey) REFERENCES gigworkert(id),
    FOREIGN KEY (employertKey) REFERENCES employert(id),
    PRIMARY KEY (userName)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE VIEW employer as SELECT * FROM account a INNER JOIN employert e on a.employertKey=e.id;
CREATE VIEW gigworker as SELECT * FROM account a INNER JOIN gigworkert w on a.gigworkertKey=w.id;


CREATE TABLE IF NOT EXISTS newchat (
  id int NOT NULL AUTO_INCREMENT,
  message text NOT NULL,
  `from` varchar(128) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

 CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userName VARCHAR(50) NOT NULL,    
    company VARCHAR(50) NOT NULL,
    rating VARCHAR(10) NOT NULL,
    description TEXT NOT NULL
);
  
CREATE TABLE gigs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    domain VARCHAR(50) NOT NULL,
    company VARCHAR(50) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    hourly_paid DECIMAL(8,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS project1.interested_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    domain VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    duration VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    hourly_paid VARCHAR(255) NOT NULL
);
