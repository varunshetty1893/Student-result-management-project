</div><!-- /.main-content -->

<footer class="admin-footer">
  &copy; 2024 UP-Result Management System &mdash; Developed by Team MVP
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function sbToggle(id, btn) {
    var sub   = document.getElementById(id);
    var isOpen = sub.classList.contains('open');
    document.querySelectorAll('.sb-sub').forEach(function(s){ s.classList.remove('open'); });
    document.querySelectorAll('.sb-item').forEach(function(b){ b.classList.remove('open'); });
    if (!isOpen) {
        sub.classList.add('open');
        btn.classList.add('open');
    }
}
function toggleSidebar() {
    document.getElementById('mainSidebar').classList.toggle('sb-open');
}
// Auto-open active submenu on page load
window.addEventListener('DOMContentLoaded', function() {
    var activeLink = document.querySelector('.sb-sub a.active');
    if (activeLink) {
        var sub = activeLink.closest('.sb-sub');
        var btn = sub ? sub.previousElementSibling : null;
        if (sub) sub.classList.add('open');
        if (btn) btn.classList.add('open');
    }
});
</script>
</body>
</html>
