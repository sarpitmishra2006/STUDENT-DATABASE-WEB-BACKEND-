<?php
require_once 'db_config_student.php';
require_once 'db_config_academic.php';

// sanitize inputs
$name = $stu_conn->real_escape_string(trim($_POST['name'] ?? ''));
$dept_id = intval($_POST['dept_id'] ?? 0);
$dob = $_POST['dob'] ?? NULL;
$gender = $stu_conn->real_escape_string($_POST['gender'] ?? '');
$email = $stu_conn->real_escape_string($_POST['email'] ?? '');
$phone = $stu_conn->real_escape_string($_POST['phone'] ?? '');
$semester = intval($_POST['semester'] ?? 0);
$course_name = trim($_POST['course'] ?? '');

// Basic validation
if(empty($name) || $dept_id <= 0){
    die("Name and Department are required. <a href='../public/index.php'>Back</a>");
}

$stu_conn->begin_transaction();
try {
    // Insert student
    $stmt = $stu_conn->prepare("INSERT INTO Students (Name, Gender, DeptID, Email, Phone, DOB) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssisss', $name, $gender, $dept_id, $email, $phone, $dob);
    $stmt->execute();
    $student_id = $stmt->insert_id;
    $stmt->close();

    // If course provided, find course id in Academic_DB
    if(!empty($course_name)){
        $cstmt = $acad_conn->prepare("SELECT CourseID FROM Courses WHERE CourseName = ? AND DeptID = ?");
        $cstmt->bind_param('si', $course_name, $dept_id);
        $cstmt->execute();
        $cstmt->bind_result($course_id_found);
        if($cstmt->fetch()){
            $cstmt->close();
            $est = $stu_conn->prepare("INSERT INTO Enrollment (StudentID, CourseID, Semester) VALUES (?, ?, ?)");
            $est->bind_param('iii', $student_id, $course_id_found, $semester);
            $est->execute();
            $est->close();
        } else {
            $cstmt->close();
            // optional: create course in Academic_DB
            $acad_conn->query("INSERT INTO Courses (CourseName, DeptID, Semester) VALUES ('".$acad_conn->real_escape_string($course_name)."', $dept_id, $semester)");
            $course_id_new = $acad_conn->insert_id;
            if($course_id_new){
                $est = $stu_conn->prepare("INSERT INTO Enrollment (StudentID, CourseID, Semester) VALUES (?, ?, ?)");
                $est->bind_param('iii', $student_id, $course_id_new, $semester);
                $est->execute();
                $est->close();
            }
        }
    }

    $stu_conn->commit();
    header('Location: ../public/index.php?success=1');
    exit();
} catch(Exception $e){
    $stu_conn->rollback();
    die("Error: ".$e->getMessage());
}
?>
