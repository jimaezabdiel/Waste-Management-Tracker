<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $sql = "INSERT INTO reuse_items (title, description, category) VALUES ('$title', '$description', '$category')";
    if ($conn->query($sql) === TRUE) {
        echo "Item listed successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: index.php"); // Redirect back to the main page
}
?>
