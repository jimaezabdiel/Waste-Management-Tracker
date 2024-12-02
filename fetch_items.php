<?php
include 'db_connection.php';

function fetchItems($conn, $status = 'Available') {
    $sql = "SELECT * FROM reuse_items WHERE status = '$status' ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $items = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}
?>
