<?php
require_once 'includes/db.php';


$nid    = isset($_GET['nid']) ? (int)$_GET['nid'] : 0;
$notice = null;

if ($nid) {
    $res    = mysqli_query($conn, "SELECT * FROM tblnotice WHERE id=$nid LIMIT 1");
    $notice = mysqli_fetch_assoc($res);
}
if (!$notice) { header("Location: index.php"); exit(); }

$others = array();
$res2   = mysqli_query($conn, "SELECT id, noticeTitle, postingDate FROM tblnotice ORDER BY postingDate DESC LIMIT 5");
while ($row = mysqli_fetch_assoc($res2)) { $others[] = $row; }

$pageTitle = $notice['noticeTitle'];
require_once 'includes/header.php';
?>

<div style="background:#f4f6f9;border-bottom:1px solid #e0e0e0;padding:14px 0">
  <div class="container">
    <ol class="breadcrumb mb-0" style="font-size:13px">
      <li class="breadcrumb-item"><a href="index.php" style="color:#c0392b">Home</a></li>
      <li class="breadcrumb-item active">Notice Details</li>
    </ol>
  </div>
</div>

<section style="padding:40px 0;background:#f4f6f9;min-height:60vh">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-8">
        <div style="background:#fff;border:1px solid #ddd;border-radius:6px;padding:28px">
          <h4 style="font-size:1.2rem;font-weight:800;color:#1a1a5e;margin-bottom:8px">
            <?php echo htmlspecialchars($notice['noticeTitle']); ?>
          </h4>
          <div style="font-size:12px;color:#888;margin-bottom:18px">
            <i class="bi bi-calendar3 me-1"></i>
            <?php echo date('d M Y, h:i A', strtotime($notice['postingDate'])); ?>
          </div>
          <hr style="border-color:#eee"/>
          <div style="font-size:14px;color:#444;line-height:1.8">
            <?php echo nl2br(htmlspecialchars($notice['noticeDetails'])); ?>
          </div>
          <div style="margin-top:24px">
            <a href="index.php" style="background:#1a2340;color:#fff;padding:9px 20px;font-size:13px;font-weight:600;border-radius:3px;text-decoration:none;display:inline-block">
              <i class="bi bi-arrow-left me-1"></i>Back to Home
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div style="background:#fff;border:1px solid #ddd;border-radius:6px;overflow:hidden">
          <div style="background:#1a2340;color:#fff;padding:12px 16px;font-size:14px;font-weight:700">
            <i class="bi bi-bell-fill me-2"></i>Other Notices
          </div>
          <?php foreach ($others as $o): ?>
          <a href="notice-details.php?nid=<?php echo (int)$o['id']; ?>"
             style="display:block;padding:12px 16px;border-bottom:1px solid #f0f0f0;text-decoration:none;background:<?php echo ($o['id']==$nid)?'#fef5f5':'#fff'; ?>">
            <div style="font-size:13px;font-weight:600;color:<?php echo ($o['id']==$nid)?'#c0392b':'#1a2340'; ?>">
              <?php echo htmlspecialchars($o['noticeTitle']); ?>
            </div>
            <div style="font-size:11px;color:#888;margin-top:3px">
              <i class="bi bi-calendar3 me-1"></i><?php echo date('d M Y', strtotime($o['postingDate'])); ?>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
