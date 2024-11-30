<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Fetch user details
$user = $conn->query("SELECT username, email FROM users WHERE id = $userId")->fetch_assoc();

// Fetch user's waste activity
$userWasteLogs = $conn->query("
    SELECT waste_type, amount, date 
    FROM waste_logs 
    WHERE user_id = $userId
    ORDER BY date DESC
")->fetch_all(MYSQLI_ASSOC);

// Update profile details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];

    // Update password if provided
    if (!empty($_POST['password'])) {
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updateQuery = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $updateQuery->bind_param("sssi", $newUsername, $newEmail, $newPassword, $userId);
    } else {
        $updateQuery = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $updateQuery->bind_param("ssi", $newUsername, $newEmail, $userId);
    }

    if ($updateQuery->execute()) {
        $_SESSION['username'] = $newUsername; // Update session username
        $user['username'] = $newUsername;
        $user['email'] = $newEmail;
        echo "<script>alert('Profile updated successfully!');</script>";
    } else {
        echo "<script>alert('Failed to update profile.');</script>";
    }
}
?>
