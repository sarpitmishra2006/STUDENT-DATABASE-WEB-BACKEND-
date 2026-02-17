<?php
// index.php (public)
require_once __DIR__ . '/../php/db_config_academic.php';
require_once __DIR__ . '/../php/db_config_student.php';

// Fetch departments for Add Student form
$deptRes = $acad_conn->query("SELECT DeptID, DeptName FROM Department ORDER BY DeptName");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Management System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Student Management System</h1>

    <!-- Add Student Form -->
    <section class="card">
      <h2>Add Student</h2>
      <form id="studentForm" action="../php/insert_student.php" method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <select name="dept_id" required>
          <option value="">Select Department</option>
          <?php while($d = $deptRes->fetch_assoc()): ?>
            <option value="<?php echo $d['DeptID']; ?>"><?php echo htmlspecialchars($d['DeptName']); ?></option>
          <?php endwhile; ?>
        </select>
        <input type="date" name="dob" placeholder="Date of Birth">
        <select name="gender">
          <option value="M">M</option>
          <option value="F">F</option>
        </select>
        <input type="email" name="email" placeholder="Email">
        <input type="text" name="phone" placeholder="Phone">
        <input type="number" name="semester" placeholder="Semester" min="1">
        <input type="text" name="course" placeholder="Course (optional)">
        <button type="submit">Add Student</button>
      </form>
    </section>

    <!-- Actions Section -->
    <section class="card">
      <h2>Actions</h2>
      
      <!-- Open Student List -->
      <a class="btn" href="../php/view_students.php" target="_blank">Open Student List</a>

      <!-- Normalization Demo -->
      <a class="btn" href="../php/normalization_demo.php" target="_blank">Normalization Demo (1NF → 5NF)</a>

      <!-- Concurrency Demo Dropdown -->
      <form action="../php/concurrency_demo.php" method="get" target="_blank" style="margin-top:10px;">
        <label>Concurrency Demo for Student:</label>
        <select name="sid" required>
          <option value="">Select Student</option>
          <?php
          $students = $stu_conn->query("SELECT StudentID, Name FROM Students ORDER BY StudentID");
          while($s = $students->fetch_assoc()){
              echo "<option value='{$s['StudentID']}'>".htmlspecialchars($s['Name'])."</option>";
          }
          ?>
        </select>
        <button type="submit" class="btn">Start Concurrency Demo</button>
      </form>

      <!-- Distributed DB Demo -->
      <form action="../php/distributed_demo.php" method="get" target="_blank" style="margin-top:10px;">
        <button type="submit" class="btn">Distributed DB Demo</button>
        <p style="font-size:0.85em; color:gray;">
          View cross-DB data from Academic_DB & Student_DB (simulated distributed network)
        </p>
      </form>
    </section>

  </div>
  <script src="scripts.js"></script>
</body>
</html>
