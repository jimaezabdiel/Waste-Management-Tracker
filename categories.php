<!DOCTYPE html>
<html>
<head>
  <title>Admin: Manage Categories</title>
</head>
<body>
  <h2>Manage Waste Categories</h2>
  <h3>Add New Category</h3>
  <form method="post" action="process_category.php">
    <label for="category_name">Category Name:</label>
    <input type="text" name="category_name" id="category_name" required>
    <br>
    <input type="submit" value="Add Category">
  </form>
  
  <h3>Existing Categories</h3>
  <table>
    <thead>
      <tr>
        <th>Category ID</th>
        <th>Category Name</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php

        // Fetch existing categories from the db and display them in a table
        $categories = get_categories();
        foreach ($categories as $category) {
          echo "<tr>";
          echo "<td>{$category['category_id']}</td>";
          echo "<td>{$category['category_name']}</td>";
          echo "<td><a href='edit_category.php?id={$category['category_id']}'>Edit</a> | <a href='delete_category.php?id={$category['category_id']}'>Delete</a></td>";
          echo "</tr>";
        }
      ?>
    </tbody>
  </table>
</body>
</html>
