<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchCalendarEvents($conn, $userId, $month, $year) {
    $startDate = "$year-$month-01";
    $endDate = date("Y-m-t", strtotime($startDate));

    $stmt = $conn->prepare("
        SELECT date, event_type, description
        FROM waste_calendar
        WHERE user_id = ? AND date BETWEEN ? AND ?
    ");
    $stmt->bind_param("iss", $userId, $startDate, $endDate);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $userId = $_POST['user_id'];
    $date = $_POST['date'];
    $eventType = $_POST['event_type'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("
        INSERT INTO waste_calendar (user_id, date, event_type, description)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isss", $userId, $date, $eventType, $description);
    $stmt->execute();
    
    // Set success message
    $successMessage = 'Event Added!';
}

$month = date('m');
$year = date('Y');
$loggedInUserId = $user_id;

$events = fetchCalendarEvents($conn, $loggedInUserId, $month, $year);

$eventsByDate = [];
foreach ($events as $event) {
    $eventsByDate[$event['date']][] = $event;
}

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$startDay = date('N', strtotime("$year-$month-01"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Calendar</title>
    <link rel="stylesheet" href="calendar.css">
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
        <a href="waste-calendar.php">Waste Calendar</a>
    </div>
</header>

<main>
    <div class="form-section">
        <div class="form-container">
            <h1>Add an Event</h1>
            <form action="waste-calendar.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $loggedInUserId; ?>">

                <div class="field">
                    <label for="date">Date:</label>
                    <input type="date" name="date" id="date" required>
                </div>

                <div class="field">
                    <label for="event_type">Event Type:</label>
                    <select name="event_type" id="event_type" required>
                        <option value="Disposal">Disposal</option>
                        <option value="Reminder">Reminder</option>
                    </select>
                </div>

                <div class="field">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" rows="4" required></textarea>
                </div>

                <button type="submit" name="add_event" class="btn">Add Event</button>
            </form>
        </div>
    </div>

    <div class="calendar-section">
        <div class="calendar-header">
            <span class="prev-month">&#9664;</span>
            <h2 class="month-year"><?php echo date('F Y', strtotime("$year-$month-01")); ?></h2>
            <span class="next-month">&#9654;</span>
        </div>
        
        <div class="calendar">
            <?php for ($i = 1; $i < $startDay; $i++): ?>
                <div class="day"></div>
            <?php endfor; ?>

            <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                <?php
                $currentDate = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                $dayEvents = $eventsByDate[$currentDate] ?? [];
                ?>
                <div class="day">
                    <strong><?php echo $day; ?></strong>
                    <?php foreach ($dayEvents as $event): ?>
                        <div class="event">
                            <?php echo htmlspecialchars($event['event_type'] . ": " . $event['description']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2024 Waste Management Tracker</p>
</footer>

<?php if ($successMessage): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: '<?php echo $successMessage; ?>',
        text: 'The event has been successfully added to your calendar.',
    }).then(() => {
        window.location.href = 'waste-calendar.php'; // Redirect to the calendar page
    });
</script>
<?php endif; ?>

</body>
</html>
