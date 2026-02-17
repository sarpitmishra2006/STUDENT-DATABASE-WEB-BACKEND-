<?php
require_once 'db_config_student.php';
require_once 'db_config_academic.php';

$sql = "SELECT s.StudentID, s.Name, s.Gender, s.Email, s.Phone, s.DOB, d.DeptName,
        e.EnrollID, c.CourseName, e.Semester, e.Grade
        FROM Student_DB.Students s
        LEFT JOIN Academic_DB.Department d ON s.DeptID = d.DeptID
        LEFT JOIN Student_DB.Enrollment e ON s.StudentID = e.StudentID
        LEFT JOIN Academic_DB.Courses c ON e.CourseID = c.CourseID
        ORDER BY s.StudentID, e.EnrollID";

$res = $stu_conn->query($sql);
if(!$res) die("Query failed: ".$stu_conn->error);
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Students</title>
<link rel="stylesheet" href="../public/styles.css">
</head><body>
<div class="container">
  <h1>Student List</h1>
  <table>
    <thead>
      <tr><th>ID</th><th>Name</th><th>Dept</th><th>Course</th><th>Semester</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
    </thead>
    <tbody>
<?php
$lastId = 0;
while($row = $res->fetch_assoc()){
    echo "<tr>";
    echo "<td>".htmlspecialchars($row['StudentID'])."</td>";
    echo "<td>".htmlspecialchars($row['Name'])."</td>";
    echo "<td>".htmlspecialchars($row['DeptName'])."</td>";
    echo "<td>".htmlspecialchars($row['CourseName'])."</td>";
    echo "<td>".htmlspecialchars($row['Semester'])."</td>";
    echo "<td>".htmlspecialchars($row['Email'])."</td>";
    echo "<td>".htmlspecialchars($row['Phone'])."</td>";
    echo "<td><a href='edit_student.php?id=".$row['StudentID']."'>Edit</a> | <a href='delete_student.php?id=".$row['StudentID']."' onclick='return confirm(\"Delete?\")'>Delete</a></td>";
    echo "</tr>";
}
?>
    </tbody>
  </table>
  <br><a class="btn" href="../public/index.php">Back</a>
</div>
</body></html>
