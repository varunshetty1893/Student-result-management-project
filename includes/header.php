<?php
// includes/header.php — shared public header
// Set $pageTitle before including
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle).' | UP-Result' : 'UP-Result'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="style.css" rel="stylesheet"/>
  <style>
    /* ── TOP BAR ── */
    .top-bar { background: #1a2340; color: #ccc; font-size: 13px; padding: 7px 0; }
    .top-bar a { color: #ccc; text-decoration: none; margin-left: 16px; }
    .top-bar a:hover { color: #fff; }
    .top-bar .social a {
      display: inline-flex; align-items: center; justify-content: center;
      width: 28px; height: 28px; background: transparent;
      color: #fff; font-size: 14px; margin-left: 10px;
    }
    .top-bar .social a:hover { color: #c0392b; }

    /* ── MAIN NAVBAR ── */
    .main-navbar { background: #fff; border-bottom: 1px solid #eee; padding: 0; }
    .navbar-inner {
      display: flex; align-items: center; justify-content: space-between;
      padding: 14px 0;
    }

    /* Logo */
    .brand-logo {
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }
    .brand-logo .brand-name {
      font-size: 1.35rem;
      font-weight: 900;
      color: #1a1a5e;
      letter-spacing: 1px;
      display: block;
      border-bottom: 2px solid #1a1a5e;
      padding-bottom: 2px;
      line-height: 1;
    }
    .brand-logo .brand-sub {
      font-size: 11px;
      color: #555;
      font-weight: 400;
      display: block;
      margin-top: 3px;
      letter-spacing: .3px;
    }

    /* Nav links */
    .nav-links { display: flex; align-items: center; gap: 4px; }
    .nav-link-item {
      color: #555 !important;
      font-size: 14px;
      font-weight: 500;
      padding: 6px 14px !important;
      text-decoration: none;
      display: inline-block;
    }
    .nav-link-item:hover  { color: #c0392b !important; }
    .nav-link-item.active { color: #c0392b !important; font-weight: 700; }

    /* Search button */
    .btn-search {
      background: #c0392b; color: #fff; border: none;
      padding: 10px 32px; font-size: 14px; font-weight: 700;
      border-radius: 4px; cursor: pointer; letter-spacing: .3px;
    }
    .btn-search:hover { background: #a93226; }

    /* Footer brand override */
    .footer-brand-center { text-align: center; }
    .fb-name { font-size: 1.3rem; font-weight: 900; color: #fff; letter-spacing: 1px; border-bottom: 2px solid #fff; display: inline-block; padding-bottom: 2px; }
    .fb-sub  { font-size: 12px; color: #aaa; margin-top: 4px; }
  </style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <i class="bi bi-telephone-fill me-1"></i> (+91) 0000 0000 00
      <a href="#">Help Center</a>
      <a href="#">Upresults.in</a>
    </div>
    <div class="social d-flex align-items-center">
      <a href="#"><i class="bi bi-facebook"></i></a>
      <a href="#"><i class="bi bi-twitter"></i></a>
      <a href="#"><i class="bi bi-instagram"></i></a>
      <a href="#"><i class="bi bi-linkedin"></i></a>
    </div>
  </div>
</div>

<!-- MAIN NAVBAR -->
<nav class="main-navbar">
  <div class="container navbar-inner">

    <!-- Logo -->
    <a href="index.php" class="brand-logo">
      <span class="brand-name">UP-RESULT</span>
      <span class="brand-sub">Developed by Team MVP</span>
    </a>

    <!-- Nav Links -->
    <?php $cur = basename($_SERVER['PHP_SELF']); ?>
    <div class="nav-links">
      <a href="index.php"
         class="nav-link-item <?php echo ($cur=='index.php') ? 'active' : ''; ?>">Home</a>
      <a href="find-result.php"
         class="nav-link-item <?php echo ($cur=='find-result.php'||$cur=='result.php') ? 'active' : ''; ?>">Students</a>
      <a href="admin-login.php"
         class="nav-link-item <?php echo ($cur=='admin-login.php') ? 'active' : ''; ?>">Admin</a>
    </div>

    <!-- Search Button -->
    <button class="btn-search"
      onclick="var el=document.getElementById('result-lookup');
               if(el){el.scrollIntoView({behavior:'smooth'});}
               else{window.location='index.php#result-lookup';}">
      Search
    </button>

  </div>
</nav>
