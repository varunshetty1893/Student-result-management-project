<?php
require_once 'includes/db.php';

$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM tblstudents WHERE StudentId=$id LIMIT 1"));
if (!$student) { header("Location: manage-students.php"); exit(); }

$activePage = 'students';
$pageTitle  = 'Edit Student';
require_once 'includes/admin-header.php';

$classes = array();
$res = mysqli_query($conn, "SELECT * FROM tblclasses ORDER BY ClassNameNumeric");
while ($r = mysqli_fetch_assoc($res)) { $classes[] = $r; }

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = mysqli_real_escape_string($conn, trim($_POST['studentName']));
    $roll    = mysqli_real_escape_string($conn, trim($_POST['rollId']));
    $email   = mysqli_real_escape_string($conn, trim($_POST['email']));
    $gender  = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob     = mysqli_real_escape_string($conn, $_POST['dob']);
    $classId = (int)$_POST['classId'];
    $status  = (int)$_POST['status'];

    if ($name && $roll && $email && $gender && $dob && $classId) {
        mysqli_query($conn,
            "UPDATE tblstudents
             SET StudentName='$name', RollId='$roll', StudentEmail='$email',
                 Gender='$gender', DOB='$dob', ClassId=$classId,
                 Status=$status, UpdationDate=NOW()
             WHERE StudentId=$id");
        $success = 'Student updated successfully!';
        $student = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT * FROM tblstudents WHERE StudentId=$id"));
    } else { $error = 'Please fill all required fields.'; }
}
?>

<div class="page-header-bar">
  <h4><i class="bi bi-pencil me-2"></i>Edit Student</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="manage-students.php">Manage Students</a></li>
    <li class="breadcrumb-item active">Edit Student</li>
  </ol></nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-pencil me-2"></i>Edit Student Details</span>
        <a href="manage-students.php">Back</a>
      </div>
      <div class="ac-body">

        <?php if ($success): ?>
        <div class="alert-ok"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="f-label">Full Name <span style="color:#c0392b">*</span></label>
              <input type="text" name="studentName" class="f-input"
                     value="<?php echo htmlspecialchars($student['StudentName']); ?>" required/>
            </div>

            <div class="col-md-6">
              <label class="f-label">Roll ID <span style="color:#c0392b">*</span></label>
              <input type="text" name="rollId" class="f-input"
                     value="<?php echo htmlspecialchars($student['RollId']); ?>" required/>
            </div>

            <div class="col-md-6">
              <label class="f-label">Email Address <span style="color:#c0392b">*</span></label>
              <input type="email" name="email" class="f-input"
                     value="<?php echo htmlspecialchars($student['StudentEmail']); ?>" required/>
            </div>

            <div class="col-md-6">
              <label class="f-label">Gender <span style="color:#c0392b">*</span></label>
              <select name="gender" class="f-select" required>
                <option value="">-- Select Gender --</option>
                <option value="Male"   <?php echo $student['Gender']=='Male'   ? 'selected':''; ?>>Male</option>
                <option value="Female" <?php echo $student['Gender']=='Female' ? 'selected':''; ?>>Female</option>
                <option value="Other"  <?php echo $student['Gender']=='Other'  ? 'selected':''; ?>>Other</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="f-label">Date of Birth <span style="color:#c0392b">*</span></label>
              <input type="date" name="dob" class="f-input"
                     value="<?php echo htmlspecialchars($student['DOB']); ?>" required/>
            </div>

            <div class="col-md-6">
              <label class="f-label">Class <span style="color:#c0392b">*</span></label>
              <select name="classId" class="f-select" required>
                <option value="">-- Select Class --</option>
                <?php foreach ($classes as $c): ?>
                <option value="<?php echo $c['id']; ?>"
                  <?php echo $student['ClassId']==$c['id'] ? 'selected':''; ?>>
                  <?php echo htmlspecialchars($c['ClassName']); ?> (Section <?php echo htmlspecialchars($c['Section']); ?>)
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="f-label">Status</label>
              <select name="status" class="f-select">
                <option value="1" <?php echo $student['Status']==1 ? 'selected':''; ?>>Active</option>
                <option value="0" <?php echo $student['Status']==0 ? 'selected':''; ?>>Inactive</option>
              </select>
            </div>

            <div class="col-12">
              <hr style="border-color:#f0f0f0;margin:6px 0 14px"/>
              <div class="d-flex gap-2">
                <button type="submit" class="btn-save">
                  <i class="bi bi-check-lg me-1"></i>Update Student
                </button>
                <a href="manage-students.php"
                   style="background:#6c757d;color:#fff;padding:10px 20px;font-size:14px;font-weight:700;border-radius:5px;text-decoration:none">
                  Cancel
                </a>
              </div>
            </div>

          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
