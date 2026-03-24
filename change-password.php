<?php
require_once 'includes/db.php';
$activePage = 'change-password';
$pageTitle  = 'Change Password';
require_once 'includes/admin-header.php';

$success = ''; $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPass = trim($_POST['currentPass']);
    $newPass     = trim($_POST['newPass']);
    $confirmPass = trim($_POST['confirmPass']);

    if ($currentPass && $newPass && $confirmPass) {
        if ($newPass !== $confirmPass) {
            $error = 'New password and confirm password do not match.';
        } elseif (strlen($newPass) < 6) {
            $error = 'New password must be at least 6 characters.';
        } else {
            // Get current admin
            $u    = mysqli_real_escape_string($conn, $_SESSION['alogin']);
            $admin = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT * FROM admin WHERE UserName='$u' LIMIT 1"));

            if ($admin) {
                $dbPass = $admin['Password'];
                // Support plain text or md5
                if ($currentPass === $dbPass || md5($currentPass) === $dbPass) {
                    // Save new password as plain text
                    $newPassEsc = mysqli_real_escape_string($conn, $newPass);
                    mysqli_query($conn,
                        "UPDATE admin SET Password='$newPassEsc', updationDate=NOW()
                         WHERE UserName='$u'");
                    $success = 'Password changed successfully!';
                } else {
                    $error = 'Current password is incorrect.';
                }
            } else {
                $error = 'Admin account not found.';
            }
        }
    } else {
        $error = 'Please fill all fields.';
    }
}
?>

<div class="page-header-bar">
  <h4><i class="bi bi-key me-2"></i>Change Password</h4>
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active">Change Password</li>
  </ol></nav>
</div>

<div class="row justify-content-center">
  <div class="col-lg-5 col-md-7">
    <div class="ac">
      <div class="ac-head">
        <span><i class="bi bi-shield-lock me-2"></i>Change Your Password</span>
      </div>
      <div class="ac-body">

        <?php if ($success): ?>
        <div class="alert-ok"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert-err"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Admin info -->
        <div style="background:#f8f9fc;border:1px solid #e4e7ec;border-radius:6px;padding:14px 16px;margin-bottom:22px;display:flex;align-items:center;gap:12px">
          <div style="width:40px;height:40px;border-radius:50%;background:#1a2340;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;flex-shrink:0">
            <i class="bi bi-person-fill"></i>
          </div>
          <div>
            <div style="font-size:13px;font-weight:700;color:#1a2340"><?php echo htmlspecialchars($_SESSION['alogin']); ?></div>
            <div style="font-size:11px;color:#888">Administrator</div>
          </div>
        </div>

        <form method="POST">

          <div class="mb-3">
            <label class="f-label">Current Password <span style="color:#c0392b">*</span></label>
            <div style="position:relative">
              <input type="password" name="currentPass" id="curPwd" class="f-input"
                     style="padding-right:40px"
                     placeholder="Enter current password" required/>
              <span onclick="togglePwd('curPwd','eyeCur')"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#aaa">
                <i class="bi bi-eye" id="eyeCur"></i>
              </span>
            </div>
          </div>

          <div class="mb-3">
            <label class="f-label">New Password <span style="color:#c0392b">*</span></label>
            <div style="position:relative">
              <input type="password" name="newPass" id="newPwd" class="f-input"
                     style="padding-right:40px"
                     placeholder="Minimum 6 characters" required/>
              <span onclick="togglePwd('newPwd','eyeNew')"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#aaa">
                <i class="bi bi-eye" id="eyeNew"></i>
              </span>
            </div>
          </div>

          <div class="mb-4">
            <label class="f-label">Confirm New Password <span style="color:#c0392b">*</span></label>
            <div style="position:relative">
              <input type="password" name="confirmPass" id="conPwd" class="f-input"
                     style="padding-right:40px"
                     placeholder="Re-enter new password" required/>
              <span onclick="togglePwd('conPwd','eyeCon')"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#aaa">
                <i class="bi bi-eye" id="eyeCon"></i>
              </span>
            </div>
          </div>

          <!-- Password strength indicator -->
          <div style="margin-bottom:20px">
            <div style="font-size:12px;color:#888;margin-bottom:6px">Password strength</div>
            <div style="height:5px;background:#eee;border-radius:3px;overflow:hidden">
              <div id="strengthBar" style="height:100%;width:0;border-radius:3px;transition:width .3s,background .3s"></div>
            </div>
            <div id="strengthText" style="font-size:11px;color:#aaa;margin-top:4px"></div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn-save">
              <i class="bi bi-check-lg me-1"></i>Update Password
            </button>
            <a href="dashboard.php"
               style="background:#6c757d;color:#fff;padding:10px 20px;font-size:14px;font-weight:700;border-radius:5px;text-decoration:none">
              Cancel
            </a>
          </div>

        </form>
      </div>
    </div>

    <!-- Tips -->
    <div class="ac mt-3">
      <div class="ac-head"><span><i class="bi bi-lightbulb me-2"></i>Password Tips</span></div>
      <div class="ac-body" style="padding:16px 20px">
        <ul style="font-size:13px;color:#555;padding-left:18px;margin:0;line-height:2">
          <li>Use at least 6 characters</li>
          <li>Mix uppercase, lowercase letters and numbers</li>
          <li>Avoid using your name or email as password</li>
          <li>Do not share your password with anyone</li>
        </ul>
      </div>
    </div>

  </div>
</div>

<script>
function togglePwd(fieldId, iconId) {
    var f = document.getElementById(fieldId);
    var i = document.getElementById(iconId);
    if (f.type === 'password') {
        f.type = 'text';
        i.className = 'bi bi-eye-slash';
    } else {
        f.type = 'password';
        i.className = 'bi bi-eye';
    }
}

// Password strength checker
document.getElementById('newPwd').addEventListener('input', function() {
    var val  = this.value;
    var bar  = document.getElementById('strengthBar');
    var txt  = document.getElementById('strengthText');
    var score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    var colors = ['#e74c3c','#e74c3c','#f39c12','#2ecc71','#27ae60'];
    var labels = ['','Weak','Fair','Good','Strong'];
    var widths = ['0%','30%','55%','75%','100%'];

    if (val.length === 0) {
        bar.style.width = '0'; txt.textContent = '';
    } else {
        bar.style.width    = widths[score] || '100%';
        bar.style.background = colors[score] || '#27ae60';
        txt.textContent    = labels[score] || 'Strong';
        txt.style.color    = colors[score] || '#27ae60';
    }
});
</script>

<?php require_once 'includes/admin-footer.php'; ?>
