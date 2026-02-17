<?php
require_once 'db_config_student.php';
require_once 'db_config_academic.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Normalization Demo</title>
<link rel="stylesheet" href="../public/styles.css">
</head>
<body>
<div class="container">
<h1>Normalization Demo (1NF → 5NF)</h1>

<?php
// 1️⃣ Unnormalized Table (simulated by grouping courses per student)
$unnormalized = $stu_conn->query("
SELECT s.StudentID, s.Name, GROUP_CONCAT(c.CourseName SEPARATOR ', ') AS Courses, d.DeptName
FROM Student_DB.Students s
LEFT JOIN Student_DB.Enrollment e ON s.StudentID = e.StudentID
LEFT JOIN Academic_DB.Courses c ON e.CourseID = c.CourseID
LEFT JOIN Academic_DB.Department d ON s.DeptID = d.DeptID
GROUP BY s.StudentID
");

echo "<h2>Unnormalized Table (Students + Courses)</h2>";
echo "<table><tr><th>StudentID</th><th>Name</th><th>Courses</th><th>Dept</th></tr>";
while($row = $unnormalized->fetch_assoc()){
    echo "<tr>
            <td>{$row['StudentID']}</td>
            <td>".htmlspecialchars($row['Name'])."</td>
            <td>".htmlspecialchars($row['Courses'])."</td>
            <td>".htmlspecialchars($row['DeptName'])."</td>
          </tr>";
}
echo "</table>";

// 2️⃣ 1NF: Remove repeating groups
echo "<h2>1NF (Remove Repeating Groups)</h2>";
$oneNF = $stu_conn->query("
SELECT s.StudentID, s.Name, c.CourseName, d.DeptName
FROM Student_DB.Students s
LEFT JOIN Student_DB.Enrollment e ON s.StudentID = e.StudentID
LEFT JOIN Academic_DB.Courses c ON e.CourseID = c.CourseID
LEFT JOIN Academic_DB.Department d ON s.DeptID = d.DeptID
ORDER BY s.StudentID
");
echo "<table><tr><th>StudentID</th><th>Name</th><th>Course</th><th>Dept</th></tr>";
while($row = $oneNF->fetch_assoc()){
    echo "<tr>
            <td>{$row['StudentID']}</td>
            <td>".htmlspecialchars($row['Name'])."</td>
            <td>".htmlspecialchars($row['CourseName'])."</td>
            <td>".htmlspecialchars($row['DeptName'])."</td>
          </tr>";
}
echo "</table>";

// 3️⃣ 2NF: Separate Students and Courses tables (partial dependencies removed)
echo "<h2>2NF (Remove Partial Dependencies)</h2>";
echo "<h3>Students Table</h3>";
$students = $stu_conn->query("SELECT StudentID, Name, DeptID FROM Student_DB.Students");
echo "<table><tr><th>StudentID</th><th>Name</th><th>DeptID</th></tr>";
while($row = $students->fetch_assoc()){
    echo "<tr><td>{$row['StudentID']}</td><td>".htmlspecialchars($row['Name'])."</td><td>{$row['DeptID']}</td></tr>";
}
echo "</table>";

echo "<h3>Courses Table</h3>";
$courses = $acad_conn->query("SELECT CourseID, CourseName, DeptID FROM Academic_DB.Courses");
echo "<table><tr><th>CourseID</th><th>CourseName</th><th>DeptID</th></tr>";
while($row = $courses->fetch_assoc()){
    echo "<tr><td>{$row['CourseID']}</td><td>".htmlspecialchars($row['CourseName'])."</td><td>{$row['DeptID']}</td></tr>";
}
echo "</table>";

// 4️⃣ 3NF: Remove transitive dependency (Departments separated)
echo "<h2>3NF (Remove Transitive Dependencies)</h2>";
$departments = $acad_conn->query("SELECT DeptID, DeptName FROM Academic_DB.Department");
echo "<table><tr><th>DeptID</th><th>DeptName</th></tr>";
while($row = $departments->fetch_assoc()){
    echo "<tr><td>{$row['DeptID']}</td><td>".htmlspecialchars($row['DeptName'])."</td></tr>";
}
echo "</table>";

// 5️⃣ 4NF: Multi-valued dependencies separated into Enrollment table
echo "<h2>4NF (Multi-valued Dependencies in Enrollment)</h2>";
$enroll = $stu_conn->query("
SELECT e.EnrollID, e.StudentID, e.CourseID, e.Semester, c.CourseName
FROM Student_DB.Enrollment e
LEFT JOIN Academic_DB.Courses c ON e.CourseID = c.CourseID
ORDER BY e.EnrollID
");
echo "<table><tr><th>EnrollID</th><th>StudentID</th><th>CourseID</th><th>CourseName</th><th>Semester</th></tr>";
while($row = $enroll->fetch_assoc()){
    echo "<tr>
            <td>{$row['EnrollID']}</td>
            <td>{$row['StudentID']}</td>
            <td>{$row['CourseID']}</td>
            <td>".htmlspecialchars($row['CourseName'])."</td>
            <td>{$row['Semester']}</td>
          </tr>";
}
echo "</table>";

// 6️⃣ 5NF: Explanation
echo "<h2>5NF (Join Dependencies Reconstructable)</h2>";
echo "<p>All original data can be reconstructed by joining <b>Students ⋈ Enrollment ⋈ Courses</b>. No further decomposition required.</p>";
?>

</div>
</body>
</html>
