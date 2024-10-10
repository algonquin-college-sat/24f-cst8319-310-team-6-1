/* SQL2 */
DROP TABLE IF EXISTS user_verification;
DROP TABLE IF EXISTS account;
DROP TABLE IF EXISTS employert;
DROP TABLE IF EXISTS gigworkert;
  DROP DATABASE IF EXISTS project1;
  CREATE DATABASE project1;
  
  GRANT ALL  PRIVILEGES ON project1.* TO "root"@"localhost";

  USE project1;

  /* Create Users Table */
  CREATE TABLE gigworker_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gigworker_id INT,
    day_of_week ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
    available_from TIME,
    available_to TIME
);



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
    workhistory varchar(5000),
    sunday varchar (1000),
    monday varchar (1000),
    tuesday varchar (1000),
    wednesday varchar (1000),
    thursday varchar (1000),
    friday varchar (1000),
    saturday varchar (1000),
     sunday2 text,
    monday2 text,
    tuesday2 text,
    wednesday2 text,
    thursday2 text,
    friday2 text,
    saturday2 text,
    PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  CREATE TABLE IF NOT EXISTS account (
    userName varchar(128) NOT NULL,
    userEmail varchar(128) NOT NULL,
    userPWD varchar(128) NOT NULL,
    userType char(1) NOT NULL,
    url varchar (128),
    profile varchar (512),
    document varchar (512),
    document2 varchar (512),
    validation varchar(512),
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
   `toUser` varchar(128) NOT NULL,
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
CREATE TABLE gigadvertisements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    domain VARCHAR(50) NOT NULL,
    gigworkername VARCHAR(50) NOT NULL,
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
    hourly_paid VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL
);
CREATE TABLE user_verification (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userName VARCHAR(128) NOT NULL,
    verified ENUM('yes', 'no') DEFAULT 'no'
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
DELIMITER //
CREATE TRIGGER after_insert_account
AFTER INSERT ON account
FOR EACH ROW
BEGIN
    -- Insert into user_verification
    INSERT INTO user_verification (userName, verified)
    VALUES (NEW.userName, 'no');  -- Assuming 'username' corresponds to 'userName' in account table
END;
//
DELIMITER ;
ALTER TABLE user_verification
ADD verificationCode VARCHAR(10) NOT NULL DEFAULT '0000000000';
