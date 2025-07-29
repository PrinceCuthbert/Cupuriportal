<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user_name'];
$userRole = $_SESSION['role'];

// Load users only if admin
$users = [];
if ($userRole === 'admin') {
    require_once 'db_connection.php';
    $stmt = $conn->prepare("SELECT id, full_name, email, gender, civil_status, created_at FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <link rel="stylesheet" href="dashboard.css" />
</head>
<body>
<div class="dashboard">
  <aside class="sidebar">
    <h2 class="logo">📘 MyPortal</h2>
    <nav>
      <ul>
        <li><a href="#">📊 Dashboard</a></li>
        <li><a href="resources.php">📁 My Resources</a></li>
        <li><a href="tasks.php">📄 My Tasks</a></li>
        <li><a href="profile.php">👤 My Profile</a></li>
        <li><a href="downloads.php">📥 Downloads</a></li>
        <li><a href="settings.php">⚙️ Settings</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
      </ul>
    </nav>
  </aside>

  <main class="main-content">
    <header class="topbar">
      <div class="greeting">
        <h1>Welcome back, <?= htmlspecialchars($userName) ?> 🎓</h1>
        <p class="subtitle"><?= htmlspecialchars($userRole) ?> Dashboard</p>
      </div>
      <div class="actions">
        <a href="downloads.php" class="btn">📂 Browse Exams</a>
        <?php if ($userRole === 'admin'): ?>
        <a href="upload.php" class="btn primary">⬆️ Upload</a>
        <?php endif; ?>
      </div>
    </header>

    <?php if ($userRole === 'admin'): ?>
<section class="admin-dashboard">
 <div class="controls">
  <input type="text" id="searchInput" placeholder="🔍 Search users..." onkeyup="filterUsers()">
  <select id="filterGender" onchange="filterUsers()">
    <option value="">All Genders</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
  </select>
  <select id="filterCivil" onchange="filterUsers()">
    <option value="">All Civil Status</option>
    <option value="Single">Single</option>
    <option value="Married">Married</option>
    <option value="Widow">Widow</option>
    <option value="Divorced">Divorced</option>
  </select>
  <button onclick="exportToCSV()">📤 Export CSV</button>
  <span id="userCount">👥 Total: 0</span>
</div>

  <table id="usersTable" class="dashboard-table" data-sort-order="asc">
    <thead>
      <tr>
        <th onclick="sortTable(0)">Name</th>
        <th onclick="sortTable(1)">Email</th>
        <th onclick="sortTable(2)">Gender</th>
        <th onclick="sortTable(3)">Civil Status</th>
        <th onclick="sortTable(4)">Created At ⬍</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['full_name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['gender']) ?></td>
        <td><?= htmlspecialchars($user['civil_status']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div id="paginationControls" class="pagination">
    <button onclick="changePage(-1)">⬅ Prev</button>
    <span id="currentPage">Page 1</span>
    <button onclick="changePage(1)">Next ➡</button>
  </div>
</section>
<?php endif; ?>

    <!-- Stats Cards -->
    <section class="cards">
      <div class="card"><div class="stat"><div class="icon green">📘</div><div><h3>Active Courses</h3><p>4</p></div></div></div>
      <div class="card"><div class="stat"><div class="icon blue">📑</div><div><h3>Pending Tasks</h3><p>2</p></div></div></div>
      <div class="card"><div class="stat"><div class="icon purple">📬</div><div><h3>Messages</h3><p>5 unread</p></div></div></div>
      <div class="card"><div class="stat"><div class="icon orange">📈</div><div><h3>Performance</h3><p>85% Avg</p></div></div></div>
    </section>

    <section class="quick-actions">
      <h2>Quick Actions</h2>
      <div class="card-grid">
        <a href="#" class="quick-card violet"><div class="icon-box">📂</div><div><h3>Browse Resources</h3><p>Search exam archives</p></div></a>
        <a href="#" class="quick-card cyan"><div class="icon-box">⬆️</div><div><h3>Upload File</h3><p>Submit new exam docs</p></div></a>
        <a href="#" class="quick-card pink"><div class="icon-box">📊</div><div><h3>View Stats</h3><p>Track usage and performance</p></div></a>
      </div>
    </section>
  </main>
</div>

<script>
function filterUsers() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const gender = document.getElementById("filterGender").value.trim().toLowerCase();
  const civil = document.getElementById("filterCivil").value.trim().toLowerCase();
  const rows = document.querySelectorAll("#usersTable tbody tr");
  let visibleCount = 0;

  rows.forEach(row => {
    const name = row.cells[0].textContent.toLowerCase();
    const email = row.cells[1].textContent.toLowerCase();
    const rowGender = row.cells[2].textContent.trim().toLowerCase();
    const rowCivil = row.cells[3].textContent.trim().toLowerCase();

    const match = (
      name.includes(search) ||
      email.includes(search) ||
      rowGender.includes(search) ||
      rowCivil.includes(search)
    ) &&
    (gender === "" || rowGender === gender) &&
    (civil === "" || rowCivil === civil);

    row.dataset.visible = match ? "true" : "false";
    if (match) visibleCount++;
  });

  document.getElementById("userCount").textContent = `👥 Total: ${visibleCount}`;
  showPage(1);
}

const genderOrder = ["male", "female"];
const civilOrder = ["single", "married"];

function sortTable(colIndex) {
  const table = document.getElementById("usersTable");
  const tbody = table.querySelector("tbody");
  const rows = Array.from(tbody.querySelectorAll("tr"));
  let currentOrder = table.dataset.sortOrder || "asc";
  const asc = currentOrder === "asc";
  table.dataset.sortOrder = asc ? "desc" : "asc";

  rows.sort((a, b) => {
    let valA = a.cells[colIndex].textContent.trim().toLowerCase();
    let valB = b.cells[colIndex].textContent.trim().toLowerCase();

    if (colIndex === 2) {
      valA = genderOrder.indexOf(valA);
      valB = genderOrder.indexOf(valB);
      if (valA === -1) valA = 99;
      if (valB === -1) valB = 99;
      return asc ? valA - valB : valB - valA;
    }

    if (colIndex === 3) {
      valA = civilOrder.indexOf(valA);
      valB = civilOrder.indexOf(valB);
      if (valA === -1) valA = 99;
      if (valB === -1) valB = 99;
      return asc ? valA - valB : valB - valA;
    }

    if (colIndex === 4) {
      const dateA = Date.parse(valA);
      const dateB = Date.parse(valB);
      if (!isNaN(dateA) && !isNaN(dateB)) {
        return asc ? dateA - dateB : dateB - dateA;
      }
    }

    return asc ? valA.localeCompare(valB) : valB.localeCompare(valA);
  });

  rows.forEach(row => tbody.appendChild(row));
}

function exportToCSV() {
  const rows = document.querySelectorAll("#usersTable tr");
  let csv = Array.from(rows).map(row =>
    Array.from(row.cells).slice(0, 5).map(cell => {
      const text = cell.textContent.replace(/"/g, '""');
      return `"${text}"`;
    }).join(",")
  ).join("\n");

  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "users_export.csv";
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

let currentPage = 1;
const rowsPerPage = 3;

function showPage(page) {
  const rows = Array.from(document.querySelectorAll("#usersTable tbody tr"));
  const matchedRows = rows.filter(row => row.dataset.visible !== "false");

  const totalPages = Math.ceil(matchedRows.length / rowsPerPage);
  if (page < 1) page = 1;
  if (page > totalPages) page = totalPages;

  currentPage = page;
  const start = (page - 1) * rowsPerPage;
  const end = start + rowsPerPage;

  rows.forEach(row => row.style.display = "none");
  matchedRows.forEach((row, index) => {
    if (index >= start && index < end) {
      row.style.display = "";
    }
  });

  document.getElementById("currentPage").textContent = `Page ${currentPage} of ${totalPages}`;
}

function changePage(delta) {
  showPage(currentPage + delta);
}

window.addEventListener("DOMContentLoaded", () => {
  filterUsers(); // Will update count and show page
});
</script>
</body>
</html>
