<?php
require_once 'includes/db.php';
$activePage = 'students';
$pageTitle  = 'Add Student';
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

    if ($name && $roll && $email && $gender && $dob && $classId) {
        $chk = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT StudentId FROM tblstudents WHERE RollId='$roll' AND ClassId=$classId LIMIT 1"));
        if ($chk) {
            $error = 'A student with this Roll ID already exists in this class.';
        } else {
            mysqli_query($conn,
                "INSERT INTO tblstudents (StudentName,RollId,StudentEmail,Gender,DOB,ClassId,Status)
                 VALUES ('$name','$roll','$email','$gender','$dob',$classId,1)");
            $success = 'Student added successfully!';
        }
    } else { $error = 'Please fill all required fields.'; }
}
?>

<div class="page-header-bar">
  <h4><i class="bi bi-person-plus me-2"></i>Add Student</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Add Student</li>
  </ol></nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-person-plus me-2"></i>Add New Student</span>
        <a href="manage-students.php">Manage Students</a>
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
                     placeholder="e.g. Abantika Suresh Ahire"
                     value="<?php echo isset($_POST['studentName']) ? htmlspecialchars($_POST['studentName']) : ''; ?>"
                     required/>
            </div>

            <div class="col-md-6">
              <label class="f-label">Roll ID <span style="color:#c0392b">*</span></label>
              <input type="text" name="rollId" class="f-input"
                     placeholder="e.g. 1"
                     value="<?php echo isset($_POST['rollId']) ? htmlspecialchars($_POST['rollId']) : ''; ?>"
                     required/>
            </div>

            <div class="col-md-6">
              <label class="f-label">Email Address <span style="color:#c0392b">*</span></label>
              <input type="email" name="email" class="f-input"
                     placeholder="student@email.com"
                     value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                     required/>
            </div>

            <div class="col-md-6">
              <label class="f-label">Gender <span style="color:#c0392b">*</span></label>
              <select name="gender" class="f-select" required>
                <option value="">-- Select Gender --</option>
                <option value="Male"   <?php echo (isset($_POST['gender']) && $_POST['gender']=='Male')   ? 'selected':''; ?>>Male</option>
                <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender']=='Female') ? 'selected':''; ?>>Female</option>
                <option value="Other"  <?php echo (isset($_POST['gender']) && $_POST['gender']=='Other')  ? 'selected':''; ?>>Other</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="f-label">Date of Birth <span style="color:#c0392b">*</span></label>
              <input type="date" name="dob" class="f-input"
                     value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>"
                     required/>
            </div>

            <div class="col-md-6">
              <label class="f-label">Class <span style="color:#c0392b">*</span></label>
              <select name="classId" class="f-select" required>
                <option value="">-- Select Class --</option>
                <?php foreach ($classes as $c): ?>
                <option value="<?php echo $c['id']; ?>"
                  <?php echo (isset($_POST['classId']) && $_POST['classId']==$c['id']) ? 'selected':''; ?>>
                  <?php echo htmlspecialchars($c['ClassName']); ?> (Section <?php echo htmlspecialchars($c['Section']); ?>)
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12">
              <hr style="border-color:#f0f0f0;margin:6px 0 14px"/>
              <div class="d-flex gap-2">
                <button type="submit" class="btn-save">
                  <i class="bi bi-check-lg me-1"></i>Add Student
                </button>
                
              </div>
            </div>

          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
