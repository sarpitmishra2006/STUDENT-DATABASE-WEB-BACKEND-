<?php
require_once 'db_config_student.php';
require_once 'db_config_academic.php';

$id = intval($_GET['id'] ?? 0);
if($id <= 0){ header('Location: ../php/view_students.php'); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // process update
    $name = $stu_conn->real_escape_string($_POST['name'] ?? '');
    $dept_id = intval($_POST['dept_id'] ?? 0);
    $dob = $_POST['dob'] ?? NULL;
    $gender = $stu_conn->real_escape_string($_POST['gender'] ?? '');
    $email = $stu_conn->real_escape_string($_POST['email'] ?? '');
    $phone = $stu_conn->real_escape_string($_POST['phone'] ?? '');

    $stu_conn->begin_transaction();
    try{
        $stmt = $stu_conn->prepare("UPDATE Students SET Name=?, Gender=?, DeptID=?, Email=?, Phone=?, DOB=? WHERE StudentID=?");
        $stmt->bind_param('ssisssi', $name, $gender, $dept_id, $email, $phone, $dob, $id);
        $stmt->execute();
        $stmt->close();

        $stu_conn->commit();
        header('Location: ../php/view_students.php');
        exit();
    } catch(Exception $e){
        $stu_conn->rollback();
        die("Update failed: ".$e->getMessage());
    }
} else {
    // show edit form
    $res = $stu_conn->query("SELECT * FROM Students WHERE StudentID = $id");
    if(!$res || $res->num_rows == 0){ die("Student not found"); }
    $student = $res->fetch_assoc();
    $depts = $acad_conn->query("SELECT DeptID, DeptName FROM Department ORDER BY DeptName");
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Edit Student</title>
<link rel="stylesheet" href="../public/styles.css"></head><body>
<div class="container">
  <h1>Edit Student</h1>
  <form method="POST">
    <input type="text" name="name" value="<?php echo htmlspecialchars($student['Name']); ?>" required>
    <select name="dept_id" required>
      <?php while($d = $depts->fetch_assoc()): ?>
        <option value="<?php echo $d['DeptID']; ?>" <?php if($d['DeptID']==$student['DeptID']) echo 'selected'; ?>>
          <?php echo htmlspecialchars($d['DeptName']); ?>
        </option>
      <?php endwhile; ?>
    </select>
    <input type="date" name="dob" value="<?php echo htmlspecialchars($student['DOB']); ?>">
    <select name="gender">
      <option value="M" <?php if($student['Gender']=='M') echo 'selected'; ?>>M</option>
      <option value="F" <?php if($student['Gender']=='F') echo 'selected'; ?>>F</option>
    </select>
    <input type="email" name="email" value="<?php echo htmlspecialchars($student['Email']); ?>">
    <input type="text" name="phone" value="<?php echo htmlspecialchars($student['Phone']); ?>">
    <button type="submit">Save</button>
  </form>
  <br><a class="btn" href="../php/view_students.php">Back</a>
</div>
</body></html>
