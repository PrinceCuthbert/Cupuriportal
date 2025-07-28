<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user_name'];
$userRole = $_SESSION['role']; // ✅ get the role
?>


<!-- http://localhost/login_system/dashboard.php -->

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
  <!-- Sidebar -->
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

  <!-- Main Content -->
  <main class="main-content">
    <!-- Top Bar -->
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

    <!-- Stats Cards -->
    <section class="cards">
      <div class="card">
        <div class="stat">
          <div class="icon green">📘</div>
          <div>
            <h3>Active Courses</h3>
            <p>4</p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="stat">
          <div class="icon blue">📑</div>
          <div>
            <h3>Pending Tasks</h3>
            <p>2</p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="stat">
          <div class="icon purple">📬</div>
          <div>
            <h3>Messages</h3>
            <p>5 unread</p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="stat">
          <div class="icon orange">📈</div>
          <div>
            <h3>Performance</h3>
            <p>85% Avg</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Quick Actions -->
    <section class="quick-actions">
      <h2>Quick Actions</h2>
      <div class="card-grid">
        <a href="#" class="quick-card violet">
          <div class="icon-box">📂</div>
          <div>
            <h3>Browse Resources</h3>
            <p>Search exam archives</p>
          </div>
        </a>
        <a href="#" class="quick-card cyan">
          <div class="icon-box">⬆️</div>
          <div>
            <h3>Upload File</h3>
            <p>Submit new exam docs</p>
          </div>
        </a>
        <a href="#" class="quick-card pink">
          <div class="icon-box">📊</div>
          <div>
            <h3>View Stats</h3>
            <p>Track usage and performance</p>
          </div>
        </a>
      </div>
    </section>
  </main>
</div>
</body>
</html>
