<?php
require_once 'includes/db.php';

$id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sub = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblsubjects WHERE id=$id LIMIT 1"));
if (!$sub) { header("Location: manage-subjects.php"); exit(); }

$activePage = 'subjects';
$pageTitle  = 'Edit Subject';
require_once 'includes/admin-header.php';

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sn   = mysqli_real_escape_string($conn, trim($_POST['subjectName']));
    $code = mysqli_real_escape_string($conn, trim($_POST['subjectCode']));
    if ($sn && $code) {
        mysqli_query($conn, "UPDATE tblsubjects SET SubjectName='$sn',SubjectCode='$code',UpdationDate=NOW() WHERE id=$id");
        $success = 'Subject updated successfully!';
        $sub     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblsubjects WHERE id=$id"));
    } else { $error = 'Please fill all fields.'; }
}
?>

<div class="page-header-bar">
  <h4><i class="bi bi-pencil me-2"></i>Edit Subject</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="manage-subjects.php">Manage Subjects</a></li>
    <li class="breadcrumb-item active">Edit Subject</li>
  </ol></nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-pencil me-2"></i>Edit Subject</span>
        <a href="manage-subjects.php">Back</a>
      </div>
      <div class="ac-body">
        <?php if ($success): ?><div class="alert-ok"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
          <div class="mb-3">
            <label class="f-label">Subject Name</label>
            <input type="text" name="subjectName" class="f-input"
                   value="<?php echo htmlspecialchars($sub['SubjectName']); ?>" required/>
          </div>
          <div class="mb-4">
            <label class="f-label">Subject Code</label>
            <input type="text" name="subjectCode" class="f-input"
                   value="<?php echo htmlspecialchars($sub['SubjectCode']); ?>" required/>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i>Update Subject</button>
            <a href="manage-subjects.php" style="background:#6c757d;color:#fff;padding:10px 20px;font-size:14px;font-weight:700;border-radius:5px;text-decoration:none">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
