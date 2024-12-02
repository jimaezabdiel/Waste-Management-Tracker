<!DOCTYPE html>
<html>
<head>
  <title>Waste Categorization</title>
</head>
<body>
  <h2>Categorize Your Waste</h2>
  <form method = "post" action = "process_categorization.php">
    <label for = "category">Select Category:</label>
    <select name = "category" id = "category">
      <?php 

        // Fetch categories from db
        $categories = get_categories();
        foreach ($categories as $category) {
          echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
        }
      ?>
    </select>
    <br>
    <label for = "quantity">Quantity:</label>
    <input type = "number" name = "quantity" id = "quantity" required>
    <br>
    <input type = "submit" value = "Categorize"> 1 
  </form>
</body>
</html>
