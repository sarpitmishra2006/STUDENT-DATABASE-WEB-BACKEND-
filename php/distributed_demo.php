<?php
require_once 'db_config_student.php';
require_once 'db_config_academic.php';

// Cross-DB join to simulate distributed database
$sql = "SELECT s.StudentID, s.Name, d.DeptName, c.CourseName, e.Semester
        FROM Student_DB.Students s
        LEFT JOIN Student_DB.Enrollment e ON s.StudentID = e.StudentID
        LEFT JOIN Academic_DB.Courses c ON e.CourseID = c.CourseID
        LEFT JOIN Academic_DB.Department d ON s.DeptID = d.DeptID
        ORDER BY s.StudentID";

$res = $stu_conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Distributed DB Simulation</title>
<link rel="stylesheet" href="../public/styles.css">
</head>
<body>
<div class="container">
<h1>Distributed DB Simulation (Cross-DB Join)</h1>
<table>
  <tr>
    <th>StudentID</th>
    <th>Name</th>
    <th>Dept</th>
    <th>Course</th>
    <th>Semester</th>
  </tr>
<?php while($row = $res->fetch_assoc()): ?>
  <tr>
    <td><?php echo $row['StudentID']; ?></td>
    <td><?php echo htmlspecialchars($row['Name']); ?></td>
    <td><?php echo htmlspecialchars($row['DeptName']); ?></td>
    <td><?php echo htmlspecialchars($row['CourseName']); ?></td>
    <td><?php echo $row['Semester']; ?></td>
  </tr>
<?php endwhile; ?>
</table>
<br>
<a class="btn" href="../public/index.php">Back</a>
<p style="font-size:0.85em; color:gray;">
This simulates a distributed network: Academic_DB and Student_DB are logically separate nodes. In a real system, they could reside on different servers.
</p>
</div>
</body>
</html>
