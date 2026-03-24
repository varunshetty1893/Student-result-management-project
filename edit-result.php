<?php
require_once 'includes/db.php';

$studentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student   = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT s.*, c.ClassName, c.Section
     FROM tblstudents s
     LEFT JOIN tblclasses c ON s.ClassId=c.id
     WHERE s.StudentId=$studentId LIMIT 1"));
if (!$student) { header("Location: manage-results.php"); exit(); }

$activePage = 'results';
$pageTitle  = 'Edit Result';
require_once 'includes/admin-header.php';

// Get subjects for this student's class
$subjects = array();
$rs = mysqli_query($conn,
    "SELECT s.* FROM tblsubjects s
     JOIN tblsubjectcombination sc ON s.id=sc.SubjectId
     WHERE sc.ClassId={$student['ClassId']} AND sc.status=1
     ORDER BY s.SubjectName");
while ($r = mysqli_fetch_assoc($rs)) { $subjects[] = $r; }

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['marks'])) {
    foreach ($_POST['marks'] as $subjectId => $mark) {
        $subjectId = (int)$subjectId;
        $mark      = (int)$mark;
        $chk = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM tblresult WHERE StudentId=$studentId AND ClassId={$student['ClassId']} AND SubjectId=$subjectId LIMIT 1"));
        if ($chk) {
            mysqli_query($conn,
                "UPDATE tblresult SET marks=$mark, UpdationDate=NOW()
                 WHERE StudentId=$studentId AND ClassId={$student['ClassId']} AND SubjectId=$subjectId");
        } else {
            mysqli_query($conn,
                "INSERT INTO tblresult (StudentId,ClassId,SubjectId,marks)
                 VALUES ($studentId,{$student['ClassId']},$subjectId,$mark)");
        }
    }
    $success = 'Result updated successfully!';
}
?>

<div class="page-header-bar">
  <h4><i class="bi bi-pencil-square me-2"></i>Edit Result</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="manage-results.php">Manage Results</a></li>
    <li class="breadcrumb-item active">Edit Result</li>
  </ol></nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">

    <?php if ($success): ?>
    <div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Student Info -->
    <div class="ac mb-3">
      <div class="ac-head"><span><i class="bi bi-person me-2"></i>Student Info</span></div>
      <div class="ac-body">
        <div class="row g-3" style="font-size:13px">
          <div class="col-md-3"><div style="color:#888">Name</div><strong><?php echo htmlspecialchars($student['StudentName']); ?></strong></div>
          <div class="col-md-3"><div style="color:#888">Roll ID</div><strong><?php echo htmlspecialchars($student['RollId']); ?></strong></div>
          <div class="col-md-3"><div style="color:#888">Class</div><strong><?php echo htmlspecialchars($student['ClassName'].'('.$student['Section'].')'); ?></strong></div>
          <div class="col-md-3"><div style="color:#888">Gender</div><strong><?php echo htmlspecialchars($student['Gender']); ?></strong></div>
        </div>
      </div>
    </div>

    <!-- Marks Form -->
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-pencil-square me-2"></i>Edit Marks</span>
        <a href="manage-results.php">Back</a>
      </div>
      <div class="ac-body">
        <?php if (!empty($subjects)): ?>
        <form method="POST">
          <div class="table-responsive">
            <table class="at">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Subject</th>
                  <th>Code</th>
                  <th style="width:160px">Marks (out of 100)</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach ($subjects as $sub):
                  $existing = mysqli_fetch_assoc(mysqli_query($conn,
                      "SELECT marks FROM tblresult
                       WHERE StudentId=$studentId AND ClassId={$student['ClassId']} AND SubjectId={$sub['id']} LIMIT 1"));
                  $existMark = $existing ? (int)$existing['marks'] : 0;
                ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo htmlspecialchars($sub['SubjectName']); ?></td>
                  <td><span class="badge-on"><?php echo htmlspecialchars($sub['SubjectCode']); ?></span></td>
                  <td>
                    <input type="number" name="marks[<?php echo $sub['id']; ?>]"
                           class="f-input" style="width:100px"
                           value="<?php echo $existMark; ?>"
                           min="0" max="100" required/>
                  </td>
                  <td>
                    <?php if ($existMark >= 33): ?>
                      <span class="badge-on">Pass</span>
                    <?php else: ?>
                      <span class="badge-off">Fail</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i>Update Result</button>
            <a href="manage-results.php" style="background:#6c757d;color:#fff;padding:10px 20px;font-size:14px;font-weight:700;border-radius:5px;text-decoration:none">Cancel</a>
          </div>
        </form>
        <?php else: ?>
        <div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i>No subjects found for this class.</div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
