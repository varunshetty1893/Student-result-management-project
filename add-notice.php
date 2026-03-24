<?php
require_once 'includes/db.php';
$activePage = 'notices';
$pageTitle  = 'Add Notice';
require_once 'includes/admin-header.php';

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title   = mysqli_real_escape_string($conn, trim($_POST['noticeTitle']));
    $details = mysqli_real_escape_string($conn, trim($_POST['noticeDetails']));
    if ($title && $details) {
        mysqli_query($conn,
            "INSERT INTO tblnotice (noticeTitle, noticeDetails) VALUES ('$title','$details')");
        $success = 'Notice posted successfully!';
    } else { $error = 'Please fill all fields.'; }
}
?>

<div class="page-header-bar">
  <h4><i class="bi bi-megaphone me-2"></i>Add Notice</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Add Notice</li>
  </ol></nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-megaphone me-2"></i>Post New Notice</span>
        <a href="manage-notices.php">Manage Notices</a>
      </div>
      <div class="ac-body">
        <?php if ($success): ?>
        <div class="alert-ok"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label class="f-label">Notice Title <span style="color:#c0392b">*</span></label>
            <input type="text" name="noticeTitle" class="f-input"
                   placeholder="e.g. Classes Resume"
                   value="<?php echo isset($_POST['noticeTitle']) ? htmlspecialchars($_POST['noticeTitle']) : ''; ?>"
                   required/>
          </div>
          <div class="mb-4">
            <label class="f-label">Notice Details <span style="color:#c0392b">*</span></label>
            <textarea name="noticeDetails" class="f-textarea" rows="6"
                      placeholder="Write notice details here..."
                      required><?php echo isset($_POST['noticeDetails']) ? htmlspecialchars($_POST['noticeDetails']) : ''; ?></textarea>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-save">
              <i class="bi bi-send me-1"></i>Post Notice
            </button>
            
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
