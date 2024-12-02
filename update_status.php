<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $itemId = $_POST['item_id'];
    $newStatus = $_POST['status'];

    $sql = "UPDATE reuse_items SET status = '$newStatus' WHERE id = $itemId";
    if ($conn->query($sql) === TRUE) {
        echo "Item status updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: index.php"); // Redirect back to the main page
}
?>
