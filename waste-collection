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

// Initialize message variable
$message = "";

// Check if user is logged in and session contains user_id
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Retrieve user_id from session
} else {
    // If not logged in, redirect to login page
    header('Location: login.php');
    exit;
}

// Insert a waste collection report
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_report'])) {
    // Get the form inputs
    $date_of_collection = $_POST['date'];
    $waste_type = $_POST['waste-type'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $disposal_method = $_POST['disposal-method'];
    $notes = $_POST['notes'];

    // Prepare the SQL statement to insert data into waste_collection_reports
    $stmt = $conn->prepare("INSERT INTO waste_collection_reports (user_id, date_of_collection, waste_type, quantity, location, disposal_method, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississs", $user_id, $date_of_collection, $waste_type, $quantity, $location, $disposal_method, $notes);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Successful insertion
        $message = "Report submitted successfully!";
    } else {
        // General error
        $message = "Error submitting the report!";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Collection Report Form</title>
    <link rel="stylesheet" href="forms.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="collection-report-form box overview-content">
                <h1>Waste Collection Report Form</h1>
                <form action="" method="post">
                    <div class="field">
                        <label for="date">Date of Collection:</label>
                        <input type="date" id="date" name="date" required>
                    </div>

                    <div class="field">
                        <label for="waste-type">Waste Type:</label>
                        <select id="waste-type" name="waste-type" required>
                            <option value=""></option>
                            <option value="organic-waste">Organic Waste</option>
                            <option value="hazardous-waste">Hazardous Waste</option>
                            <option value="solid-waste">Solid Waste</option>
                            <option value="liquid-waste">Liquid Waste</option>
                            <option value="recyclable-waste">Recyclable Waste</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="quantity">Weight (kg):</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="20">
                    </div>

                    <div class="field">
                        <label for="location">Collection Location:</label>
                        <input type="text" id="location" name="location" required>
                    </div>

                    <div class="field">
                        <label for="disposal-method">Disposal Method:</label>
                        <select id="disposal-method" name="disposal-method" required>
                            <option value=""></option>
                            <option value="recycling">Recycling</option>
                            <option value="composting">Composting</option>
                            <option value="incineration">Incineration</option>
                            <option value="sanitary-landfill">Sanitary Landfill</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="notes">Notes:</label>
                        <textarea id="notes" name="notes" rows="2"></textarea>
                    </div>

                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> 
                    <button type="submit" name="submit_report" class="btn">Submit Report</button>
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
        // Display SweetAlert2 message after successful form submission
        <?php if ($message): ?>
            Swal.fire({
                icon: 'success',
                title: '<?php echo $message; ?>',
                showCancelButton: true,
                confirmButtonText: 'Submit Another Report',
                cancelButtonText: 'Return to Profile',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the same form to submit another report
                    window.location.href = 'collection.php';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Redirect to the homepage
                    window.location.href = 'profile.php';
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
