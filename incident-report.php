<?php
// Start the session to retrieve the logged-in user
session_start();

// Check if user is logged in (this assumes you have already set the user_id in the session)
if (!isset($_SESSION['user_id'])) {
    // If no user is logged in, redirect them to the login page
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
$success_message = ''; // Initialize success message

// Insert data into the database if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("INSERT INTO incident_reports (user_id, incident_type, incident_location, incident_date, incident_description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $incident_type, $incident_location, $incident_date, $incident_description);

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
            <a href="user-update.php">Update Profile</a>
            <a href="carbon-footprint.php">Carbon Footprint Summary</a>
            <a href="waste-collection.php">Waste Collection Form</a>
            <a href="incident-report.php">Incident Report Form</a> 
            <a href="goal.php">Management Goals</a>
        </div>
    </header>

    <main>
        <section class="form-section">
            <div class="incident-report-form box overview-content">
                <h1>Track Your Incident</h1>
                <p>Record any issues with your waste management practices.</p>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="field">
                        <label for="incident_type">Incident Type:</label>
                        <select id="incident_type" name="incident-type" required>
                            <option value=""></option>
                            <option value="missed-waste-collection">Missed Waste Collection</option>
                            <option value="improper-waste-segregation">Improper Waste Segregation</option>
                            <option value="overflowing-bin">Overflowing Bin</option>
                            <option value="non-compliance">Non-compliance with Guidelines</option>
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

                    <button type="submit" name="submit" class="btn">Submit Report</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
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
                cancelButtonText: 'Return to Profile Page',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'incident-report.php';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = 'profile.php';
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
