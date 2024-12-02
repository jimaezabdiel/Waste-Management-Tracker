<?php
// Db connection
class Database {
    private $host = "localhost";
    private $db_name = "waste_collection";
    private $username = "me";
    private $password = "";
    public $conn;

    public function getConnect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";waste_collection=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
}

// Function to request a waste pickup
function requestPickup($userId, $requestDate) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO pickup_requests (user_id, request_date) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $requestDate);
    $stmt->execute();
    $stmt->close();
    echo "Pickup request submitted successfully.";
}

// Function for admin to assign a schedule
function assignSchedule($requestId, $scheduleDate) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO schedules (request_id, schedule_date) VALUES (?, ?)");
    $stmt->bind_param("is", $requestId, $scheduleDate);
    $stmt->execute();

    $stmt2 = $conn->prepare("UPDATE pickup_requests SET status = 'Scheduled' WHERE id = ?");
    $stmt2->bind_param("i", $requestId);
    $stmt2->execute();

    $stmt->close();
    $stmt2->close();
    echo "Schedule assigned successfully.";
}

// Function to send reminders
function sendReminders() {
    global $conn;
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT schedules.id, users.email, schedules.schedule_date 
                            FROM schedules 
                            JOIN pickup_requests ON schedules.request_id = pickup_requests.id
                            JOIN users ON pickup_requests.user_id = users.id
                            WHERE schedules.schedule_date = ? AND schedules.reminder_sent = 0");
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Simulate email sending (replace with actual mail function)
        echo "Reminder sent to " . $row['email'] . " for collection on " . $row['schedule_date'] . "<br>";
        
        $updateStmt = $conn->prepare("UPDATE schedules SET reminder_sent = 1 WHERE id = ?");
        $updateStmt->bind_param("i", $row['id']);
        $updateStmt->execute();
        $updateStmt->close();
    }

    $stmt->close();
}

?>
