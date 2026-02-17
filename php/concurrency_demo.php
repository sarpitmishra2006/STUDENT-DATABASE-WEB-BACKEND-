<?php
require_once 'db_config_student.php';
$student_id = isset($_GET['sid']) ? intval($_GET['sid']) : 1;
echo "<h2>Concurrency Demo for StudentID = $student_id</h2>";

$stu_conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

echo "Transaction started. Attempting to lock student row...<br>";
$lockRes = $stu_conn->query("SELECT * FROM Students WHERE StudentID = $student_id FOR UPDATE");

if($lockRes && $lockRes->num_rows){
    $row = $lockRes->fetch_assoc();
    echo "Locked Student: ".htmlspecialchars($row['Name'])."<br>";
    echo "Simulating long operation (sleep 10s). While this runs, open another tab and try to edit/delete same student.<br>";
    sleep(10);

    $newPhone = '9'.mt_rand(100000000,999999999);
    $stu_conn->query("UPDATE Students SET Phone = '".$stu_conn->real_escape_string($newPhone)."' WHERE StudentID = $student_id");
    echo "Updated Phone to $newPhone<br>";
    $stu_conn->commit();
    echo "Committed transaction. Lock released.<br>";
} else {
    $stu_conn->rollback();
    echo "Student not found or lock failed.<br>";
}
?>
