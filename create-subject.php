<?php
require_once 'includes/db.php';
$activePage = 'subjects';
$pageTitle  = 'Create Subject';
require_once 'includes/admin-header.php';

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sn   = mysqli_real_escape_string($conn, trim($_POST['subjectName']));
    $code = mysqli_real_escape_string($conn, trim($_POST['subjectCode']));
    if ($sn && $code) {
        $chk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM tblsubjects WHERE SubjectName='$sn' LIMIT 1"));
        if ($chk) { $error = 'Subject already exists.'; }
        else {
            mysqli_query($conn, "INSERT INTO tblsubjects (SubjectName,SubjectCode) VALUES ('$sn','$code')");
            $success = 'Subject created successfully!';
        }
    } else { $error = 'Please fill all fields.'; }
}
?>
<div class="page-header-bar">
  <h4><i class="bi bi-plus-circle me-2"></i>Create Subject</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Create Subject</li>
  </ol></nav>
</div>
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-book me-2"></i>Create New Subject</span>
        <a href="manage-subjects.php">Manage Subjects</a>
      </div>
      <div class="ac-body">
        <?php if ($success): ?><div class="alert-ok"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
          <div class="mb-3">
            <label class="f-label">Subject Name</label>
            <input type="text" name="subjectName" class="f-input" placeholder="e.g. Software Engineering" required/>
          </div>
          <div class="mb-4">
            <label class="f-label">Subject Code</label>
            <input type="text" name="subjectCode" class="f-input" placeholder="e.g. SE" required/>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i>Save Subject</button>
            
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once 'includes/admin-footer.php'; ?>
