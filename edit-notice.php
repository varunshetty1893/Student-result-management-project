<?php
require_once 'includes/db.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$notice = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM tblnotice WHERE id=$id LIMIT 1"));
if (!$notice) { header("Location: manage-notices.php"); exit(); }

$activePage = 'notices';
$pageTitle  = 'Edit Notice';
require_once 'includes/admin-header.php';

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title   = mysqli_real_escape_string($conn, trim($_POST['noticeTitle']));
    $details = mysqli_real_escape_string($conn, trim($_POST['noticeDetails']));
    if ($title && $details) {
        mysqli_query($conn,
            "UPDATE tblnotice SET noticeTitle='$title', noticeDetails='$details' WHERE id=$id");
        $success = 'Notice updated successfully!';
        $notice  = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT * FROM tblnotice WHERE id=$id LIMIT 1"));
    } else { $error = 'Please fill all fields.'; }
}
?>

<div class="page-header-bar">
  <h4><i class="bi bi-pencil me-2"></i>Edit Notice</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="manage-notices.php">Manage Notices</a></li>
    <li class="breadcrumb-item active">Edit Notice</li>
  </ol></nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-pencil me-2"></i>Edit Notice</span>
        <a href="manage-notices.php">Back</a>
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
                   value="<?php echo htmlspecialchars($notice['noticeTitle']); ?>"
                   required/>
          </div>
          <div class="mb-4">
            <label class="f-label">Notice Details <span style="color:#c0392b">*</span></label>
            <textarea name="noticeDetails" class="f-textarea" rows="6"
                      required><?php echo htmlspecialchars($notice['noticeDetails']); ?></textarea>
          </div>
          <div style="font-size:12px;color:#888;margin-bottom:16px">
            <i class="bi bi-calendar3 me-1"></i>
            Originally posted: <?php echo date('d M Y, h:i A', strtotime($notice['postingDate'])); ?>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-save">
              <i class="bi bi-check-lg me-1"></i>Update Notice
            </button>
            <a href="manage-notices.php"
               style="background:#6c757d;color:#fff;padding:10px 20px;font-size:14px;font-weight:700;border-radius:5px;text-decoration:none">
              Cancel
            </a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
