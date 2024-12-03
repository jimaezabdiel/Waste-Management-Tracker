<?php
// Start the session to retrieve the logged-in user
session_start();

// Check if user is logged in (this assumes you have already set the user_id in the session)
if (!isset($_SESSION['user_id'])) {
    // If no user is logged in, you can redirect them to the login page or show an error
    header("Location: login.php");
    exit();
}

// Retrieve the user_id from the session
$user_id = $_SESSION['user_id'];

// Database connection
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

// Initialize variables
$incident_type = $_POST['incident-type'] ?? '';
$incident_location = $_POST['incident-location'] ?? '';
$incident_date = $_POST['incident-date'] ?? '';
$incident_description = $_POST['incident-description'] ?? '';
$incident_photo = ''; // Initialize variable for the photo
$success_message = ''; // Initialize success message

// Handle file upload
if (isset($_FILES['incident-photo']) && $_FILES['incident-photo']['error'] == 0) {
    // Ensure 'uploads' directory exists
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    // Handle the uploaded file
    $target_file = $target_dir . basename($_FILES['incident-photo']['name']);
    if (move_uploaded_file($_FILES['incident-photo']['tmp_name'], $target_file)) {
        $incident_photo = $target_file;  // Save the path of the uploaded photo
    } else {
        $success_message = "Error uploading photo!";
    }
}

// Insert data into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("INSERT INTO incident_reports (user_id, incident_type, incident_location, incident_date, incident_description, incident_photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $incident_type, $incident_location, $incident_date, $incident_description, $incident_photo);

    // Execute the query
    if ($stmt->execute()) {
        $success_message = "Incident report submitted successfully!";
    } else {
        $success_message = "Error submitting the incident report!";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report Form</title>
    <link rel="stylesheet" href="forms.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <div class="header-links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="waste-collection.php">Waste Collection</a>
            <a href="incident-report.php">Incident Report</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <section class="form-section">
            <div class="incident-report-form box overview-content">
                <h1>Incident Report Form</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="field">
                        <label for="incident-type">Incident Type:</label>
                        <select id="incident-type" name="incident-type" required>
                            <option value="missed-pickup">Missed Pickup</option>
                            <option value="illegal-dumping">Illegal Dumping</option>
                            <option value="vehicle-breakdown">Vehicle Breakdown</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="incident-location">Incident Location:</label>
                        <input type="text" id="incident-location" name="incident-location" required>
                    </div>

                    <div class="field">
                        <label for="incident-date">Incident Date:</label>
                        <input type="datetime-local" id="incident-date" name="incident-date" required>
                    </div>

                    <div class="field">
                        <label for="incident-description">Incident Description:</label>
                        <textarea id="incident-description" name="incident-description" rows="2" required></textarea>
                    </div>

                    <div class="field">
                        <label for="incident-photo">Upload Photo (Optional):</label>
                        <input type="file" id="incident-photo" name="incident-photo">
                    </div>

                    <button type="submit" name="submit" class="btn">Submit Report</button>
                </form>
            </div>
        </section>

        <section id="chart-section" style="display: none;">
            <h2>Incident Report Chart</h2>
            <canvas id="incidentReport"></canvas>
        </section>
    </main>

    <footer>
        <div class="footer-links">
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
        </div>
        <div>
            <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
        </div>
    </footer>

    <script>
        <?php if ($success_message): ?>
            Swal.fire({
                icon: 'success',
                title: '<?php echo $success_message; ?>',
                showCancelButton: true,
                confirmButtonText: 'Submit Another Report',
                cancelButtonText: 'Return to Homepage',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'incident-report.php';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = 'home.php';
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
