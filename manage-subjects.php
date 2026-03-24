<?php
require_once 'includes/db.php';

// Delete BEFORE any output
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    mysqli_query($conn, "DELETE FROM tblsubjects WHERE id=$id");
    header("Location: manage-subjects.php?msg=deleted");
    exit();
}

$activePage = 'subjects';
$pageTitle  = 'Manage Subjects';
require_once 'includes/admin-header.php';

$msg      = isset($_GET['msg']) ? $_GET['msg'] : '';
$subjects = array();
$res      = mysqli_query($conn, "SELECT * FROM tblsubjects ORDER BY SubjectName");
while ($row = mysqli_fetch_assoc($res)) { $subjects[] = $row; }
?>

<div class="page-header-bar">
  <h4><i class="bi bi-list-check me-2"></i>Manage Subjects</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Manage Subjects</li>
  </ol></nav>
</div>

<?php if ($msg == 'deleted'): ?>
<div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i>Subject deleted successfully.</div>
<?php endif; ?>

<div class="ac">
  <div class="ac-head">
    <span><i class="bi bi-book me-2"></i>All Subjects</span>
    <a href="create-subject.php"><i class="bi bi-plus-circle me-1"></i>Add New</a>
  </div>
  <div class="table-responsive">
    <table class="at">
      <thead>
        <tr>
          <th>#</th>
          <th>Subject Name</th>
          <th>Code</th>
          <th>Created</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($subjects)): $i = 1; foreach ($subjects as $s): ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo htmlspecialchars($s['SubjectName']); ?></td>
          <td><span class="badge-on"><?php echo htmlspecialchars($s['SubjectCode']); ?></span></td>
          <td><?php echo $s['Creationdate']; ?></td>
          <td>
            <a href="edit-subject.php?id=<?php echo $s['id']; ?>" class="btn-edit me-1">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="manage-subjects.php?del=<?php echo $s['id']; ?>"
               onclick="return confirm('Are you sure you want to delete this subject?')"
               class="btn-del">
              <i class="bi bi-trash"></i> Delete
            </a>
          </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
          <td colspan="5" style="text-align:center;padding:30px;color:#888">No subjects found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
