CREATE TABLE reg_hist (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    fullname varchar(50) NOT NULL,
    email varchar(50) NOT NULL,
    password varchar(50) NOT NULL,
    stream varchar(50) NOT NULL,
    approval varchar(50) NOT NULL
);

CREATE TABLE regd_studs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(100),
    stream varchar(50) NOT NULL,
    date VARCHAR(100)
);