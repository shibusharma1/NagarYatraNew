<?php
$title = "NagarYatra | Mechanics Contacts";
$current_page = "mechanicscontacts";
include_once 'master_header.php';
?>

<div class="container my-4">
  <div class="header mb-3 text-center">
    <h1><i class="fa-solid fa-screwdriver-wrench"></i> Mechanics in Nepal</h1>
    <p id="nearbyNotice" class="text-muted"></p>
  </div>

  <!-- Filter Options -->
  <div class="row mb-3">
    <div class="col-md-6">
      <label for="districtFilter" class="form-label">Filter by District</label>
      <select id="districtFilter" class="form-select">
        <option value="">All Districts</option>
      </select>
    </div>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-bordered table-striped text-center align-middle" id="mechanicsTable">
        <thead class="table-primary">
          <tr>
            <th>SN</th>
            <th>Name</th>
            <th>Province</th>
            <th>District</th>
            <th>Ward</th>
            <th>Contact</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="pagination justify-content-center mt-3" id="pagination"></div>
  </div>
</div>

<?php include_once 'master_footer.php'; ?>

<script>
  let mechanics = [];
  let filteredMechanics = [];
  let currentPage = 1;
  const itemsPerPage = 10;
  const paginationRange = 3;

  const tableBody = document.querySelector("#mechanicsTable tbody");
  const paginationContainer = document.getElementById("pagination");
  const districtSelect = document.getElementById("districtFilter");

  function populateDistricts(data) {
    const districts = [...new Set(data.map(m => m.district))].sort();
    districts.forEach(d => {
      const opt = document.createElement("option");
      opt.value = d;
      opt.textContent = d;
      districtSelect.appendChild(opt);
    });
  }

  function filterMechanics() {
    const selectedDistrict = districtSelect.value;
    let results = [...mechanics];

    if (selectedDistrict) {
      results = results.filter(m => m.district === selectedDistrict);
    }

    results.sort((a, b) => a.district.localeCompare(b.district));
    filteredMechanics = results;
    currentPage = 1;
    renderTable();
  }

  function renderTable() {
    tableBody.innerHTML = "";
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageItems = filteredMechanics.slice(start, end);

    pageItems.forEach((mech, index) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${start + index + 1}</td>
        <td><i class="fa-solid fa-wrench fa-icon me-2"></i>${mech.name}</td>
        <td>${mech.province}</td>
        <td>${mech.district}</td>
        <td>${mech.ward}</td>
        <td><a href="tel:${mech.contact}">${mech.contact}</a></td>
      `;
      tableBody.appendChild(row);
    });

    renderPagination();
  }

  function renderPagination() {
    paginationContainer.innerHTML = "";
    const totalPages = Math.ceil(filteredMechanics.length / itemsPerPage);

    const createButton = (label, page) => {
      const btn = document.createElement("button");
      btn.className = `btn btn-sm mx-1 ${page === currentPage ? "btn-primary" : "btn-outline-primary"}`;
      btn.textContent = label;
      btn.onclick = () => {
        currentPage = page;
        renderTable();
      };
      return btn;
    };

    if (currentPage > 1) {
      paginationContainer.appendChild(createButton("Previous", currentPage - 1));
    }

    const startPage = Math.max(1, currentPage - Math.floor(paginationRange / 2));
    const endPage = Math.min(totalPages, startPage + paginationRange - 1);

    for (let i = startPage; i <= endPage; i++) {
      paginationContainer.appendChild(createButton(i, i));
    }

    if (currentPage < totalPages) {
      paginationContainer.appendChild(createButton("Next", currentPage + 1));
    }
  }

  function initMechanics(data) {
    mechanics = data;
    populateDistricts(data);
    filterMechanics();
  }

  function fetchMechanicsData() {
    fetch("mechanicscontacts.json")
      .then(res => res.json())
      .then(data => {
        initMechanics(data);
        document.getElementById("nearbyNotice").innerText = "Sorted by district name.";
      })
      .catch(err => {
        document.getElementById("nearbyNotice").innerText = "Failed to load mechanics data.";
        console.error("Error loading JSON:", err);
      });
  }

  districtSelect.addEventListener("change", filterMechanics);

  window.onload = fetchMechanicsData;
</script>
