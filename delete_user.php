<?php
// delete_user.php

// Step 1: Connect to your database
$conn = new mysqli("localhost", "root", "", "cupuri_portal");

// Step 2: Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 3: Get the user ID from the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Step 4: Delete the user
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo "✅ User deleted successfully.";
    } else {
        echo "❌ Error deleting user: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "⚠️ No user ID provided in URL.";
}

$conn->close();
?>
