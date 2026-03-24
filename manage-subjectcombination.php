<?php
require_once 'includes/db.php';

// Delete BEFORE any output
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    mysqli_query($conn, "DELETE FROM tblsubjectcombination WHERE id=$id");
    header("Location: manage-subjectcombination.php?msg=deleted");
    exit();
}

$activePage = 'subjects';
$pageTitle  = 'Manage Subject Combination';
require_once 'includes/admin-header.php';

$msg  = isset($_GET['msg']) ? $_GET['msg'] : '';
$list = array();
$res  = mysqli_query($conn,
    "SELECT sc.*, c.ClassName, c.Section, s.SubjectName, s.SubjectCode
     FROM tblsubjectcombination sc
     JOIN tblclasses c  ON sc.ClassId   = c.id
     JOIN tblsubjects s ON sc.SubjectId = s.id
     ORDER BY c.ClassNameNumeric, s.SubjectName"
);
while ($row = mysqli_fetch_assoc($res)) { $list[] = $row; }
?>

<div class="page-header-bar">
  <h4><i class="bi bi-list-ul me-2"></i>Manage Subject Combination</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Manage Subject Combination</li>
  </ol></nav>
</div>

<?php if ($msg == 'deleted'): ?>
<div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i>Combination deleted successfully.</div>
<?php endif; ?>

<div class="ac">
  <div class="ac-head">
    <span><i class="bi bi-diagram-3 me-2"></i>All Subject Combinations</span>
    <a href="add-subjectcombination.php"><i class="bi bi-plus-circle me-1"></i>Add New</a>
  </div>
  <div class="table-responsive">
    <table class="at">
      <thead>
        <tr>
          <th>#</th>
          <th>Class</th>
          <th>Section</th>
          <th>Subject</th>
          <th>Code</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($list)): $i = 1; foreach ($list as $l): ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo htmlspecialchars($l['ClassName']); ?></td>
          <td><span class="badge-on"><?php echo htmlspecialchars($l['Section']); ?></span></td>
          <td><?php echo htmlspecialchars($l['SubjectName']); ?></td>
          <td><?php echo htmlspecialchars($l['SubjectCode']); ?></td>
          <td>
            <?php echo $l['status'] == 1
              ? '<span class="badge-on">Active</span>'
              : '<span class="badge-off">Inactive</span>'; ?>
          </td>
          <td>
            <a href="manage-subjectcombination.php?del=<?php echo $l['id']; ?>"
               onclick="return confirm('Are you sure you want to delete this combination?')"
               class="btn-del">
              <i class="bi bi-trash"></i> Delete
            </a>
          </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
          <td colspan="7" style="text-align:center;padding:30px;color:#888">No combinations found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
