<?php
require_once 'includes/db.php';
$activePage = 'classes';
$pageTitle  = 'Edit Class';
require_once 'includes/admin-header.php';

$id    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$class = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblclasses WHERE id=$id LIMIT 1"));
if (!$class) { header("Location: manage-classes.php"); exit(); }

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cn  = mysqli_real_escape_string($conn, trim($_POST['className']));
    $num = (int)$_POST['classNameNumeric'];
    $sec = mysqli_real_escape_string($conn, trim($_POST['section']));
    if ($cn && $num && $sec) {
        mysqli_query($conn, "UPDATE tblclasses SET ClassName='$cn',ClassNameNumeric=$num,Section='$sec',UpdationDate=NOW() WHERE id=$id");
        $success = 'Class updated successfully!';
        $class = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tblclasses WHERE id=$id LIMIT 1"));
    } else { $error = 'Please fill all fields.'; }
}
?>
<div class="page-header-bar">
  <h4><i class="bi bi-pencil me-2"></i>Edit Class</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="manage-classes.php">Manage Classes</a></li>
    <li class="breadcrumb-item active">Edit Class</li>
  </ol></nav>
</div>
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="ac">
      <div class="ac-head"><span><i class="bi bi-pencil me-2"></i>Edit Class</span>
        <a href="manage-classes.php">Back</a></div>
      <div class="ac-body">
        <?php if ($success): ?><div class="alert-ok"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
          <div class="mb-3"><label class="f-label">Class Name</label>
            <input type="text" name="className" class="f-input" value="<?php echo htmlspecialchars($class['ClassName']); ?>" required/></div>
          <div class="mb-3"><label class="f-label">Class Numeric Value</label>
            <input type="number" name="classNameNumeric" class="f-input" value="<?php echo $class['ClassNameNumeric']; ?>" required/></div>
          <div class="mb-4"><label class="f-label">Section</label>
            <input type="text" name="section" class="f-input" value="<?php echo htmlspecialchars($class['Section']); ?>" maxlength="5" required/></div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i>Update Class</button>
            <a href="manage-classes.php" style="background:#6c757d;color:#fff;padding:10px 20px;font-size:14px;font-weight:700;border-radius:5px;text-decoration:none">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once 'includes/admin-footer.php'; ?>
