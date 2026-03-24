<?php
require_once 'includes/db.php';
$activePage = 'classes';
$pageTitle  = 'Create Class';
require_once 'includes/admin-header.php';

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cn  = trim($_POST['className']);
    $num = (int)$_POST['classNameNumeric'];
    $sec = trim($_POST['section']);
    if ($cn && $num && $sec) {
        $cn  = mysqli_real_escape_string($conn, $cn);
        $sec = mysqli_real_escape_string($conn, $sec);
        $chk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM tblclasses WHERE ClassName='$cn' AND Section='$sec' LIMIT 1"));
        if ($chk) { $error = 'This class already exists.'; }
        else { mysqli_query($conn, "INSERT INTO tblclasses (ClassName,ClassNameNumeric,Section) VALUES ('$cn',$num,'$sec')"); $success = 'Class created successfully!'; }
    } else { $error = 'Please fill all fields.'; }
}
?>
<div class="page-header-bar">
  <h4><i class="bi bi-plus-circle me-2"></i>Create Class</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Create Class</li>
  </ol></nav>
</div>
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="ac">
      <div class="ac-head"><span><i class="bi bi-journal-text me-2"></i>Create New Class</span>
        <a href="manage-classes.php">Manage Classes</a></div>
      <div class="ac-body">
        <?php if ($success): ?><div class="alert-ok"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
          <div class="mb-3"><label class="f-label">Class Name</label>
            <input type="text" name="className" class="f-input" placeholder="e.g. First Year" required/></div>
          <div class="mb-3"><label class="f-label">Class Numeric Value</label>
            <input type="number" name="classNameNumeric" class="f-input" placeholder="e.g. 1" min="1" required/></div>
          <div class="mb-4"><label class="f-label">Section</label>
            <input type="text" name="section" class="f-input" placeholder="e.g. A" maxlength="5" required/></div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i>Save Class</button>
            <a href="manage-classes.php" style="background:#6c757d;color:#fff;border:none;padding:10px 20px;font-size:14px;font-weight:700;border-radius:5px;text-decoration:none">Manage Classes</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once 'includes/admin-footer.php'; ?>
