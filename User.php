<?php

// Db connection
 
$sql = "SELECT * FROM waste_logs";
$result = $conn->query($sql);
 
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Waste Logs</title>
</head>
<body>
    <h2>Waste Logs</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Waste type</th>
        </tr>
        <?php
        If ($result->num_rows > 0) {
            While($row = $result->fetch_assoc()) {
                Echo "<tr><td>" . $row["date"] . "</td><td>" . $row["amount"] . "</td><td>" . $row["waste_type"] . "</td></tr>";
            }
        } else {
            Echo "0 results";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
