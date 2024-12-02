// waste logging page 
<!DOCTYPE html>
<html>
<head>
    <title>Log Waste</title>
</head>
<body>
    <h1>Log Your Waste</h1>
    <form action="log_waste.php" method="POST">
        <label>Waste Type:</label>
        <select name="waste_type" required>
            <option value="plastic_bottle">Plastic Bottle</option>
            <option value="plastic_bag">Plastic Bag</option>
            <option value="paper">Paper</option>
            <option value="food_waste">Food Waste</option>
        </select><br><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" required><br><br>

        <button type="submit">Log Waste</button>
    </form>
</body>
</html>


