<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update as necessary
$password = ""; // Update as necessary
$dbname = "waste_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle data submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $log_date = $_POST['log_date'];
    $waste_type = $_POST['waste_type'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO waste_logs (log_date, waste_type, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $log_date, $waste_type, $amount);
    if ($stmt->execute()) {
        echo "<p>Data added successfully!</p>";
    } else {
        echo "<p>Error adding data: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Fetch and aggregate data for the chart
$query = "
    SELECT DATE_FORMAT(log_date, '%Y-%m') AS month, waste_type, SUM(amount) AS total_amount
    FROM waste_logs
    GROUP BY month, waste_type
    ORDER BY month ASC";
$result = $conn->query($query);

// Prepare data for the chart
$labels = [];
$data = [];
$wasteTypes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $month = $row['month'];
        $type = $row['waste_type'];
        $amount = $row['total_amount'];

        if (!in_array($month, $labels)) {
            $labels[] = $month;
        }

        if (!isset($data[$type])) {
            $data[$type] = array_fill(0, count($labels), 0);
        }

        $data[$type][array_search($month, $labels)] = $amount;
        if (!in_array($type, $wasteTypes)) {
            $wasteTypes[] = $type;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visual Waste Trends</title>
    <script src = "https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Visual Waste Trends</h1>

    <!-- Form to collect data -->
    <form method = "POST" action = "">
        <label for = "log_date">Date:</label>
        <input type = "date" id = "log_date" name = "log_date" required><br><br>

        <label for = "waste_type">Waste Type:</label>
        <select id = "waste_type" name = "waste_type" required>
            <option value = "organic">Organic</option>
            <option value = "plastic">Plastic</option>
            <option value = "recyclable">Recyclable</option>
        </select><br><br>

        <label for = "amount">Amount (kg):</label>
        <input type = "number" step = "0.1" id = "amount" name = "amount" required><br><br>

        <button type = "submit">Add Waste Data</button>
    </form>

    <canvas id = "wasteChart" width = "600" height = "400"></canvas>

    <script>
        // Prepare data for the chart directly in JavaScript
        const labels = <?php echo json_encode($labels); ?>;
        const datasets = [
            <?php foreach ($data as $type => $amounts): ?>,
            {
                label: '<?php echo $type; ?>',
                data: [<?php echo implode(',', $amounts); ?>],
                backgroundColor: '<?php echo sprintf("rgba(%d, %d, %d, 0.7)", rand(0, 255), rand(0, 255), rand(0, 255)); ?>',
            },
            <?php endforeach; ?>
        ];

        // Render the chart
        const ctx = document.getElementById('wasteChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    x: { title: { display: true, text: 'Month' } },
                    y: { title: { display: true, text: 'Waste Amount (kg)' } }
                }
            }
        });
    </script>
</body>
</html>
