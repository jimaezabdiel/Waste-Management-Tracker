<?php

// Define the file path for waste logs
$waste_log_file_path = "waste_logs.json";
$success_message = '';

// Handle the waste report form submission
if (isset($_POST['submit'])) {
    // Get the user input from the form
    $date = htmlspecialchars($_POST['date']);
    $waste_type = htmlspecialchars($_POST['waste-type']);
    $quantity = htmlspecialchars($_POST['quantity']);
    $location = htmlspecialchars($_POST['location']);
    $disposal_method = htmlspecialchars($_POST['disposal-method']);
    $notes = htmlspecialchars($_POST['notes']);
    
    // Prepare the new waste log data
    $new_waste_log = [
        'date' => $date,
        'waste_type' => $waste_type,
        'quantity' => $quantity,
        'location' => $location,
        'disposal_method' => $disposal_method,
        'notes' => $notes
    ];

    // Check if the waste_logs.json file exists
    if (file_exists($waste_log_file_path)) {
        // Read the existing data from the file
        $json_data = file_get_contents($waste_log_file_path);
        $waste_logs = json_decode($json_data, true); // Decode the JSON into an array
    } else {
        // If the file doesn't exist, initialize an empty array
        $waste_logs = [];
    }

    // Add the new waste log to the existing logs array
    $waste_logs[] = $new_waste_log;

    // Save the updated waste logs data back to the file
    file_put_contents($waste_log_file_path, json_encode($waste_logs, JSON_PRETTY_PRINT));

    // Set success message
    $success_message = "<p>You have successfully submitted your waste collection report.</p>";
    $success_message .= "<a href='javascript:void(0)' id='show-chart-link'>Click here to view the waste logs in a chart</a>";
}

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
                            <option value="organic-waste">Organic Waste</option>
                            <option value="hazardous-waste">Hazardous Waste</option>
                            <option value="solid-waste">Solid Waste</option>
                            <option value="liquid-waste">Liquid Waste</option>
                            <option value="recyclable-waste">Recyclable Waste</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="quantity">Number of Bags:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="10">
                    </div>

                    <div class="field">
                        <label for="location">Collection Location:</label>
                        <input type="text" id="location" name="location" required>
                    </div>

                    <div class="field">
                        <label for="disposal-method">Disposal Method:</label>
                        <select id="disposal-method" name="disposal-method" required>
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
            <h2>Waste Collection Data Chart</h2>
            <canvas id="wasteChart"></canvas>
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
        // Fetch the waste logs data from the waste_logs.json file
        function showWasteLogsChart() {
            fetch('waste_logs.json')
                .then(response => response.json())
                .then(data => {
                    // Initialize the counters for each waste type
                    const wasteCounts = {
                        'organic-waste': 0,
                        'hazardous-waste': 0,
                        'solid-waste': 0,
                        'liquid-waste': 0,
                        'recyclable-waste': 0
                    };

                    // Sum the quantities for each waste type
                    data.forEach(log => {
                        wasteCounts[log.waste_type] += parseInt(log.quantity);
                    });

                    // Prepare the chart data
                    const chartData = {
                        labels: Object.keys(wasteCounts),
                        datasets: [{
                            label: 'Waste Quantity (Bags)',
                            data: Object.values(wasteCounts),
                            backgroundColor: ['#FF9999', '#66B2FF', '#99FF99', '#FFCC99', '#FFFF99'],
                            borderColor: ['#FF6666', '#3399FF', '#66FF66', '#FF9966', '#FFFF66'],
                            borderWidth: 1
                        }]
                    };

                    // Create the chart
                    const ctx = document.getElementById('wasteChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: chartData,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.raw + ' Bags';
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Show the chart section
                    document.getElementById('chart-section').style.display = 'block';
                })
                .catch(error => console.error('Error fetching waste logs:', error));
        }

        // Event listener for the "Click here to view the waste logs in a chart" link
        document.getElementById('show-chart-link')?.addEventListener('click', showWasteLogsChart);
    </script>
</body>
</html>
