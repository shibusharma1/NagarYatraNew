<?php
$title = "NagarYatra | Emergency Contacts";
$current_page = "emergencycontacts";
include_once 'master_header.php';
?>
<div class="container">
  <div class="header">
    <h1><i class="fas fa-phone-volume"></i> Emergency Contacts in Nepal</h1>
  </div>

  <div class="search-box">
    <input type="search" id="searchInput" class="form-control form-control-lg" placeholder="Search for Police, Ambulance, Fire, etc.">
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-striped align-middle text-center" id="contactsTable">
        <thead>
          <tr>
            <th style="color: white;"><i class="fas fa-building-shield"></i> Service</th>
            <th style="color: white;"><i class="fas fa-phone"></i> Contact</th>
            <th style="color: white;"><i class="fas fa-map-marker-alt"></i> Location</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="contact-name"><i class="fa-solid fa-shield-halved fa-icon"></i>Police</td>
            <td><a href="tel:+977100">100</a></td>
            <td>Nationwide</td>
          </tr>
          <tr>
            <td class="contact-name"><i class="fa-solid fa-truck-medical fa-icon"></i>Ambulance</td>
            <td><a href="tel:+977102">102</a></td>
            <td>Nationwide</td>
          </tr>
          <tr>
            <td class="contact-name"><i class="fa-solid fa-fire-extinguisher fa-icon"></i>Fire Service</td>
            <td><a href="tel:+977101">101</a></td>
            <td>Nationwide</td>
          </tr>
          <tr>
            <td class="contact-name"><i class="fa-solid fa-venus-mars fa-icon"></i>Women & Children Help Desk</td>
            <td><a href="tel:+977114">114</a></td>
            <td>Nationwide</td>
          </tr>
          <tr>
            <td class="contact-name"><i class="fa-solid fa-car-on fa-icon"></i>Traffic Police</td>
            <td><a href="tel:+977115">115</a></td>
            <td>Kathmandu</td>
          </tr>
          <tr>
            <td class="contact-name"><i class="fa-solid fa-bolt-lightning fa-icon"></i>Electricity Emergency</td>
            <td><a href="tel:+977154">154</a></td>
            <td>Nationwide</td>
          </tr>
          <tr>
            <td class="contact-name"><i class="fa-solid fa-gas-pump fa-icon"></i>Gas Leak Emergency</td>
            <td><a href="tel:+977154">154</a></td>
            <td>Nationwide</td>
          </tr>
          <tr>
            <td class="contact-name"><i class="fa-solid fa-mountain fa-icon"></i>Search & Rescue</td>
            <td><a href="tel:+977112">112</a></td>
            <td>Nationwide</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#contactsTable tbody tr');
    rows.forEach(row => {
      let text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? '' : 'none';
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php
include_once 'master_footer.php';
?>
