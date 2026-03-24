<?php
// includes/admin-header.php
// Usage: require_once 'includes/admin-header.php'; at top of every admin page
// Set $activePage = 'dashboard' | 'students' | 'classes' | 'subjects' | 'results' | 'notices' before including

if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['alogin'])) {
    header("Location: admin-login.php");
    exit();
}
$activePage = isset($activePage) ? $activePage : '';
$pageTitle  = isset($pageTitle)  ? $pageTitle  : 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo htmlspecialchars($pageTitle); ?> | UP-Result Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <style>
    /* ══════════════════════════════════════════════
       RESET & BASE
    ══════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; color: #333; }
    a { text-decoration: none; }

    /* ══════════════════════════════════════════════
       TOP BAR
    ══════════════════════════════════════════════ */
    .topbar {
      position: fixed; top: 0; left: 0; right: 0; height: 58px;
      background: linear-gradient(90deg, #1a2340 0%, #2c3e6b 100%);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 20px 0 0; z-index: 1050;
      box-shadow: 0 2px 12px rgba(0,0,0,.25);
    }

    /* Brand inside topbar (covers sidebar width) */
    .topbar-brand {
      width: 260px; min-width: 260px;
      display: flex; align-items: center; gap: 10px;
      padding: 0 20px;
      background: rgba(0,0,0,.15);
      height: 100%;
      border-right: 1px solid rgba(255,255,255,.08);
    }
    .topbar-brand .brand-icon {
      width: 34px; height: 34px; background: #c0392b;
      border-radius: 6px; display: flex; align-items: center;
      justify-content: center; font-size: 1rem; color: #fff;
      flex-shrink: 0;
    }
    .topbar-brand .brand-text .bn { font-size: .95rem; font-weight: 900; color: #fff; letter-spacing: .5px; line-height: 1.1; }
    .topbar-brand .brand-text .bs { font-size: 10px; color: rgba(255,255,255,.5); }

    /* Right side icons */
    .topbar-right { display: flex; align-items: center; gap: 6px; }
    .tb-icon-btn {
      width: 36px; height: 36px; border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,.7); font-size: 1rem;
      background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);
      transition: background .2s, color .2s; cursor: pointer;
    }
    .tb-icon-btn:hover { background: rgba(255,255,255,.15); color: #fff; }

    /* Admin info pill */
    .admin-pill {
      display: flex; align-items: center; gap: 8px;
      background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
      border-radius: 20px; padding: 4px 14px 4px 6px;
      margin-left: 4px;
    }
    .admin-pill .ap-avatar {
      width: 28px; height: 28px; border-radius: 50%;
      background: #c0392b; display: flex; align-items: center;
      justify-content: center; font-size: .75rem; font-weight: 700; color: #fff;
    }
    .admin-pill .ap-name { font-size: 12px; color: rgba(255,255,255,.85); font-weight: 600; }

    /* Logout btn */
    .btn-topbar-logout {
      display: flex; align-items: center; gap: 6px;
      background: #c0392b; color: #fff; border: none;
      padding: 7px 16px; border-radius: 6px; font-size: 12px;
      font-weight: 700; cursor: pointer; margin-left: 8px;
      transition: background .2s;
    }
    .btn-topbar-logout:hover { background: #a93226; color: #fff; }

    /* ══════════════════════════════════════════════
       SIDEBAR
    ══════════════════════════════════════════════ */
    .sidebar {
      position: fixed; top: 58px; left: 0; bottom: 0;
      width: 260px; background: #1a2340;
      overflow-y: auto; z-index: 1040;
      border-right: 1px solid rgba(255,255,255,.05);
    }
    /* Scrollbar */
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 2px; }

    /* Avatar area */
    .sb-avatar {
      padding: 22px 20px 18px; text-align: center;
      border-bottom: 1px solid rgba(255,255,255,.06);
      background: rgba(0,0,0,.12);
    }
    .sb-avatar-img {
      width: 70px; height: 70px; border-radius: 50%;
      margin: 0 auto 10px; overflow: hidden;
      border: 3px solid rgba(255,255,255,.15);
      background: #2c3e6b;
      display: flex; align-items: center; justify-content: center;
    }
    .sb-avatar-img img { width: 100%; height: 100%; object-fit: cover; }
    .sb-avatar-img i { font-size: 2rem; color: rgba(255,255,255,.5); }
    .sb-name { font-size: 13px; font-weight: 700; color: #fff; letter-spacing: .5px; }
    .sb-role { font-size: 11px; color: rgba(255,255,255,.4); margin-top: 2px; }

    /* Section label */
    .sb-label {
      font-size: 10px; font-weight: 700; letter-spacing: 1.8px;
      color: rgba(255,255,255,.3); text-transform: uppercase;
      padding: 16px 18px 5px;
    }

    /* Menu item */
    .sb-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 18px; color: rgba(255,255,255,.65); font-size: 13px;
      font-weight: 600; cursor: pointer; border: none; background: transparent;
      width: 100%; text-align: left; border-left: 3px solid transparent;
      transition: background .15s, color .15s, border-color .15s;
    }
    .sb-item:hover { background: rgba(255,255,255,.06); color: #fff; }
    .sb-item.active {
      background: rgba(192,57,43,.15); color: #fff;
      border-left-color: #c0392b;
    }
    .sb-item .si-left { display: flex; align-items: center; gap: 10px; }
    .sb-item .si-left i { font-size: 14px; width: 18px; text-align: center; }
    .sb-item .si-arrow { font-size: 11px; transition: transform .2s; }
    .sb-item.open .si-arrow { transform: rotate(90deg); }

    /* Submenu */
    .sb-sub { display: none; background: rgba(0,0,0,.12); }
    .sb-sub.open { display: block; }
    .sb-sub a {
      display: block; padding: 8px 18px 8px 46px;
      color: rgba(255,255,255,.5); font-size: 12px;
      border-left: 3px solid transparent;
      transition: color .15s, background .15s;
    }
    .sb-sub a:hover { color: #fff; background: rgba(255,255,255,.04); }
    .sb-sub a.active { color: #c0392b; border-left-color: #c0392b; }

    /* Divider */
    .sb-divider { border: none; border-top: 1px solid rgba(255,255,255,.06); margin: 6px 0; }

    /* ══════════════════════════════════════════════
       MAIN CONTENT
    ══════════════════════════════════════════════ */
    .main-content {
      margin-left: 260px; margin-top: 58px;
      padding: 26px 24px 50px;
      min-height: calc(100vh - 58px);
    }

    /* Page header bar */
    .page-header-bar {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 22px; flex-wrap: wrap; gap: 10px;
    }
    .page-header-bar h4 {
      font-size: 1.3rem; font-weight: 800; color: #1a2340; margin: 0;
    }
    .breadcrumb { font-size: 12px; margin: 0; background: transparent; padding: 0; }
    .breadcrumb-item a { color: #c0392b; }
    .breadcrumb-item.active { color: #888; }
    .breadcrumb-item + .breadcrumb-item::before { color: #bbb; }

    /* ══════════════════════════════════════════════
       ADMIN CARDS (reusable)
    ══════════════════════════════════════════════ */
    .ac { background: #fff; border: 1px solid #e4e7ec; border-radius: 8px; overflow: hidden; }
    .ac-head {
      background: linear-gradient(90deg, #1a2340, #2c3e6b);
      color: #fff; padding: 13px 18px;
      display: flex; align-items: center; justify-content: space-between;
      font-size: 14px; font-weight: 700;
    }
    .ac-head a { color: rgba(255,255,255,.7); font-size: 12px; font-weight: 400; }
    .ac-head a:hover { color: #fff; }
    .ac-body { padding: 20px; }

    /* STAT CARDS */
    .stat-card {
      background: #fff; border-radius: 8px; border: 1px solid #e4e7ec;
      padding: 24px 20px; text-align: center;
      transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.1); transform: translateY(-2px); }
    .stat-circle {
      width: 58px; height: 58px; border-radius: 50%;
      border: 2px solid #1a2340;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 12px; font-size: 1.35rem; color: #1a2340;
    }
    .stat-num   { font-size: 2rem; font-weight: 900; color: #1a2340; line-height: 1; }
    .stat-label { font-size: 12px; color: #888; margin-top: 6px; }

    /* TABLES */
    .at { width: 100%; border-collapse: collapse; font-size: 13px; }
    .at thead th {
      padding: 11px 14px; color: #1a2340; font-weight: 700;
      border-bottom: 2px solid #e4e7ec; background: #f8f9fc;
      white-space: nowrap;
    }
    .at tbody td { padding: 10px 14px; border-bottom: 1px solid #f0f2f5; vertical-align: middle; }
    .at tbody tr:last-child td { border-bottom: none; }
    .at tbody tr:hover td { background: #fef9f9; }
    .at tfoot th { padding: 10px 14px; background: #f8f9fc; border-top: 2px solid #e4e7ec; color: #1a2340; font-weight: 700; }

    /* BADGES */
    .badge-on  { background: #e8fdf0; color: #0d7a45; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
    .badge-off { background: #fde8e8; color: #c0392b; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }

    /* BUTTONS */
    .btn-add    { background: #1a2340; color: #fff; border: none; padding: 9px 20px; font-size: 13px; font-weight: 700; border-radius: 5px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-add:hover { background: #c0392b; color: #fff; }
    .btn-edit   { background: #1a56db; color: #fff; border: none; padding: 5px 12px; font-size: 12px; font-weight: 600; border-radius: 4px; cursor: pointer; }
    .btn-edit:hover { background: #1340b0; }
    .btn-del    { background: #c0392b; color: #fff; border: none; padding: 5px 12px; font-size: 12px; font-weight: 600; border-radius: 4px; cursor: pointer; }
    .btn-del:hover  { background: #a93226; }
    .btn-save   { background: #c0392b; color: #fff; border: none; padding: 10px 28px; font-size: 14px; font-weight: 700; border-radius: 5px; cursor: pointer; }
    .btn-save:hover { background: #a93226; }

    /* FORM FIELDS */
    .f-label { font-size: 13px; font-weight: 700; color: #1a2340; display: block; margin-bottom: 6px; }
    .f-input, .f-select, .f-textarea {
      width: 100%; border: 1px solid #d0d5dd; border-radius: 5px;
      padding: 9px 13px; font-size: 13px; color: #333;
      outline: none; font-family: Arial, sans-serif;
      transition: border-color .2s, box-shadow .2s; background: #fff;
    }
    .f-input:focus, .f-select:focus, .f-textarea:focus {
      border-color: #c0392b; box-shadow: 0 0 0 3px rgba(192,57,43,.1);
    }
    .f-textarea { resize: vertical; min-height: 100px; }

    /* ALERTS */
    .alert-ok  { background: #e8fdf0; border: 1px solid #a3d9b8; color: #0d7a45; padding: 11px 16px; border-radius: 5px; font-size: 13px; margin-bottom: 16px; }
    .alert-err { background: #fde8e8; border: 1px solid #f5a0a0; color: #c0392b; padding: 11px 16px; border-radius: 5px; font-size: 13px; margin-bottom: 16px; }

    /* ADMIN FOOTER */
    .admin-footer { background: #1a2340; color: #aaa; padding: 13px 24px; font-size: 12px; text-align: center; margin-left: 260px; }

    /* RESPONSIVE */
    @media (max-width: 991px) {
      .sidebar { transform: translateX(-260px); transition: transform .3s; }
      .sidebar.sb-open { transform: translateX(0); }
      .main-content { margin-left: 0; }
      .admin-footer { margin-left: 0; }
      .topbar-brand { width: auto; min-width: auto; }
    }
  </style>
</head>
<body>

<!-- ══ TOP BAR ══ -->
<div class="topbar">
  <div class="topbar-brand">
    <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
    <div class="brand-text">
      <div class="bn">UP-RESULT</div>
      <div class="bs">Developed by Team MVP</div>
    </div>
  </div>
  <div class="topbar-right">
    <button class="tb-icon-btn d-lg-none" onclick="toggleSidebar()">
      <i class="bi bi-list"></i>
    </button>
    <a href="dashboard.php" class="tb-icon-btn" title="Dashboard">
      <i class="bi bi-grid-fill"></i>
    </a>
    <a href="index.php" class="tb-icon-btn" title="View Site" target="_blank">
      <i class="bi bi-globe"></i>
    </a>
    <div class="admin-pill">
      <div class="ap-avatar">A</div>
      <div class="ap-name"><?php echo htmlspecialchars(explode('@', $_SESSION['alogin'])[0]); ?></div>
    </div>
    <a href="logout.php" class="btn-topbar-logout">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </div>
</div>

<!-- ══ SIDEBAR ══ -->
<div class="sidebar" id="mainSidebar">

  <!-- Avatar -->
  <div class="sb-avatar">
    <div class="sb-avatar-img">
      <img src="images/avatar.jfif"
           onerror="this.style.display='none';this.nextSibling.style.display='block'"
           alt="Admin"/>
      <i class="bi bi-person-fill" style="display:none"></i>
    </div>
    <div class="sb-name">ADMIN</div>
    <div class="sb-role">Administrator</div>
  </div>

  <!-- Main Category -->
  <div class="sb-label">Main Category</div>
  <a href="dashboard.php" class="sb-item <?php echo ($activePage=='dashboard')?'active':''; ?>">
    <span class="si-left"><i class="bi bi-grid-fill"></i> Dashboard</span>
  </a>

  <hr class="sb-divider"/>

  <!-- Appearance -->
  <div class="sb-label">Appearance</div>

  <!-- Student Classes -->
  <button class="sb-item <?php echo ($activePage=='classes')?'active open':''; ?>"
          onclick="sbToggle('classMenu',this)">
    <span class="si-left"><i class="bi bi-journal-text"></i> Student Classes</span>
    <i class="bi bi-chevron-right si-arrow"></i>
  </button>
  <div class="sb-sub <?php echo ($activePage=='classes')?'open':''; ?>" id="classMenu">
    <a href="create-class.php"  class="<?php echo ($activePage=='create-class')?'active':''; ?>">Create Class</a>
    <a href="manage-classes.php" class="<?php echo ($activePage=='manage-classes')?'active':''; ?>">Manage Classes</a>
  </div>

  <!-- Subjects -->
  <button class="sb-item <?php echo ($activePage=='subjects')?'active open':''; ?>"
          onclick="sbToggle('subMenu',this)">
    <span class="si-left"><i class="bi bi-book"></i> Subjects</span>
    <i class="bi bi-chevron-right si-arrow"></i>
  </button>
  <div class="sb-sub <?php echo ($activePage=='subjects')?'open':''; ?>" id="subMenu">
    <a href="create-subject.php"          class="<?php echo ($activePage=='create-subject')?'active':''; ?>">Create Subject</a>
    <a href="manage-subjects.php"         class="<?php echo ($activePage=='manage-subjects')?'active':''; ?>">Manage Subjects</a>
    <a href="add-subjectcombination.php"  class="<?php echo ($activePage=='add-subjectcombination')?'active':''; ?>">Add Subject Combination</a>
    <a href="manage-subjectcombination.php" class="<?php echo ($activePage=='manage-subjectcombination')?'active':''; ?>">Manage Subject Combination</a>
  </div>

  <!-- Students -->
  <button class="sb-item <?php echo ($activePage=='students')?'active open':''; ?>"
          onclick="sbToggle('stuMenu',this)">
    <span class="si-left"><i class="bi bi-people"></i> Students</span>
    <i class="bi bi-chevron-right si-arrow"></i>
  </button>
  <div class="sb-sub <?php echo ($activePage=='students')?'open':''; ?>" id="stuMenu">
    <a href="add-students.php"    class="<?php echo ($activePage=='add-students')?'active':''; ?>">Add Students</a>
    <a href="manage-students.php" class="<?php echo ($activePage=='manage-students')?'active':''; ?>">Manage Students</a>
  </div>

  <!-- Results -->
  <button class="sb-item <?php echo ($activePage=='results')?'active open':''; ?>"
          onclick="sbToggle('resMenu',this)">
    <span class="si-left"><i class="bi bi-clipboard2-check"></i> Results</span>
    <i class="bi bi-chevron-right si-arrow"></i>
  </button>
  <div class="sb-sub <?php echo ($activePage=='results')?'open':''; ?>" id="resMenu">
    <a href="add-result.php"    class="<?php echo ($activePage=='add-result')?'active':''; ?>">Add Result</a>
    <a href="manage-results.php" class="<?php echo ($activePage=='manage-results')?'active':''; ?>">Manage Results</a>
  </div>

  <!-- Notices -->
  <button class="sb-item <?php echo ($activePage=='notices')?'active open':''; ?>"
          onclick="sbToggle('notMenu',this)">
    <span class="si-left"><i class="bi bi-megaphone"></i> Notices</span>
    <i class="bi bi-chevron-right si-arrow"></i>
  </button>
  <div class="sb-sub <?php echo ($activePage=='notices')?'open':''; ?>" id="notMenu">
    <a href="add-notice.php"    class="<?php echo ($activePage=='add-notice')?'active':''; ?>">Add Notice</a>
    <a href="manage-notices.php" class="<?php echo ($activePage=='manage-notices')?'active':''; ?>">Manage Notices</a>
  </div>

  <hr class="sb-divider"/>

  <a href="change-password.php" class="sb-item <?php echo ($activePage=='change-password')?'active':''; ?>">
    <span class="si-left"><i class="bi bi-key"></i> Change Password</span>
  </a>
  <a href="logout.php" class="sb-item">
    <span class="si-left"><i class="bi bi-box-arrow-right"></i> Logout</span>
  </a>

  <div style="height:20px"></div>
</div>
<!-- /.sidebar -->

<!-- ══ PAGE CONTENT STARTS HERE ══ -->
<div class="main-content">
