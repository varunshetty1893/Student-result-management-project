<!-- FOOTER -->
<footer class="main-footer">
  <div class="container">
    <div class="row g-4">

      <!-- Brand col -->
      <div class="col-md-4">
        <div class="footer-brand-center">
          <div class="fb-name">UP-RESULT</div>
          <div class="fb-sub">Developed by Team MVP</div>
        </div>
        <p class="footer-desc">
          "Team MVP is dedicated to PHP and DBMS. Our projects are focused on
          learning and development, and we aim to enhance educational experiences
          through these technologies."
        </p>
        <div class="footer-social mt-3">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-twitter"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <!-- Navigation -->
      <div class="col-md-2 offset-md-1">
        <div class="footer-heading">Navigation</div>
        <ul class="footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Blog posts</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="#">Contact us</a></li>
          <li><a href="#">Components</a></li>
        </ul>
      </div>

      <!-- Resources -->
      <div class="col-md-2">
        <div class="footer-heading">Resources</div>
        <ul class="footer-links">
          <li><a href="#">Marketing Plans</a></li>
          <li><a href="#">Digital Services</a></li>
          <li><a href="#">Interior Design</a></li>
          <li><a href="#">Product Selling</a></li>
          <li><a href="#">Digital Services</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="col-md-3">
        <div class="footer-heading">Contact Info</div>
        <div class="footer-contact">
          <div style="margin-bottom:8px">
            Address : Team MVP Freelancer, KU University, Shankargatta,
            bhadravthi taluk, shivamogga district, karnataka-577129.
          </div>
          <div style="margin-bottom:4px">Phone : (+91)9090909090</div>
          <div style="margin-bottom:4px">Email : info@result.com</div>
          <div>Support : info@result.com</div>
        </div>
      </div>

    </div><!-- /.row -->

    <!-- Copyright -->
    <div class="footer-copy">
      Copyright &copy; 2024 Student Result Management System 
    </div>

  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* Fallback for hover-image cards if local image missing */
(function() {
  var cards = document.querySelectorAll('.feat-hover-img');
  for (var i = 0; i < cards.length; i++) {
    var card = cards[i];
    var bg   = card.style.backgroundImage;
    if (!bg || bg === 'none' || bg === 'url("")') {
      var fb = card.getAttribute('data-fallback');
      if (fb) { card.style.backgroundImage = "url('" + fb + "')"; }
    }
  }
})();
</script>

</body>
</html>
