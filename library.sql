use svoznyuk;
DROP TABLE IF EXISTS Book, PhysicalCopy,Loan, Reader;

Create Table Book (
 ISBN int primary key,
 title varchar(50),
 author varchar (50),
 publisher varchar (50)
 );
 INSERT INTO Book Values
(100001, 'Computer Architecture', 'Hennessy', 'McGrawHill'),
				(100002, 'Introduction to Algorithms', 'Cormen', 'MIT Press'),
				(100003, 'Mastering Linux', 'Wang', 'Elsevier'),
					(100004, 'Introduction to Java Programming', 'Liang', 'Prentice Hall'),
			(100005, 'Systems Architecture', 'Burd', 'Course Technology');

CREATE TABLE PhysicalCopy(
 catalogNo int primary key,
 title varchar(50),
 overdueChargePerDay int
 );
 
 INSERT INTO PhysicalCopy Values
(2010,'Computer Architecture',1),
(2011,'Computer Architecture',1),
(2012,'Computer Architecture',1),
(2021,'Introduction to Algorithms',2),

(2022,'Introduction to Algorithms',2),
(2023,'Introduction to Algorithms',2),
(2031,'Mastering Linux',3),
(2032,'Mastering Linux',3),
(2033,'Mastering Linux',3),

(2041,'Introduction to Java Programming',4),
(2042,'Introduction to Java Programming',4),
(2043,'Introduction to Java Programming',4),
(2051,'Systems Architecture',5),

(2052,'Systems Architecture',5),
(2053,'Systems Architecture',5);

create table Reader (
 userName varchar(50) primary key,
 userCity varchar(50),
 userEmail varchar(50),
 telephone varchar(15)
);
INSERT INTO Reader Values
('Abe', 'Lincoln','Abe@unl.edu',4020000001),
('Bob', 'Omaha','Bob@unl.edu',4020000002),
('Chuck', 'Lincoln','Chuck@unl.edu',4020000003),
('David','Kearney','David@unl.edu',4020000004);

create table Loan (
 userName varchar(50),
 catalogNo int,
 dateOut date,
 dateIn date null,
 dueDate date,
 foreign key(userName) REFERENCES Reader(userName) ON DELETE RESTRICT,
 PRIMARY KEY(userName, catalogNo, dateOut)
);
INSERT INTO Loan Values
('Abe',2010,'2012-07-01','2012-09-01','2012-08-01'),
('Abe',2041,'2012-09-01',null,'2012-10-01'),
('Abe',2032,'2012-09-01','2012-09-15','2012-10-01'),
					('Bob',2011,'2012-08-01','2012-09-15','2012-09-01'),
('Bob',2023,'2012-09-01','2012-09-15','2012-10-01'),
('Bob',2041,'2012-09-01',null,'2012-10-01'),
					
('Chuck',2051,'2012-09-01','2012-09-15','2012-10-01'),
('Chuck',2031,'2012-09-01','2012-10-15','2012-10-01'),			
('David',2052,'2012-09-01','2012-10-10','2012-10-01'),
('David',2022,'2012-08-01','2012-09-15','2012-09-01');