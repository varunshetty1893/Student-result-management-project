<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['alogin'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $u      = mysqli_real_escape_string($conn, $username);
        $result = mysqli_query($conn, "SELECT * FROM admin WHERE UserName='$u' LIMIT 1");
        $admin  = mysqli_fetch_assoc($result);

        if ($admin) {
            $dbPass = $admin['Password'];
            // Support plain text OR md5 stored password
            if ($password === $dbPass || md5($password) === $dbPass) {
                $_SESSION['alogin'] = $admin['UserName'];
                $_SESSION['aid']    = $admin['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = 'Invalid username or password. Please try again.';
            }
        } else {
            $error = 'Invalid username or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login | UP-Result</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <style>
    body { background: #f4f6f9; font-family: Arial, sans-serif; }
    .login-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 30px 15px; }
    .login-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; width: 100%; max-width: 440px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
    .login-top { background: #1a2340; padding: 30px 32px 24px; text-align: center; }
    .logo-text { font-size: 1.5rem; font-weight: 900; color: #fff; letter-spacing: 2px; border-bottom: 2px solid #fff; display: inline-block; padding-bottom: 3px; }
    .logo-sub  { display: block; font-size: 12px; color: #aaa; margin-top: 5px; }
    .login-top h5 { color: #ccc; font-size: 14px; font-weight: 400; margin-top: 16px; margin-bottom: 0; }
    .login-body { padding: 28px 32px 32px; }
    .flabel { font-size: 13px; font-weight: 700; color: #1a2340; margin-bottom: 7px; display: block; }
    .igrp { position: relative; }
    .finput { width: 100%; border: 1px solid #ccc; border-radius: 5px; padding: 11px 14px 11px 40px; font-size: 13px; color: #333; outline: none; font-family: Arial, sans-serif; transition: border-color .2s; }
    .finput:focus { border-color: #c0392b; box-shadow: 0 0 0 3px rgba(192,57,43,.1); }
    .iico { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 15px; }
    .btn-go { width: 100%; background: #c0392b; color: #fff; border: none; padding: 13px; font-size: 15px; font-weight: 700; border-radius: 5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-go:hover { background: #a93226; }
    .err-box { background: #fde8e8; border: 1px solid #f5a0a0; color: #c0392b; padding: 11px 14px; border-radius: 5px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
  </style>
</head>
<body>
<div class="login-wrapper">
  <div style="width:100%;max-width:440px">

    <div class="login-card">
      <div class="login-top">
        <span class="logo-text">UP-RESULT</span>
        <span class="logo-sub">Developed by Team MVP</span>
        <h5><i class="bi bi-shield-lock-fill me-2"></i>Admin Login Panel</h5>
      </div>
      <div class="login-body">

        <?php if ($error): ?>
        <div class="err-box">
          <i class="bi bi-exclamation-circle-fill"></i>
          <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form action="admin-login.php" method="POST">

          <div style="margin-bottom:18px">
            <label class="flabel">Username / Email</label>
            <div class="igrp">
              <i class="bi bi-person-fill iico"></i>
              <input type="text" name="username" class="finput"
                     placeholder="Admin"
                     value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                     required/>
            </div>
          </div>

          <div style="margin-bottom:10px">
            <label class="flabel">Password</label>
            <div class="igrp">
              <i class="bi bi-lock-fill iico"></i>
              <input type="password" name="password" id="pwdField"
                     class="finput" placeholder="Enter your password" required/>
            </div>
          </div>

          <div style="text-align:right;margin-bottom:22px">
            <label style="font-size:12px;color:#888;cursor:pointer;user-select:none">
              <input type="checkbox"
                     onchange="document.getElementById('pwdField').type=this.checked?'text':'password'"
                     style="margin-right:5px"/>
              Show password
            </label>
          </div>

          <button type="submit" class="btn-go">
            <i class="bi bi-box-arrow-in-right"></i> Login
          </button>

        </form>

        <div style="text-align:center;margin-top:18px;font-size:13px">
          <a href="index.php" style="color:#c0392b;text-decoration:none;font-weight:600">
            <i class="bi bi-arrow-left me-1"></i>Back to Home
          </a>
        </div>

      </div>
    </div>

    <div style="text-align:center;font-size:12px;color:#aaa;margin-top:18px">
      &copy; 2024 Student Result Management System
    </div>

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
