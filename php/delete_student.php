<?php
require_once 'db_config_student.php';
$id = intval($_GET['id'] ?? 0);
if($id <= 0){ header('Location: ../public/index.php'); exit; }

$stu_conn->begin_transaction();
try {
    $stmt = $stu_conn->prepare("DELETE FROM Enrollment WHERE StudentID = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    $stmt2 = $stu_conn->prepare("DELETE FROM Students WHERE StudentID = ?");
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $stmt2->close();

    $stu_conn->commit();
    header('Location: ../php/view_students.php');
    exit();
} catch(Exception $e){
    $stu_conn->rollback();
    die("Delete failed: ".$e->getMessage());
}
?>
