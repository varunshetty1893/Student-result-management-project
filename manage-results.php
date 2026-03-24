<?php
require_once 'includes/db.php';

// Delete BEFORE any output
if (isset($_GET['del'])) {
    $id         = (int)$_GET['del'];
    $classIdDel = isset($_GET['classId']) ? (int)$_GET['classId'] : 0;
    mysqli_query($conn, "DELETE FROM tblresult WHERE StudentId=$id");
    $redirect = $classIdDel ? "manage-results.php?msg=deleted&classId=$classIdDel" : "manage-results.php?msg=deleted";
    header("Location: $redirect");
    exit();
}

$activePage = 'results';
$pageTitle  = 'Manage Results';
require_once 'includes/admin-header.php';

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Filter by class — stays in GET param
$filterClass = isset($_GET['classId']) ? (int)$_GET['classId'] : 0;

// All classes for dropdown
$classes = array();
$rc = mysqli_query($conn, "SELECT * FROM tblclasses ORDER BY ClassNameNumeric");
while ($r = mysqli_fetch_assoc($rc)) { $classes[] = $r; }

// Build query
if ($filterClass) {
    $res = mysqli_query($conn,
        "SELECT s.StudentId, s.StudentName, s.RollId, s.Status,
                c.ClassName, c.Section,
                COUNT(r.id) as subCount,
                SUM(r.marks) as totalMarks
         FROM tblstudents s
         LEFT JOIN tblclasses c ON s.ClassId = c.id
         LEFT JOIN tblresult r  ON s.StudentId = r.StudentId
         WHERE s.ClassId = $filterClass
         GROUP BY s.StudentId
         HAVING subCount > 0
         ORDER BY s.StudentName"
    );
} else {
    $res = mysqli_query($conn,
        "SELECT s.StudentId, s.StudentName, s.RollId, s.Status,
                c.ClassName, c.Section,
                COUNT(r.id) as subCount,
                SUM(r.marks) as totalMarks
         FROM tblstudents s
         LEFT JOIN tblclasses c ON s.ClassId = c.id
         LEFT JOIN tblresult r  ON s.StudentId = r.StudentId
         GROUP BY s.StudentId
         HAVING subCount > 0
         ORDER BY c.ClassNameNumeric, s.StudentName"
    );
}

$results = array();
while ($row = mysqli_fetch_assoc($res)) { $results[] = $row; }
?>

<div class="page-header-bar">
  <h4><i class="bi bi-table me-2"></i>Manage Results</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Manage Results</li>
  </ol></nav>
</div>

<?php if ($msg == 'deleted'): ?>
<div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i>Result deleted successfully.</div>
<?php endif; ?>

<!-- Filter -->
<div class="ac mb-3">
  <div class="ac-body" style="padding:14px 16px">
    <form method="GET" class="d-flex align-items-center gap-3 flex-wrap">
      <label class="f-label mb-0" style="white-space:nowrap">Filter by Class:</label>
      <select name="classId" class="f-select" style="width:auto;min-width:220px"
              onchange="this.form.submit()">
        <option value="">-- All Classes --</option>
        <?php foreach ($classes as $c): ?>
        <option value="<?php echo $c['id']; ?>"
          <?php echo ($filterClass == $c['id']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars($c['ClassName']); ?> (<?php echo htmlspecialchars($c['Section']); ?>)
        </option>
        <?php endforeach; ?>
      </select>
      <?php if ($filterClass): ?>
      <a href="manage-results.php" style="font-size:13px;color:#c0392b;font-weight:600">
        <i class="bi bi-x-circle me-1"></i>Clear filter
      </a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Results Table -->
<div class="ac">
  <div class="ac-head">
    <span>
      <i class="bi bi-clipboard2-check me-2"></i>All Results
      <span style="background:rgba(255,255,255,.15);font-size:11px;padding:2px 8px;border-radius:10px;margin-left:8px">
        <?php echo count($results); ?> Students
      </span>
    </span>
    <a href="add-result.php"><i class="bi bi-plus-circle me-1"></i>Add Result</a>
  </div>
  <div class="table-responsive">
    <table class="at">
      <thead>
        <tr>
          <th>#</th>
          <th>Student Name</th>
          <th>Roll ID</th>
          <th>Class</th>
          <th>Subjects</th>
          <th>Total Marks</th>
          <th>Percentage</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($results)): $i = 1; foreach ($results as $r):
          $maxMarks = (int)$r['subCount'] * 100;
          $pct      = $maxMarks > 0 ? round(((int)$r['totalMarks'] / $maxMarks) * 100, 1) : 0;
          $failChk  = mysqli_fetch_assoc(mysqli_query($conn,
              "SELECT COUNT(*) as fc FROM tblresult
               WHERE StudentId={$r['StudentId']} AND marks < 33"));
          $failed = $failChk['fc'] > 0;
        ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><strong style="color:#1a2340"><?php echo htmlspecialchars($r['StudentName']); ?></strong></td>
          <td><?php echo htmlspecialchars($r['RollId']); ?></td>
          <td>
            <span style="background:#e8f0fe;color:#1a56db;font-size:11px;font-weight:700;padding:3px 9px;border-radius:10px">
              <?php echo htmlspecialchars($r['ClassName'].'('.$r['Section'].')'); ?>
            </span>
          </td>
          <td style="text-align:center"><?php echo $r['subCount']; ?></td>
          <td><?php echo (int)$r['totalMarks']; ?>/<?php echo $maxMarks; ?></td>
          <td>
            <strong style="color:<?php echo $pct >= 33 ? '#0d7a45' : '#c0392b'; ?>">
              <?php echo $pct; ?>%
            </strong>
          </td>
          <td>
            <?php echo $failed
              ? '<span class="badge-off">FAIL</span>'
              : '<span class="badge-on">PASS</span>'; ?>
          </td>
          <td>
            <a href="edit-result.php?id=<?php echo $r['StudentId']; ?>" class="btn-edit me-1">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="manage-results.php?del=<?php echo $r['StudentId']; ?>&classId=<?php echo $filterClass; ?>"
               onclick="return confirm('Delete ALL results for this student?')"
               class="btn-del">
              <i class="bi bi-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
          <td colspan="9" style="text-align:center;padding:50px;color:#888">
            <i class="bi bi-clipboard2-x" style="font-size:2.5rem;display:block;margin-bottom:10px;color:#ccc"></i>
            No results found<?php echo $filterClass ? ' for this class' : ''; ?>.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
