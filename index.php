<?php
require_once 'includes/db.php';


// Notices
$notices = array();
$res = mysqli_query($conn, "SELECT * FROM tblnotice ORDER BY postingDate DESC LIMIT 5");
while ($row = mysqli_fetch_assoc($res)) { $notices[] = $row; }

// Classes for dropdown
$classes = array();
$res2 = mysqli_query($conn, "SELECT * FROM tblclasses ORDER BY ClassNameNumeric");
while ($row = mysqli_fetch_assoc($res2)) { $classes[] = $row; }

$pageTitle = 'Home';
require_once 'includes/header.php';
?>

<!-- HERO BANNER -->
<section class="hero-banner">
  <div class="hero-overlay"></div>
  <div class="hero-content container">
    <h1>Student Result Management System</h1>
    <p>Get the most of reduction in your team's operating creates amazing UI/UX experiences.</p>
    <a href="#result-lookup" class="btn-learn">Learn More</a>
  </div>
  <div class="hero-dots">
    <span class="dot active"></span>
    <span class="dot"></span>
    <span class="dot"></span>
  </div>
</section>

<!-- NOTICE BOARD -->
<section class="notice-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h4 class="notice-title">Notice Board</h4>
        <?php if (!empty($notices)): ?>
        <ul class="notice-list">
          <?php foreach ($notices as $notice): ?>
          <li>
            <a href="notice-details.php?nid=<?php echo (int)$notice['id']; ?>">
              <?php echo htmlspecialchars($notice['noticeTitle']); ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p style="font-size:13px;color:#888">No notices posted yet.</p>
        <?php endif; ?>
      </div>
      <div class="col-md-5 offset-md-1 mt-4 mt-md-0">
        <img src="images/about.jpg"
             onerror="this.src='https://images.unsplash.com/photo-1562774053-701939374585?w=700&q=80'"
             alt="Institute" style="width:100%;border-radius:4px"/>
      </div>
    </div>
  </div>
</section>

<!-- COURSES -->
<section class="courses-section">
  <div class="container">
    <h4 class="courses-title">Courses offered by Institute</h4>
    <div class="row g-3">
      <?php
      $courses = array(
        array('Work Expertise &amp; Leadership', 'bi-display',        'images/s1.jpg', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=500&q=80'),
        array('Digital Product Development',     'bi-gear',           'images/s2.jpg', 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=500&q=80'),
        array('Business Software Development',   'bi-graph-up-arrow', 'images/s3.jpg', 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=500&q=80'),
      );
      foreach ($courses as $c):
        $ctitle=$c[0]; $cicon=$c[1]; $cimg=$c[2]; $cfb=$c[3];
      ?>
      <div class="col-md-4">
        <div class="course-card">
          <img src="<?php echo $cimg; ?>"
               onerror="this.src='<?php echo $cfb; ?>'"
               alt="<?php echo strip_tags($ctitle); ?>"/>
          <div class="course-body">
            <i class="bi <?php echo $cicon; ?> course-icon"></i>
            <span class="course-title"><?php echo $ctitle; ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- JOIN US -->
<section class="join-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-7">
        <h3 class="join-title">Start improving your<br>knowledge today! Join Us</h3>
        <p class="join-desc">"Start enhancing your skills today! Join us and explore a world of opportunities in learning and growth. Unlock your potential with expert guidance and hands-on experience."</p>
      </div>
      <div class="col-md-5 text-md-end mt-4 mt-md-0">
        <a href="#result-lookup"  class="btn-outline-dark-custom">Learn More</a>
        <a href="admin-login.php" class="btn-red">Contact us</a>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features-section">
  <div class="container">
    <h4 class="features-title">To design and deliver the<br>innovative products.</h4>
    <div class="row g-0 feat-row">
      <div class="col-md-4">
        <div class="feat-card feat-plain">
          <i class="bi bi-gear-fill feat-ico"></i>
          <div class="feat-name">Augmented Reality</div>
          <div class="feat-desc">"Our project drives innovation, delivering practical solutions for real-world needs."</div>
          <a href="#" class="feat-more">Read More</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feat-card feat-hover-img"
             style="background-image:url('images/s4.jpg');"
             data-fallback="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=500&q=80">
          <div class="feat-img-overlay"></div>
          <div class="feat-card-inner">
            <i class="bi bi-wrench-adjustable feat-ico"></i>
            <div class="feat-name">Deep Expertise</div>
            <div class="feat-desc">"Our project harnesses deep expertise to create impactful, real-world solutions."</div>
            <a href="#" class="feat-more">Read More</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feat-card feat-plain">
          <i class="bi bi-flask feat-ico"></i>
          <div class="feat-name">Software development</div>
          <div class="feat-desc">"Our project leverages software development to craft real-world solutions."</div>
          <a href="#" class="feat-more">Read More</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SPONSOR BAR -->
<div class="sponsor-bar">
  <div class="container d-flex justify-content-center align-items-center flex-wrap gap-4">
    <img src="images/logo2.png" alt="Strides"  style="height:70px" onerror="this.style.display='none'"/>
    <img src="images/logo3.png" alt="Leo Lion" style="height:70px" onerror="this.style.display='none'"/>
    <img src="images/logo4.png" alt="Logo"     style="height:70px" onerror="this.style.display='none'"/>
    <img src="images/logo2.png" alt="Strides"  style="height:70px" onerror="this.style.display='none'"/>
    <img src="images/logo3.png" alt="Leo Lion" style="height:70px" onerror="this.style.display='none'"/>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<style>
.hero-banner { position:relative; min-height:420px; display:flex; flex-direction:column; align-items:center; justify-content:center; background:url('images/banner1.jpg') center center/cover no-repeat; text-align:center; overflow:hidden; }
.hero-banner::before { content:''; position:absolute; inset:0; background:rgba(0,0,0,0.45); }
.hero-content { position:relative; z-index:1; padding:60px 20px 40px; }
.hero-content h1 { font-size:2.4rem; font-weight:900; color:#fff; margin-bottom:14px; }
.hero-content p  { font-size:15px; color:#ddd; margin-bottom:28px; }
.btn-learn { background:#c0392b; color:#fff; border:none; padding:12px 36px; font-size:15px; font-weight:700; border-radius:4px; display:inline-block; }
.btn-learn:hover { background:#a93226; color:#fff; }
.hero-dots { position:absolute; bottom:18px; left:50%; transform:translateX(-50%); z-index:1; display:flex; gap:8px; }
.dot { width:10px; height:10px; border-radius:50%; background:rgba(255,255,255,.4); display:inline-block; }
.dot.active { background:#fff; }
.notice-section { padding:60px 0; background:#fff; }
.notice-title { font-weight:900; font-size:1.4rem; color:#1a1a5e; margin-bottom:20px; }
.notice-list { list-style:disc; padding-left:20px; }
.notice-list li { margin-bottom:10px; }
.notice-list li a { color:#333; font-size:14px; }
.notice-list li a:hover { color:#c0392b; text-decoration:underline; }
.courses-section { background:#eaf0f6; padding:60px 0; }
.courses-title { text-align:center; font-weight:900; font-size:1.4rem; color:#1a1a5e; margin-bottom:32px; }
.course-card { background:#fff; border-radius:4px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
.course-card img { width:100%; height:200px; object-fit:cover; display:block; }
.course-body { padding:16px 18px; display:flex; align-items:center; gap:12px; }
.course-icon { font-size:1.3rem; color:#1a1a5e; flex-shrink:0; }
.course-title { font-size:14px; font-weight:700; color:#1a1a5e; }
.join-section { padding:60px 0; background:#fff; }
.join-title { font-weight:900; font-size:1.5rem; color:#1a1a5e; line-height:1.3; }
.join-desc { font-size:13px; color:#555; margin-top:14px; line-height:1.7; }
.btn-outline-dark-custom { border:2px solid #1a1a5e; color:#1a1a5e; background:#fff; padding:9px 24px; font-size:13px; font-weight:700; border-radius:4px; display:inline-block; margin-right:10px; }
.btn-outline-dark-custom:hover { background:#1a1a5e; color:#fff; }
.btn-red { background:#c0392b; color:#fff; border:none; padding:10px 24px; font-size:13px; font-weight:700; border-radius:4px; display:inline-block; }
.btn-red:hover { background:#a93226; color:#fff; }
.features-section { padding:70px 0; background:#fff; }
.features-title { text-align:center; font-weight:900; font-size:1.4rem; color:#1a1a5e; margin-bottom:36px; line-height:1.4; }
.feat-row { border:1px solid #e8e8e8; border-radius:4px; overflow:hidden; }
.feat-card { padding:36px 28px; height:100%; min-height:300px; display:flex; flex-direction:column; border-right:1px solid #e8e8e8; }
.feat-card:last-child { border-right:none; }
.feat-plain { background:#fff; }
.feat-ico { font-size:1.8rem; color:#c0392b; margin-bottom:16px; display:block; }
.feat-name { font-size:1.05rem; font-weight:800; color:#1a1a5e; margin-bottom:12px; }
.feat-desc { font-size:13px; color:#555; line-height:1.65; flex:1; }
.feat-more { font-size:13px; color:#1a1a5e; font-weight:600; margin-top:16px; display:inline-block; }
.feat-more:hover { color:#c0392b; text-decoration:underline; }
.feat-hover-img { position:relative; background-size:cover !important; background-position:center !important; background-color:#1a1a5e; }
.feat-img-overlay { position:absolute; inset:0; background:rgba(0,0,0,0.55); transition:background .3s; }
.feat-hover-img .feat-card-inner { position:relative; z-index:1; padding:36px 28px; display:flex; flex-direction:column; min-height:300px; }
.feat-hover-img .feat-ico { color:#fff; }
.feat-hover-img .feat-name { color:#fff; }
.feat-hover-img .feat-desc { color:#ddd; }
.feat-hover-img .feat-more { color:#fff; }
.feat-hover-img:hover .feat-img-overlay { background:rgba(0,0,0,0.25); }
.sponsor-bar { background:#c0392b; padding:32px 0; }
@media(max-width:767px){ .hero-content h1{font-size:1.6rem;} .feat-card{border-right:none;border-bottom:1px solid #e8e8e8;} }
</style>
