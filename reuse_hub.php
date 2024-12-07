<?php
// Start session
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

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get logged-in user's ID
$userId = $_SESSION['user_id'];

// Add a new item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Insert item into the reuse_items table, along with the user_id
    $stmt = $conn->prepare("INSERT INTO reuse_items (title, description, category, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $category, $userId);

    if ($stmt->execute()) {
        $successMessage = true; // Flag to show SweetAlert2
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch items
$stmt = $conn->prepare("SELECT id, title, description, category, status, created_at FROM reuse_items WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Update item status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $itemId = $_POST['item_id'];
    $newStatus = $_POST['status'];

    $stmt = $conn->prepare("UPDATE reuse_items SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $newStatus, $itemId, $userId);

    if ($stmt->execute()) {
        $statusUpdateMessage = "Item status updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuse Hub</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
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
            <a href="reuse_hub.php">Reuse Hub</a>
            <a href="trivia.php">Trivia</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <div class="display">
        <h1>Reuse Hub</h1>
        </div>
        <section class="form-section">
        <div class="add-item-form box overview-content">
            <h2>List an Item</h2>
            <form method="POST">
                <div class="field">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" required>
                </div>

                <div class="field">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" required></textarea>
                </div>

                <div class="field">
                    <label for="category">Category:</label>
                    <select name="category" id="category">
                        <option value="Furniture">Furniture</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <input type="hidden" name="user_id" value="<?php echo $userId; ?>"> 
                <button type="submit" name="add_item" class="btn">Submit Report</button>
            </form>
        </div>
        </section>
    </main>

    <!-- SweetAlert2 for success messages -->
    <script>
        <?php if (isset($successMessage) && $successMessage): ?>
            Swal.fire({
                title: 'Success!',
                text: 'Your item has been listed successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        <?php if (isset($statusUpdateMessage)): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?= $statusUpdateMessage ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>

    <footer>
        <div>
            <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
        </div>
    </footer>
</body>
</html>
