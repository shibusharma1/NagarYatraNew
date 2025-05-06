</div>
<!-- Footer Section -->
<div class="footer-content">
  <div> <span id="year"></span> &copy; Copyright <strong>NagarYatra</strong></div>
  <div>Designed and Developed by <strong>Himalaya Darshan College</strong></div>
</div>

</body>
<script>
  document.getElementById("year").textContent = new Date().getFullYear();
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const profileTrigger = document.getElementById("profileTrigger");
    const userMenu = document.getElementById("user-menu");

    document.addEventListener("click", function (e) {
      if (profileTrigger.contains(e.target)) {
        // Toggle dropdown
        userMenu.style.display = userMenu.style.display === "block" ? "none" : "block";
      } else if (!userMenu.contains(e.target)) {
        // Clicked outside the menu
        userMenu.style.display = "none";
      }
    });
  });
</script>

</html>