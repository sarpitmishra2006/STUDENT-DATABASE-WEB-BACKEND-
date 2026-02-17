<?php
// Academic DB connection (logical node 1)
$acad_host = 'localhost';
$acad_user = 'root';
$acad_pass = ''; // default for XAMPP
$acad_db   = 'Academic_DB';

$acad_conn = new mysqli($acad_host, $acad_user, $acad_pass, $acad_db);
if ($acad_conn->connect_error) {
    die("Academic DB Connection failed: " . $acad_conn->connect_error);
}
$acad_conn->set_charset("utf8mb4");
?>
