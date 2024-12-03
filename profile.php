<?php
// Start the session to retrieve the logged-in user
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

// Fetch user’s waste logs
$stmt = $conn->prepare("SELECT waste_type, quantity, date_of_collection FROM waste_collection_reports WHERE user_id = ? ORDER BY date_of_collection DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userWasteLogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch user's Reuse Hub items
$stmt = $conn->prepare("SELECT title, description, category FROM reuse_items WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$reuseHubItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch incident reports for table
$stmt = $conn->prepare("SELECT incident_type, COUNT(*) AS report_count FROM incident_reports WHERE user_id = ? GROUP BY incident_type ORDER BY incident_type");
$stmt->bind_param("i", $userId);
$stmt->execute();
$incidentReports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile and Waste Report</title>
    <link rel="stylesheet" href="profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <div class="header-links">
            <a href="home.php">Home</a>
            <a href="user-update.php">Update Profile</a>
            <a href="carbon-footprint.php">Carbon Footprint Summary</a>
            <a href="waste-collection.php">Waste Collection Form</a>
            <a href="incident-report.php">Incident Report Form</a> 
        </div>
    </header>
  
    <main>
        <h1>User Profile</h1>
        <div class="details">
            <h2>Your Information</h2>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
        </div>

        <div class="details">
            <h2>Your Waste Logs</h2>
            <table>
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
                        <td><?= htmlspecialchars($log['date_of_collection']) ?></td>
                        <td><?= htmlspecialchars($log['waste_type']) ?></td>
                        <td><?= htmlspecialchars($log['quantity']) ?> kg</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="details">
            <h2>Your Listed Items (Reuse Hub)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reuseHubItems)): ?>
                        <?php foreach ($reuseHubItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No items listed in the Reuse Hub.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="details">
            <h2>Incident Reports</h2>
            <table>
                <thead>
                    <tr>
                        <th>Incident Type</th>
                        <th>Report Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($incidentReports)): ?>
                        <?php foreach ($incidentReports as $incident): ?>
                        <tr>
                            <td><?= htmlspecialchars($incident['incident_type']) ?></td>
                            <td><?= htmlspecialchars($incident['report_count']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No incident reports found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <div>
            <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
        </div>
    </footer>
</body>
</html>
