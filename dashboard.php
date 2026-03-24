<?php
require_once 'includes/db.php';
$activePage = 'dashboard';
$pageTitle  = 'Dashboard';
require_once 'includes/admin-header.php';

// Stats
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM tblstudents"))['cnt'];
$totalSubjects = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM tblsubjects"))['cnt'];
$totalClasses  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM tblclasses"))['cnt'];
$totalResults  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT StudentId) as cnt FROM tblresult"))['cnt'];

// Recent Results
$recentResults = array();
$rr = mysqli_query($conn,
    "SELECT s.StudentName, s.RollId, s.RegDate, s.Status, c.ClassName, c.Section
     FROM tblstudents s
     LEFT JOIN tblclasses c ON s.ClassId=c.id
     WHERE s.StudentId IN (SELECT DISTINCT StudentId FROM tblresult)
     ORDER BY s.RegDate DESC LIMIT 10"
);
while ($row = mysqli_fetch_assoc($rr)) { $recentResults[] = $row; }
?>

<!-- Page Header -->
<div class="page-header-bar">
  <h4><i class="bi bi-grid-fill me-2"></i>Dashboard</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-circle"><i class="bi bi-people-fill"></i></div>
      <div class="stat-num"><?php echo $totalStudents; ?></div>
      <div class="stat-label">Registered Users</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-circle"><i class="bi bi-pencil-square"></i></div>
      <div class="stat-num"><?php echo $totalSubjects; ?></div>
      <div class="stat-label">Subjects Listed</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-circle"><i class="bi bi-bank"></i></div>
      <div class="stat-num"><?php echo $totalClasses; ?></div>
      <div class="stat-label">Total classes listed</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-circle"><i class="bi bi-file-earmark-text"></i></div>
      <div class="stat-num"><?php echo $totalResults; ?></div>
      <div class="stat-label">Results Declared</div>
    </div>
  </div>
</div>

<!-- RECENT RESULTS TABLE -->
<div class="ac">
  <div class="ac-head">
    <span><i class="bi bi-table me-2"></i>Recent declared Results</span>
    <a href="manage-results.php">View All</a>
  </div>
  <div class="table-responsive">
    <table class="at">
      <thead>
        <tr>
          <th>#</th>
          <th>Student Name</th>
          <th>Roll Id</th>
          <th>Class</th>
          <th>Registration Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($recentResults)): ?>
          <?php $i=1; foreach ($recentResults as $r): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($r['StudentName']); ?></td>
            <td><?php echo htmlspecialchars($r['RollId']); ?></td>
            <td><?php echo htmlspecialchars($r['ClassName'].'('.$r['Section'].')'); ?></td>
            <td><?php echo $r['RegDate']; ?></td>
            <td>
              <?php if ($r['Status']==1): ?>
                <span class="badge-on">Active</span>
              <?php else: ?>
                <span class="badge-off">Inactive</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="6" style="text-align:center;padding:30px;color:#888">
            No results declared yet.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <th>#</th><th>Student Name</th><th>Roll Id</th>
          <th>Class</th><th>Registration Date</th><th>Status</th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
