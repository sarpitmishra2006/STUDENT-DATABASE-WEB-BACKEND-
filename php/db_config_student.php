<?php
// Student DB connection (logical node 2)
$stu_host = 'localhost';
$stu_user = 'root';
$stu_pass = '';
$stu_db   = 'Student_DB';

$stu_conn = new mysqli($stu_host, $stu_user, $stu_pass, $stu_db);
if ($stu_conn->connect_error) {
    die("Student DB Connection failed: " . $stu_conn->connect_error);
}
$stu_conn->set_charset("utf8mb4");
?>
