<?php
$servername = "localhost";
$username = "root";
$password = "45210";
$dbname = "waste_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function logWasteDisposal($conn, $waste_type) {
    $disposal_date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO waste_logs (waste_type, disposal_date) VALUES (?, ?)");
    $stmt->bind_param("ss", $waste_type, $disposal_date);

    if ($stmt->execute()) {
        echo "Logged disposal of $waste_type on $disposal_date<br>";
    } else {
        echo "Error logging disposal: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

function calculateFrequency($conn, $waste_type) {
    $stmt = $conn->prepare("SELECT disposal_date FROM waste_logs WHERE waste_type = ? ORDER BY disposal_date DESC LIMIT 5");
    $stmt->bind_param("s", $waste_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $dates = [];

    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['disposal_date'];
    }

    $stmt->close();

    if (count($dates) < 2) {
        return null;
    }

    $total_days = 0;
    for ($i = 0; $i < count($dates) - 1; $i++) {
        $datetime1 = new DateTime($dates[$i]);
        $datetime2 = new DateTime($dates[$i + 1]);
        $interval = $datetime1->diff($datetime2);
        $total_days += $interval->days;
    }

    $average_days = $total_days / (count($dates) - 1);
    return round($average_days);
}

function setAdaptiveReminder($conn, $waste_type) {
    $frequency = calculateFrequency($conn, $waste_type);

    if ($frequency) {
        $next_reminder_date = date('Y-m-d H:i:s', strtotime("+$frequency days"));
        echo "Next $waste_type disposal reminder set for $next_reminder_date<br>";
        return $next_reminder_date;
    } else {
        echo "Not enough data to set a reminder for $waste_type<br>";
        return null;
    }
}

function displayLogs($conn) {
    $result = $conn->query("SELECT * FROM waste_logs ORDER BY disposal_date DESC");

    if ($result->num_rows > 0) {
        echo "<h3>Waste Disposal Logs</h3>";
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Waste Type: " . $row["waste_type"] . " - Disposal Date: " . $row["disposal_date"] . "<br>";
        }
    } else {
        echo "No logs found.<br>";
    }
}

logWasteDisposal($conn, "recyclables");
logWasteDisposal($conn, "organic");

displayLogs($conn);

setAdaptiveReminder($conn, "recyclables");
setAdaptiveReminder($conn, "organic");

$conn->close();
?>
