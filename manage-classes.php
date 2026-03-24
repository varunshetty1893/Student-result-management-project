<?php
require_once 'includes/db.php';

// ── Handle delete BEFORE any output ──
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    mysqli_query($conn, "DELETE FROM tblclasses WHERE id=$id");
    header("Location: manage-classes.php?msg=deleted");
    exit();
}

$activePage = 'classes';
$pageTitle  = 'Manage Classes';
require_once 'includes/admin-header.php';

$msg     = isset($_GET['msg']) ? $_GET['msg'] : '';
$classes = array();
$res     = mysqli_query($conn, "SELECT * FROM tblclasses ORDER BY ClassNameNumeric");
while ($row = mysqli_fetch_assoc($res)) { $classes[] = $row; }
?>

<div class="page-header-bar">
  <h4><i class="bi bi-list-check me-2"></i>Manage Classes</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item active">Manage Classes</li>
    </ol>
  </nav>
</div>

<?php if ($msg == 'deleted'): ?>
<div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i>Class deleted successfully.</div>
<?php endif; ?>

<div class="ac">
  <div class="ac-head">
    <span><i class="bi bi-journal-text me-2"></i>All Classes</span>
    <a href="create-class.php"><i class="bi bi-plus-circle me-1"></i>Add New</a>
  </div>
  <div class="table-responsive">
    <table class="at">
      <thead>
        <tr>
          <th>#</th>
          <th>Class Name</th>
          <th>Numeric</th>
          <th>Section</th>
          <th>Created</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($classes)): $i = 1; foreach ($classes as $c): ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo htmlspecialchars($c['ClassName']); ?></td>
          <td><?php echo $c['ClassNameNumeric']; ?></td>
          <td><span class="badge-on"><?php echo htmlspecialchars($c['Section']); ?></span></td>
          <td><?php echo $c['CreationDate']; ?></td>
          <td>
            <a href="edit-class.php?id=<?php echo $c['id']; ?>" class="btn-edit me-1">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="manage-classes.php?del=<?php echo $c['id']; ?>"
               onclick="return confirm('Are you sure you want to delete this class?')"
               class="btn-del">
              <i class="bi bi-trash"></i> Delete
            </a>
          </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
          <td colspan="6" style="text-align:center;padding:30px;color:#888">
            No classes found.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
