<?php
require_once 'includes/db.php';
$activePage = 'results';
$pageTitle  = 'Add Result';
require_once 'includes/admin-header.php';

$classes  = array();
$res = mysqli_query($conn, "SELECT * FROM tblclasses ORDER BY ClassNameNumeric");
while ($r = mysqli_fetch_assoc($res)) { $classes[] = $r; }

$students = array();
$subjects = array();

// Load students by class (AJAX or on class select)
if (isset($_POST['classId']) && !isset($_POST['marks'])) {
    $cid = (int)$_POST['classId'];
    $rs  = mysqli_query($conn, "SELECT * FROM tblstudents WHERE ClassId=$cid AND Status=1 ORDER BY StudentName");
    while ($r = mysqli_fetch_assoc($rs)) { $students[] = $r; }
    $rs2 = mysqli_query($conn,
        "SELECT s.* FROM tblsubjects s
         JOIN tblsubjectcombination sc ON s.id=sc.SubjectId
         WHERE sc.ClassId=$cid AND sc.status=1 ORDER BY s.SubjectName");
    while ($r = mysqli_fetch_assoc($rs2)) { $subjects[] = $r; }
}

$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['marks'])) {
    $classId   = (int)$_POST['classId'];
    $studentId = (int)$_POST['studentId'];
    $marks     = $_POST['marks'];

    if ($classId && $studentId && !empty($marks)) {
        $inserted = 0; $skipped = 0;
        foreach ($marks as $subjectId => $mark) {
            $subjectId = (int)$subjectId;
            $mark      = (int)$mark;
            $chk = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT id FROM tblresult WHERE StudentId=$studentId AND ClassId=$classId AND SubjectId=$subjectId LIMIT 1"));
            if ($chk) {
                mysqli_query($conn,
                    "UPDATE tblresult SET marks=$mark, UpdationDate=NOW()
                     WHERE StudentId=$studentId AND ClassId=$classId AND SubjectId=$subjectId");
                $skipped++;
            } else {
                mysqli_query($conn,
                    "INSERT INTO tblresult (StudentId,ClassId,SubjectId,marks)
                     VALUES ($studentId,$classId,$subjectId,$mark)");
                $inserted++;
            }
        }
        $success = "Result saved! ($inserted added, $skipped updated)";
    } else { $error = 'Please fill all fields.'; }

    // reload students/subjects for same class
    $cid = (int)$_POST['classId'];
    $rs  = mysqli_query($conn, "SELECT * FROM tblstudents WHERE ClassId=$cid AND Status=1 ORDER BY StudentName");
    while ($r = mysqli_fetch_assoc($rs)) { $students[] = $r; }
    $rs2 = mysqli_query($conn,
        "SELECT s.* FROM tblsubjects s
         JOIN tblsubjectcombination sc ON s.id=sc.SubjectId
         WHERE sc.ClassId=$cid AND sc.status=1 ORDER BY s.SubjectName");
    while ($r = mysqli_fetch_assoc($rs2)) { $subjects[] = $r; }
}

$selClass   = isset($_POST['classId'])   ? (int)$_POST['classId']   : 0;
$selStudent = isset($_POST['studentId']) ? (int)$_POST['studentId'] : 0;
?>

<div class="page-header-bar">
  <h4><i class="bi bi-clipboard2-plus me-2"></i>Add Result</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Add Result</li>
  </ol></nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">

    <?php if ($success): ?>
    <div class="alert-ok mb-3"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="alert-err mb-3"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Step 1: Select Class + Student -->
    <div class="ac mb-3">
      <div class="ac-head"><span><i class="bi bi-filter me-2"></i>Step 1 — Select Class &amp; Student</span></div>
      <div class="ac-body">
        <form method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="f-label">Select Class</label>
              <select name="classId" class="f-select" required onchange="this.form.submit()">
                <option value="">-- Select Class --</option>
                <?php foreach ($classes as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php echo $selClass==$c['id'] ? 'selected':''; ?>>
                  <?php echo htmlspecialchars($c['ClassName']); ?> (<?php echo $c['Section']; ?>)
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php if (!empty($students)): ?>
            <div class="col-md-6">
              <label class="f-label">Select Student</label>
              <select name="studentId" class="f-select" required onchange="this.form.submit()">
                <option value="">-- Select Student --</option>
                <?php foreach ($students as $s): ?>
                <option value="<?php echo $s['StudentId']; ?>" <?php echo $selStudent==$s['StudentId'] ? 'selected':''; ?>>
                  <?php echo htmlspecialchars($s['StudentName']); ?> (Roll: <?php echo $s['RollId']; ?>)
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Step 2: Enter Marks -->
    <?php if ($selClass && $selStudent && !empty($subjects)): ?>
    <div class="ac">
      <div class="ac-head"><span><i class="bi bi-pencil-square me-2"></i>Step 2 — Enter Marks</span></div>
      <div class="ac-body">
        <form method="POST">
          <input type="hidden" name="classId"   value="<?php echo $selClass; ?>"/>
          <input type="hidden" name="studentId" value="<?php echo $selStudent; ?>"/>

          <div class="table-responsive">
            <table class="at">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Subject</th>
                  <th>Code</th>
                  <th style="width:160px">Marks (out of 100)</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach ($subjects as $sub):
                  // Check existing marks
                  $existing = mysqli_fetch_assoc(mysqli_query($conn,
                      "SELECT marks FROM tblresult WHERE StudentId=$selStudent AND ClassId=$selClass AND SubjectId={$sub['id']} LIMIT 1"));
                  $existMark = $existing ? $existing['marks'] : '';
                ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo htmlspecialchars($sub['SubjectName']); ?></td>
                  <td><span class="badge-on"><?php echo htmlspecialchars($sub['SubjectCode']); ?></span></td>
                  <td>
                    <input type="number" name="marks[<?php echo $sub['id']; ?>]"
                           class="f-input" style="width:100px"
                           value="<?php echo $existMark; ?>"
                           min="0" max="100" placeholder="0-100" required/>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div style="margin-top:16px">
            <button type="submit" class="btn-save">
              <i class="bi bi-check-lg me-1"></i>Save Result
            </button>
          </div>
        </form>
      </div>
    </div>
    <?php elseif ($selClass && empty($subjects)): ?>
    <div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i>No subjects found for this class. Please add subject combinations first.</div>
    <?php endif; ?>

  </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
