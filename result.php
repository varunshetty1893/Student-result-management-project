<?php
session_start();
require_once 'includes/db.php';


if (!isset($_SESSION['res_classId']) || !isset($_SESSION['res_rollId'])) {
    header("Location: find-result.php");
    exit();
}

$classId = (int)$_SESSION['res_classId'];
$rollId  = mysqli_real_escape_string($conn, $_SESSION['res_rollId']);

$res     = mysqli_query($conn,
    "SELECT s.*, c.ClassName, c.Section
     FROM tblstudents s
     JOIN tblclasses c ON s.ClassId=c.id
     WHERE s.RollId='$rollId' AND s.ClassId=$classId AND s.Status=1 LIMIT 1"
);
$student = mysqli_fetch_assoc($res);

if (!$student) { header("Location: find-result.php"); exit(); }

$res2    = mysqli_query($conn,
    "SELECT sub.SubjectName, sub.SubjectCode, r.marks
     FROM tblresult r
     JOIN tblsubjects sub ON r.SubjectId=sub.id
     WHERE r.StudentId=".(int)$student['StudentId']." AND r.ClassId=$classId
     ORDER BY sub.SubjectName"
);
$results = array();
while ($row = mysqli_fetch_assoc($res2)) { $results[] = $row; }

$totalMarks = 0; $obtainedMarks = 0; $failed = false; $maxPerSub = 100;
foreach ($results as $r) {
    $totalMarks    += $maxPerSub;
    $obtainedMarks += (int)$r['marks'];
    if ((int)$r['marks'] < 33) { $failed = true; }
}
$percentage = ($totalMarks > 0) ? round(($obtainedMarks/$totalMarks)*100, 2) : 0;
if ($failed)               { $grade='FAIL'; }
elseif ($percentage >= 75) { $grade='DISTINCTION'; }
elseif ($percentage >= 60) { $grade='FIRST CLASS'; }
elseif ($percentage >= 45) { $grade='SECOND CLASS'; }
else                       { $grade='PASS'; }

$pageTitle = 'Result';
require_once 'includes/header.php';
?>
<style>@media print{.no-print{display:none !important;}}</style>

<div style="background:#f4f6f9;border-bottom:1px solid #e0e0e0;padding:14px 0" class="no-print">
  <div class="container">
    <ol class="breadcrumb mb-0" style="font-size:13px">
      <li class="breadcrumb-item"><a href="index.php" style="color:#c0392b">Home</a></li>
      <li class="breadcrumb-item"><a href="find-result.php" style="color:#c0392b">Find Result</a></li>
      <li class="breadcrumb-item active">Result</li>
    </ol>
  </div>
</div>

<section style="padding:40px 0;background:#f4f6f9;min-height:60vh">
  <div class="container">
    <div class="d-flex gap-2 mb-3 no-print">
      <button onclick="window.print()" style="background:#1a2340;color:#fff;border:none;padding:8px 22px;font-size:13px;font-weight:600;border-radius:3px;cursor:pointer">
        <i class="bi bi-printer me-1"></i>Print
      </button>
      <a href="find-result.php" style="background:#eee;color:#555;padding:8px 18px;font-size:13px;font-weight:600;border-radius:3px;text-decoration:none">
        <i class="bi bi-arrow-left me-1"></i>Back
      </a>
    </div>

    <div style="background:#fff;border:1px solid #ddd;border-radius:6px;overflow:hidden;max-width:800px;margin:0 auto">
      <div style="background:#1a2340;color:#fff;padding:18px 24px">
        <div style="font-size:1rem;font-weight:800"><i class="bi bi-mortarboard-fill me-2"></i>UP-Result Management System</div>
        <div style="font-size:12px;color:#ccc;margin-top:3px">Student Result Card &nbsp;|&nbsp; <?php echo date('d M Y'); ?></div>
      </div>

      <div style="padding:16px 24px;background:#fafafa;border-bottom:1px solid #eee">
        <div class="row g-2" style="font-size:13px">
          <div class="col-6 col-md-3">
            <div style="color:#888">Student Name</div>
            <strong><?php echo htmlspecialchars($student['StudentName']); ?></strong>
          </div>
          <div class="col-6 col-md-3">
            <div style="color:#888">Roll Number</div>
            <strong><?php echo htmlspecialchars($student['RollId']); ?></strong>
          </div>
          <div class="col-6 col-md-3">
            <div style="color:#888">Class</div>
            <strong><?php echo htmlspecialchars($student['ClassName']); ?> - <?php echo htmlspecialchars($student['Section']); ?></strong>
          </div>
          <div class="col-6 col-md-3">
            <div style="color:#888">Gender</div>
            <strong><?php echo htmlspecialchars($student['Gender']); ?></strong>
          </div>
        </div>
      </div>

      <div style="padding:20px 24px">
        <?php if (!empty($results)): ?>
        <div class="table-responsive">
          <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
              <tr style="background:#f4f6f9">
                <th style="padding:10px 14px;border-bottom:2px solid #e0e0e0;color:#1a2340">#</th>
                <th style="padding:10px 14px;border-bottom:2px solid #e0e0e0;color:#1a2340">Subject</th>
                <th style="padding:10px 14px;border-bottom:2px solid #e0e0e0;color:#1a2340">Code</th>
                <th style="padding:10px 14px;border-bottom:2px solid #e0e0e0;color:#1a2340;text-align:center">Max</th>
                <th style="padding:10px 14px;border-bottom:2px solid #e0e0e0;color:#1a2340;text-align:center">Obtained</th>
                <th style="padding:10px 14px;border-bottom:2px solid #e0e0e0;color:#1a2340;text-align:center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=1; foreach ($results as $r): ?>
              <tr style="border-bottom:1px solid #f0f0f0">
                <td style="padding:10px 14px"><?php echo $i++; ?></td>
                <td style="padding:10px 14px"><?php echo htmlspecialchars($r['SubjectName']); ?></td>
                <td style="padding:10px 14px"><?php echo htmlspecialchars($r['SubjectCode']); ?></td>
                <td style="padding:10px 14px;text-align:center"><?php echo $maxPerSub; ?></td>
                <td style="padding:10px 14px;text-align:center"><strong><?php echo (int)$r['marks']; ?></strong></td>
                <td style="padding:10px 14px;text-align:center">
                  <?php if ((int)$r['marks'] >= 33): ?>
                    <span style="background:#e8fdf0;color:#0d7a45;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px">Pass</span>
                  <?php else: ?>
                    <span style="background:#fde8e8;color:#c0392b;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px">Fail</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr style="background:#f4f6f9">
                <td colspan="3" style="padding:10px 14px;font-weight:700;color:#1a2340">Total</td>
                <td style="padding:10px 14px;text-align:center;font-weight:700"><?php echo $totalMarks; ?></td>
                <td style="padding:10px 14px;text-align:center;font-weight:700"><?php echo $obtainedMarks; ?></td>
                <td style="padding:10px 14px;text-align:center">
                  <?php if ($failed): ?>
                    <span style="background:#fde8e8;color:#c0392b;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px">FAIL</span>
                  <?php else: ?>
                    <span style="background:#e8fdf0;color:#0d7a45;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px">PASS</span>
                  <?php endif; ?>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div style="margin-top:20px;padding:16px;background:#f4f6f9;border-radius:4px;border:1px solid #e0e0e0">
          <div class="row text-center g-3" style="font-size:13px">
            <div class="col-4">
              <div style="color:#888">Percentage</div>
              <div style="font-size:1.3rem;font-weight:900;color:#1a2340"><?php echo $percentage; ?>%</div>
            </div>
            <div class="col-4">
              <div style="color:#888">Result</div>
              <div style="font-size:1.3rem;font-weight:900;color:<?php echo $failed?'#c0392b':'#0d7a45'; ?>">
                <?php echo $failed?'FAIL':'PASS'; ?>
              </div>
            </div>
            <div class="col-4">
              <div style="color:#888">Grade</div>
              <div style="font-size:1.3rem;font-weight:900;color:#1a2340"><?php echo $grade; ?></div>
            </div>
          </div>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:40px;color:#888">
          <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:10px"></i>
          No result found for this student.
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
