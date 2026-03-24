<?php
require_once 'includes/db.php';
$activePage = 'subjects';
$pageTitle  = 'Add Subject Combination';
require_once 'includes/admin-header.php';

$classes  = array();
$subjects = array();
$res  = mysqli_query($conn, "SELECT * FROM tblclasses ORDER BY ClassNameNumeric");
while ($r = mysqli_fetch_assoc($res)) {
  $classes[]  = $r;
}
$res2 = mysqli_query($conn, "SELECT * FROM tblsubjects ORDER BY SubjectName");
while ($r = mysqli_fetch_assoc($res2)) {
  $subjects[] = $r;
}

$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $classId   = (int)$_POST['classId'];
  $subjectId = (int)$_POST['subjectId'];
  if ($classId && $subjectId) {
    $chk = mysqli_fetch_assoc(mysqli_query(
      $conn,
      "SELECT id FROM tblsubjectcombination WHERE ClassId=$classId AND SubjectId=$subjectId LIMIT 1"
    ));
    if ($chk) {
      $error = 'This combination already exists.';
    } else {
      mysqli_query(
        $conn,
        "INSERT INTO tblsubjectcombination (ClassId,SubjectId,status) VALUES ($classId,$subjectId,1)"
      );
      $success = 'Subject combination added successfully!';
    }
  } else {
    $error = 'Please select both class and subject.';
  }
}
?>

<div class="page-header-bar">
  <h4><i class="bi bi-diagram-3 me-2"></i>Add Subject Combination</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item active">Add Subject Combination</li>
    </ol>
  </nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-diagram-3 me-2"></i>Add New Combination</span>
        <a href="manage-subjectcombination.php">Manage</a>
      </div>
      <div class="ac-body">
        <?php if ($success): ?><div class="alert-ok"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
          <div class="mb-3">
            <label class="f-label">Select Class</label>
            <select name="classId" class="f-select" required>
              <option value="">-- Select Class --</option>
              <?php foreach ($classes as $c): ?>
                <option value="<?php echo $c['id']; ?>">
                  <?php echo htmlspecialchars($c['ClassName']); ?> (Section <?php echo htmlspecialchars($c['Section']); ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-4">
            <label class="f-label">Select Subject</label>
            <select name="subjectId" class="f-select" required>
              <option value="">-- Select Subject --</option>
              <?php foreach ($subjects as $s): ?>
                <option value="<?php echo $s['id']; ?>">
                  <?php echo htmlspecialchars($s['SubjectName']); ?> (<?php echo htmlspecialchars($s['SubjectCode']); ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i>Add Combination</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>