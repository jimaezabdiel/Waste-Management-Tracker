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
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Profile</h1>

        <!-- Display User Profile Information -->
        <div class="card mb-4">
            <div class="card-body">
                <h3>Profile Information</h3>
                <form method="POST" action="profile.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <!-- Display User Waste Logs -->
        <div class="card">
            <div class="card-body">
                <h3>Your Garbage Records</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Waste Type</th>
                            <th>Amount (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userWasteLogs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['date']) ?></td>
                            <td><?= htmlspecialchars($log['waste_type']) ?></td>
                            <td><?= htmlspecialchars($log['amount']) ?> kg</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
