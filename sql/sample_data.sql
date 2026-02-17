USE Academic_DB;
INSERT INTO Department (DeptName) VALUES ('CSE'), ('ECE'), ('MECH');

INSERT INTO Courses (CourseName, DeptID, Semester) VALUES
('Data Structures', (SELECT DeptID FROM Department WHERE DeptName='CSE'), 3),
('Database Systems', (SELECT DeptID FROM Department WHERE DeptName='CSE'), 4),
('Signals & Systems', (SELECT DeptID FROM Department WHERE DeptName='ECE'), 3);

USE Student_DB;
INSERT INTO Students (Name, Gender, DeptID, Email, Phone, DOB)
VALUES ('Arpit Mishra','M', (SELECT DeptID FROM Academic_DB.Department WHERE DeptName='CSE'), 'arpit@example.com','9999999999','2004-03-10');

INSERT INTO Enrollment (StudentID, CourseID, Semester)
VALUES ((SELECT StudentID FROM Students WHERE Email='arpit@example.com'),
        (SELECT CourseID FROM Academic_DB.Courses WHERE CourseName='Data Structures'),
        3);
