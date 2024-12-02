<?php

// Define the file path for incident reports
$incident_reports_file_path = "incident_reports.json";
$success_message = '';

// Handle the incident report form submission
if (isset($_POST['submit'])) {
    // Get the user input from the form
    $incident_type = htmlspecialchars($_POST['incident-type']);
    $incident_location = htmlspecialchars($_POST['incident-location']);
    $incident_date = htmlspecialchars($_POST['incident-date']);
    $incident_description = htmlspecialchars($_POST['incident-description']);
    $incident_photo = isset($_FILES['incident-photo']) ? $_FILES['incident-photo']['name'] : null;

    // Prepare the new incident report data
    $new_incident_report = [
        'incident-type' =>  $incident_type,
        'incident-location' =>  $incident_location,
        'incident-date' => $incident_date,
        'incident-description' => $incident_description,
        'incident-photo' => $incident_photo,
    ];

    // Check if the incident_reports.json file exists
    if (file_exists($incident_reports_file_path)) {
        // Read the existing data from the file
        $json_data = file_get_contents($incident_reports_file_path);
        $incident_reports = json_decode($json_data, true); // Decode the JSON into an array
    } else {
        // If the file doesn't exist, initialize an empty array
        $incident_reports = [];
    }

    // Add the new incident report to the existing logs array
    $incident_reports[] = $new_incident_report;

    // Save the updated incident report data back to the file
    file_put_contents($incident_reports_file_path, json_encode($incident_reports, JSON_PRETTY_PRINT));

    // Set success message
    $success_message = "<p>You have successfully submitted your incident report.</p>";
    $success_message .= "<a href='javascript:void(0)' id='show-chart-link'>Click here to view the incident report in a chart</a>";
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <div class="header-links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="collection.php">Waste Collection</a>
            <a href="incident-report.php">Incident Report</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
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
                <?php
                    if ($success_message) {
                        echo $success_message;
                    }
                ?>
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
        document.getElementById('show-chart-link').addEventListener('click', function() {
            // Fetch the incident report data from the PHP file
            fetch('incident_reports.json')
                .then(response => response.json())
                .then(data => {
                    // Extract data for chart
                    let labels = [];
                    let dataPoints = {
                        'missed-pickup': 0,
                        'illegal-dumping': 0,
                        'vehicle-breakdown': 0
                    };

                    // Count incidents by type
                    data.forEach(incident => {
                        labels.push(incident['incident-location']);
                        dataPoints[incident['incident-type']]++;
                    });

                    // Create the chart
                    let ctx = document.getElementById('incidentReport').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(dataPoints),
                            datasets: [{
                                label: 'Incident Count',
                                data: Object.values(dataPoints),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Show the chart section
                    document.getElementById('chart-section').style.display = 'block';
                });
        });
    </script>

</body>
</html>
