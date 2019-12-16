CREATE DATABASE IF NOT EXISTS test_base;
USE test_base;

CREATE TABLE BadooUsers(
UserId INT NOT NULL AUTO_INCREMENT,
UserEmail VARCHAR(30) NOT NULL,
UserPass VARCHAR(10) NOT NULL,
PRIMARY KEY (UserId));

CREATE TABLE FacebookCredential(
UserEmail VARCHAR(30) NOT NULL,
FbLogin VARCHAR(20) NOT NULL,
FbPass VARCHAR(10) NOT NULL,
PRIMARY KEY (UserEmail));

CREATE TABLE UserData(
UserId INT NOT NULL,
Gender ENUM( 'male', 'female' ) NOT NULL,
Age INT NOT NULL,
UserName VARCHAR(20) NOT NULL,
PRIMARY KEY (UserId));

INSERT INTO BadooUsers(UserEmail,UserPass)
VALUES ('testmail_id11@mail.ru','qwe123QWE'),
('second_email','password2'),
('third_email','password3');

INSERT INTO FacebookCredential(UserEmail,FbLogin,FbPass)
VALUES ('testmail_id11@mail.ru','login1','pass1'),
('second_email','login2','pass2'),
('third_email','login3','pass3');

INSERT INTO UserData(UserId,Gender,Age,UserName)
VALUES (1,'male',28,'TestUserName'),
(2,'male',29,'Barney'),
(3,'female',26,'Robin');
