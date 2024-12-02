<?php
include 'fetch_items.php';
$availableItems = fetchItems($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reuse Hub</title>
</head>
<body>
    <h1>Reuse Hub</h1>

    <!-- Form to Add a New Item -->
    <h2>List an Item</h2>
    <form action = "add_item.php" method = "POST">
        <label>Title:</label>
        <input type = "text" name = "title" required><br><br>

        <label>Description:</label>
        <textarea name = "description" required></textarea><br><br>

        <label>Category:</label>
        <select name = "category">
            <option value = "Furniture">Furniture</option>
            <option value = "Electronics">Electronics</option>
            <option value = "Clothing">Clothing</option>
            <option value = "Other">Other</option>
        </select><br><br>

        <button type = "submit" name = "add_item">List Item</button>
    </form>

    <!-- Display Available Items -->
    <h2>Available Items</h2>
    <?php foreach ($availableItems as $item): ?>
        <div>
            <h3><?= htmlspecialchars($item['title']); ?></h3>
            <p><?= htmlspecialchars($item['description']); ?></p>
            <p>Category: <?= htmlspecialchars($item['category']); ?></p>
            <p>Status: <?= htmlspecialchars($item['status']); ?></p>
        </div>
        <hr>
    <?php endforeach; ?>

    <!-- Form to Update Item Status -->
    <h2>Update Item Status</h2>
    <form action = "update_status.php" method = "POST">
        <label>Item ID:</label>
        <input type = "number" name = "item_id" required><br><br>

        <label>New Status:</label>
        <select name = "status">
            <option value = "Available">Available</option>
            <option value = "Claimed">Claimed</option>
            <option value = "Exchanged">Exchanged</option>
        </select><br><br>

        <button type = "submit" name = "update_status">Update Status</button>
    </form>
</body>
</html>
