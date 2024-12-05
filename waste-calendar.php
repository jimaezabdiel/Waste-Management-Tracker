<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// CalendarManager Class
class CalendarManager {
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function fetchCalendarEvents($userId, $month, $year) {
        $startDate = "$year-$month-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        $stmt = $this->conn->prepare("
            SELECT id, date, event_type, description
            FROM waste_calendar
            WHERE user_id = ? AND date BETWEEN ? AND ?
        ");
        $stmt->bind_param("iss", $userId, $startDate, $endDate);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addEvent($userId, $date, $eventType, $description) {
        $stmt = $this->conn->prepare("
            INSERT INTO waste_calendar (user_id, date, event_type, description)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("isss", $userId, $date, $eventType, $description);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function deleteEvent($eventId) {
        $stmt = $this->conn->prepare("DELETE FROM waste_calendar WHERE id = ?");
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

// Initialize CalendarManager
$calendarManager = new CalendarManager("localhost", "root", "", "user_data");

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $userId = $_POST['user_id'];
    $date = $_POST['date'];
    $eventType = $_POST['event_type'];
    $description = $_POST['description'];

    if ($calendarManager->addEvent($userId, $date, $eventType, $description)) {
        $successMessage = 'Event Added!';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    $eventId = $_POST['event_id'];

    if ($calendarManager->deleteEvent($eventId)) {
        $successMessage = 'Event Deleted!';
    } else {
        $errorMessage = 'Error deleting event.';
    }
}

$userId = $_SESSION['user_id'];
$month = date('m');
$year = date('Y');
$events = $calendarManager->fetchCalendarEvents($userId, $month, $year);

// Organize events by date
$eventsByDate = [];
foreach ($events as $event) {
    $eventsByDate[$event['date']][] = $event;
}

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$startDay = date('N', strtotime("$year-$month-01"));

// Close the database connection
$calendarManager->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Calendar</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #DDF1E4;
            color: #2c6b3f;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            margin-top: 30px;
            font-size: 2rem;
            color: #2c6b3f;
        }

        .navigation {
            width: 100%;
            max-width: 900px;
            margin-top: 15px;
            display: flex;
            justify-content: flex-start;
            padding: 10px;
        }

        .navigation a {
            text-decoration: none;
            font-weight: bold;
            color: #2c6b3f;
            background-color: #fff;
            padding: 10px 20px;
            border: 1px solid #a3c6a1;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .navigation a:hover {
            background-color: #a3c6a1;
        }

        .calendar-container {
            width: 100%;
            max-width: 900px;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .calendar-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .calendar-header h2 {
            font-size: 1.8rem;
            margin: 0;
            color: #2c6b3f;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .calendar-day,
        .calendar-grid > div {
            background-color: #ffffff;
            border: 1px solid #a3c6a1;
            border-radius: 5px;
            text-align: center;
            padding: 10px;
            transition: background-color 0.3s;
        }

        .calendar-day:hover {
            background-color: #a3c6a1;
        }

        .calendar-day strong {
            display: block;
            font-size: 1.2rem;
            color: #2c6b3f;
            margin-bottom: 5px;
        }

        .event {
            background-color: #2c6b3f;
            color: white;
            border-radius: 3px;
            padding: 5px;
            font-size: 0.9rem;
            margin-top: 5px;
            overflow-wrap: break-word;
            text-align: left;
            position: relative;
        }

        .event-type {
            font-weight: bold;
        }

        .event-options {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }

        .event-options:hover .options-menu {
            display: block;
        }

        .options-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #a3c6a1;
            border-radius: 5px;
            top: 20px;
            right: 0;
            width: 100px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .options-menu a {
            display: block;
            padding: 5px;
            text-decoration: none;
            color: #2c6b3f;
            border-bottom: 1px solid #a3c6a1;
        }

        .options-menu a:hover {
            background-color: #a3c6a1;
        }

        .event-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin-top: 30px;
        }

        .event-form h3 {
            text-align: center;
            color: #2c6b3f;
            margin-bottom: 15px;
        }

        .event-form label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c6b3f;
        }

        .event-form input,
        .event-form textarea,
        .event-form button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #a3c6a1;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .event-form button {
            background-color: #2c6b3f;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        .event-form button:hover {
            background-color: #4d8a63;
        }

        .success-message, .error-message {
            color: #2c6b3f;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Waste Calendar</h1>

<div class="navigation">
    <a href="home.php">Home</a>
</div>

<?php if ($successMessage): ?>
    <div class="success-message"><?php echo $successMessage; ?></div>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <div class="error-message"><?php echo $errorMessage; ?></div>
<?php endif; ?>

<div class="calendar-container">
    <div class="calendar-header">
        <h2><?php echo date("F Y", strtotime("$year-$month-01")); ?></h2>
    </div>

    <div class="calendar-grid">
        <?php
        $weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($weekdays as $weekday) {
            echo "<div><strong>$weekday</strong></div>";
        }

        $day = 1;
        for ($i = 0; $i < 6; $i++) {
            for ($j = 0; $j < 7; $j++) {
                if ($i == 0 && $j < $startDay - 1) {
                    echo "<div></div>";
                } else if ($day <= $daysInMonth) {
                    $currentDate = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                    echo "<div class='calendar-day'>";

                    echo "<strong>$day</strong>";

                    if (isset($eventsByDate[$currentDate])) {
                        foreach ($eventsByDate[$currentDate] as $event) {
                            echo "<div class='event'>";
                            echo "<span class='event-type'>{$event['event_type']}</span><br>";
                            echo "<span>{$event['description']}</span>";

                            // Ellipsis and options
                            echo "<div class='event-options'>";
                            echo "&#x2022;&#x2022;&#x2022;"; // Ellipsis symbol
                            echo "<div class='options-menu'>";
                            echo "<form method='POST' action=''>";
                            echo "<input type='hidden' name='event_id' value='{$event['id']}'>";
                            echo "<button type='submit' name='delete_event'>Delete</button>";
                            echo "</form>";
                            echo "</div>";
                            echo "</div>";

                            echo "</div>";
                        }
                    }

                    echo "</div>";
                    $day++;
                } else {
                    echo "<div></div>";
                }
            }
        }
        ?>
    </div>
</div>

<div class="event-form">
    <h3>Add Event</h3>
    <form method="POST" action="">
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
        <label for="date">Date:</label>
        <input type="date" name="date" required>

        <label for="event_type">Event Type:</label>
        <input type="text" name="event_type" required>

        <label for="description">Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <button type="submit" name="add_event">Add Event</button>
    </form>
</div>

</body>
</html>
