<?php
session_start();
include('config.php'); // DB connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch all necessary fields
$result = mysqli_query($conn, "SELECT id, title, file_path, lecture_name,subject, uploaded_at FROM downloads");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Downloads - Cupuri Portal</title>
  <style>
  body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background: #f7f8fa;
  }

  .navbar {
    background-color: #003366;
    color: white;
    padding: 1rem 2rem;
  }

  .navbar h1 {
    margin: 0;
    font-size: 1.5rem;
  }

  .container {
    max-width: 1000px;
    margin: 40px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  }

  h2 {
    color: #003366;
    margin-bottom: 20px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
  }

  th, td {
    padding: 14px 16px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }

  th {
    background-color: #003366;
    color: white;
    font-weight: 600;
  }

  tr:hover {
    background-color: #f1f1f1;
  }

  .download-btn {
    background-color: #003366;
    color: white;
    padding: 6px 14px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background 0.3s ease;
    display: inline-block;
  }

  .download-btn:hover {
    background-color: #002244;
  }

  .back-link {
    display: inline-block;
    margin-top: 24px;
    text-decoration: none;
    color: #003366;
    font-weight: bold;
    font-size: 0.95rem;
  }

  .back-link:hover {
    text-decoration: underline;
    color: #001a33;
  }
</style>

</head>
<body>
  
  <div class="navbar">
    <h1>📥 Downloads</h1>
  </div>

  <div class="container">
    <h2>Available Past Papers</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Subject</th>
            <th>Lecture Name</th>
            <th>Download</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= htmlspecialchars($row['subject']) ?></td>
              <td><?= htmlspecialchars($row['lecture_name']) ?></td>
              <td>
                <a class="download-btn" href="<?= htmlspecialchars($row['file_path']) ?>" download>Download</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No papers available for download at the moment.</p>
    <?php endif; ?>

    <a class="back-link" href="dashboard.php">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
