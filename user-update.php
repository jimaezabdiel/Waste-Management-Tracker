<?php
// Start the session to access user information
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Fetch user details using prepared statements
$stmt = $conn->prepare("SELECT username, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Initialize a success flag
$isUpdated = false;

// Update profile details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFullName = $_POST['full-name'];
    $newEmail = $_POST['email'];
    $newPhone = $_POST['phone'];
    $newAddress = $_POST['address'];
    $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Update user profile with prepared statements
    if ($newPassword) {
        $updateQuery = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ?, password = ? WHERE id = ?");
        $updateQuery->bind_param("sssssi", $newFullName, $newEmail, $newPhone, $newAddress, $newPassword, $userId);
    } else {
        $updateQuery = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        $updateQuery->bind_param("ssssi", $newFullName, $newEmail, $newPhone, $newAddress, $userId);
    }

    if ($updateQuery->execute()) {
        $_SESSION['username'] = $newFullName; 
        $isUpdated = true;
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
    <title>User Profile Update</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <nav class="header-links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    <main>
        <section class="form-section">
            <div class="user-profile-update-form box overview-content">
                <h1>User Profile Update</h1>
                <form action="" method="post">
                    <div class="field">
                        <label for="full-name">Full Name:</label>
                        <input type="text" id="full-name" name="full-name" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="field">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="field">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>

                    <div class="field">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
                    </div>

                    <div class="field">
                        <label for="password">Change Password (Optional):</label>
                        <input type="password" id="password" name="password">
                    </div>

                    <button type="submit" name="submit_report" class="btn">Update Profile</button>
                </form>
            </div>
        </section>
    </main>
    
    <footer>
        <div>
            <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
        </div>
    </footer>

    <?php if ($isUpdated): ?>
    <script>
        Swal.fire({
            title: 'Profile Updated!',
            text: 'Your profile has been successfully updated.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Go to Home',
            cancelButtonText: 'Stay on Profile'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'home.php';
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
