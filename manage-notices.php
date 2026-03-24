<?php
require_once 'includes/db.php';

// Delete BEFORE any output
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    mysqli_query($conn, "DELETE FROM tblnotice WHERE id=$id");
    header("Location: manage-notices.php?msg=deleted");
    exit();
}

$activePage = 'notices';
$pageTitle  = 'Manage Notices';
require_once 'includes/admin-header.php';

$msg     = isset($_GET['msg']) ? $_GET['msg'] : '';
$notices = array();
$res     = mysqli_query($conn, "SELECT * FROM tblnotice ORDER BY postingDate DESC");
while ($row = mysqli_fetch_assoc($res)) { $notices[] = $row; }
?>

<div class="page-header-bar">
  <h4><i class="bi bi-bell me-2"></i>Manage Notices</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Manage Notices</li>
  </ol></nav>
</div>

<?php if ($msg == 'deleted'): ?>
<div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i>Notice deleted successfully.</div>
<?php endif; ?>

<div class="ac">
  <div class="ac-head">
    <span>
      <i class="bi bi-bell me-2"></i>All Notices
      <span style="background:rgba(255,255,255,.15);font-size:11px;padding:2px 8px;border-radius:10px;margin-left:8px">
        <?php echo count($notices); ?> Total
      </span>
    </span>
    <a href="add-notice.php"><i class="bi bi-plus-circle me-1"></i>Add New</a>
  </div>
  <div class="table-responsive">
    <table class="at">
      <thead>
        <tr>
          <th>#</th>
          <th>Notice Title</th>
          <th>Details</th>
          <th>Posted Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($notices)): $i = 1; foreach ($notices as $n): ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td>
            <strong style="color:#1a2340"><?php echo htmlspecialchars($n['noticeTitle']); ?></strong>
          </td>
          <td style="max-width:340px;color:#555;font-size:13px">
            <?php echo htmlspecialchars(substr($n['noticeDetails'], 0, 100)); ?>
            <?php echo strlen($n['noticeDetails']) > 100 ? '...' : ''; ?>
          </td>
          <td style="white-space:nowrap;font-size:12px;color:#888">
            <i class="bi bi-calendar3 me-1"></i>
            <?php echo date('d M Y, h:i A', strtotime($n['postingDate'])); ?>
          </td>
          <td style="white-space:nowrap">
            <a href="edit-notice.php?id=<?php echo $n['id']; ?>" class="btn-edit me-1">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="manage-notices.php?del=<?php echo $n['id']; ?>"
               onclick="return confirm('Are you sure you want to delete this notice?')"
               class="btn-del">
              <i class="bi bi-trash"></i> Delete
            </a>
          </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
          <td colspan="5" style="text-align:center;padding:50px;color:#888">
            <i class="bi bi-bell-slash" style="font-size:2.5rem;display:block;margin-bottom:10px;color:#ccc"></i>
            No notices posted yet.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
