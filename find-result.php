<?php
session_start();
require_once 'includes/db.php';


$error   = '';
$classes = array();
$res = mysqli_query($conn, "SELECT * FROM tblclasses ORDER BY ClassNameNumeric");
while ($row = mysqli_fetch_assoc($res)) { $classes[] = $row; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $classId = isset($_POST['classId']) ? (int)$_POST['classId'] : 0;
    $rollId  = isset($_POST['rollId'])  ? trim($_POST['rollId']) : '';

    if ($classId && $rollId !== '') {
        $rollId  = mysqli_real_escape_string($conn, $rollId);
        $result  = mysqli_query($conn,
            "SELECT StudentId FROM tblstudents WHERE RollId='$rollId' AND ClassId=$classId AND Status=1 LIMIT 1"
        );
        $student = mysqli_fetch_assoc($result);
        if ($student) {
            $_SESSION['res_classId'] = $classId;
            $_SESSION['res_rollId']  = $rollId;
            header("Location: result.php");
            exit();
        } else {
            $error = "No student found with this Roll Number in the selected class.";
        }
    } else {
        $error = "Please fill all fields.";
    }
}

$pageTitle = 'Find Result';
require_once 'includes/header.php';
?>

<div style="background:#f4f6f9;border-bottom:1px solid #e0e0e0;padding:14px 0">
  <div class="container">
    <ol class="breadcrumb mb-0" style="font-size:13px">
      <li class="breadcrumb-item"><a href="index.php" style="color:#c0392b">Home</a></li>
      <li class="breadcrumb-item active">Find Result</li>
    </ol>
  </div>
</div>

<section style="padding:50px 0;background:#f4f6f9;min-height:60vh">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div style="background:#fff;border:1px solid #ddd;border-radius:6px;padding:32px">
          <div style="text-align:center;margin-bottom:24px">
            <i class="bi bi-search" style="font-size:2rem;color:#c0392b"></i>
            <h4 style="font-weight:800;color:#1a1a5e;margin-top:8px;margin-bottom:4px">Find Your Result</h4>
            <p style="font-size:13px;color:#888;margin:0">Select class and enter roll number</p>
          </div>

          <?php if ($error): ?>
          <div style="background:#fde8e8;border:1px solid #f5a0a0;color:#c0392b;padding:10px 14px;border-radius:4px;font-size:13px;margin-bottom:18px">
            <i class="bi bi-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
          </div>
          <?php endif; ?>

          <form action="find-result.php" method="POST">
            <div style="margin-bottom:18px">
              <label style="font-size:13px;font-weight:700;color:#1a2340;display:block;margin-bottom:6px">Select Class</label>
              <select name="classId" style="width:100%;border:1px solid #ccc;border-radius:4px;padding:9px 12px;font-size:13px;outline:none;background:#fff" required>
                <option value="" disabled selected>-- Select Class --</option>
                <?php foreach ($classes as $cls): ?>
                <option value="<?php echo (int)$cls['id']; ?>"
                  <?php echo (isset($_POST['classId']) && $_POST['classId']==$cls['id']) ? 'selected':''; ?>>
                  <?php echo htmlspecialchars($cls['ClassName']); ?> - Section <?php echo htmlspecialchars($cls['Section']); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div style="margin-bottom:22px">
              <label style="font-size:13px;font-weight:700;color:#1a2340;display:block;margin-bottom:6px">Roll Number</label>
              <input type="text" name="rollId"
                     style="width:100%;border:1px solid #ccc;border-radius:4px;padding:9px 12px;font-size:13px;outline:none"
                     placeholder="Enter your roll number"
                     value="<?php echo isset($_POST['rollId']) ? htmlspecialchars($_POST['rollId']) : ''; ?>"
                     required/>
            </div>
            <button type="submit" style="width:100%;background:#c0392b;color:#fff;border:none;padding:11px;font-size:14px;font-weight:700;border-radius:4px;cursor:pointer">
              <i class="bi bi-search me-2"></i>Search Result
            </button>
          </form>

          <div style="text-align:center;margin-top:16px;font-size:13px">
            <a href="index.php" style="color:#c0392b;text-decoration:none;font-weight:600">
              <i class="bi bi-arrow-left me-1"></i>Back to Home
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
