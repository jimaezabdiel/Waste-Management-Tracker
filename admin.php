<?php

// Db connection
 
If ($_SERVER[“REQUEST_METHOD”] == "POST") {
    $date = $_POST["date"];
    $amount = $_POST["amount"];
    $waste_type = $_POST["waste_type"];
 
    $sql = "INSERT INTO waste_logs (date, amount, waste_type) VALUES ('$date', '$amount', '$waste_type')";
 
    If ($conn->query($sql) === TRUE) {
        Echo "New record created successfully";
    } else {
        Echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h2>Log Waste</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Date: <input type="date" name="date"><br>
        Amount: <input type="text" name="amount"><br>
        Waste Type: <input type="text" name="waste_type"><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
