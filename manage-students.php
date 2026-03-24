<?php
require_once 'includes/db.php';

// Handle delete/status BEFORE any output
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    mysqli_query($conn, "DELETE FROM tblstudents WHERE StudentId=$id");
    header("Location: manage-students.php?msg=deleted"); exit();
}
if (isset($_GET['status'])) {
    $id  = (int)$_GET['status'];
    $cur = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Status FROM tblstudents WHERE StudentId=$id"));
    $new = ($cur['Status'] == 1) ? 0 : 1;
    mysqli_query($conn, "UPDATE tblstudents SET Status=$new WHERE StudentId=$id");
    header("Location: manage-students.php?msg=updated"); exit();
}

$activePage = 'students';
$pageTitle  = 'Manage Students';
require_once 'includes/admin-header.php';

$msg      = isset($_GET['msg']) ? $_GET['msg'] : '';
$students = array();
$res      = mysqli_query($conn,
    "SELECT s.*, c.ClassName, c.Section
     FROM tblstudents s
     LEFT JOIN tblclasses c ON s.ClassId = c.id
     ORDER BY s.RegDate DESC"
);
while ($row = mysqli_fetch_assoc($res)) { $students[] = $row; }
?>

<div class="page-header-bar">
  <h4><i class="bi bi-people me-2"></i>Manage Students</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Manage Students</li>
  </ol></nav>
</div>

<?php if ($msg == 'deleted'): ?>
<div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i>Student deleted successfully.</div>
<?php elseif ($msg == 'updated'): ?>
<div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i>Student status updated.</div>
<?php endif; ?>

<div class="ac">
  <div class="ac-head">
    <span><i class="bi bi-people me-2"></i>All Students
      <span style="background:rgba(255,255,255,.15);font-size:11px;padding:2px 8px;border-radius:10px;margin-left:8px">
        <?php echo count($students); ?> Total
      </span>
    </span>
    <a href="add-students.php"><i class="bi bi-plus-circle me-1"></i>Add New</a>
  </div>

  <!-- Search bar -->
  <div style="padding:14px 16px;border-bottom:1px solid #f0f0f0">
    <input type="text" id="searchInput" onkeyup="searchTable()"
           placeholder="Search by name, roll, email..."
           style="width:100%;max-width:360px;border:1px solid #ddd;border-radius:5px;padding:8px 12px;font-size:13px;outline:none"/>
  </div>

  <div class="table-responsive">
    <table class="at" id="studentTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Roll ID</th>
          <th>Email</th>
          <th>Gender</th>
          <th>Class</th>
          <th>DOB</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($students)): $i = 1; foreach ($students as $s): ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td>
            <div style="font-weight:600;color:#1a2340"><?php echo htmlspecialchars($s['StudentName']); ?></div>
          </td>
          <td><?php echo htmlspecialchars($s['RollId']); ?></td>
          <td style="font-size:12px"><?php echo htmlspecialchars($s['StudentEmail']); ?></td>
          <td><?php echo htmlspecialchars($s['Gender']); ?></td>
          <td>
            <?php if ($s['ClassName']): ?>
            <span style="background:#e8f0fe;color:#1a56db;font-size:11px;font-weight:700;padding:3px 8px;border-radius:10px">
              <?php echo htmlspecialchars($s['ClassName'].'('.$s['Section'].')'); ?>
            </span>
            <?php else: ?><span style="color:#aaa">N/A</span><?php endif; ?>
          </td>
          <td style="font-size:12px"><?php echo $s['DOB']; ?></td>
          <td>
            <?php if ($s['Status'] == 1): ?>
              <span class="badge-on">Active</span>
            <?php else: ?>
              <span class="badge-off">Inactive</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="edit-student.php?id=<?php echo $s['StudentId']; ?>" class="btn-edit me-1">
              <i class="bi bi-pencil"></i>
            </a>
            <a href="manage-students.php?status=<?php echo $s['StudentId']; ?>"
               onclick="return confirm('Toggle status for this student?')"
               class="btn-edit me-1" style="background:#e65100">
              <i class="bi bi-toggle-on"></i>
            </a>
            <a href="manage-students.php?del=<?php echo $s['StudentId']; ?>"
               onclick="return confirm('Are you sure you want to delete this student?')"
               class="btn-del">
              <i class="bi bi-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
          <td colspan="9" style="text-align:center;padding:40px;color:#888">
            <i class="bi bi-people" style="font-size:2rem;display:block;margin-bottom:8px"></i>
            No students found.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function searchTable() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var rows  = document.querySelectorAll('#studentTable tbody tr');
    rows.forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(input) ? '' : 'none';
    });
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
