<?php
// Database class definition
class Database {
    private $host = "localhost";
    private $db_name = "dbko";
    private $username = "root";
    private $password = "";
    public $conn;

    // Establish database connection
    public function getConnect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    // Fetch all users from the 'users' table
    public function fetchUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert a new user into the 'users' table
    public function insertUser($username, $email, $age, $password) {
        $query = "INSERT INTO users (username, email, age, password) VALUES (:username, :email, :age, :password)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":password", $password);

        // Execute the statement
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

// Main logic
$db = new Database();
$conn = $db->getConnect();

if ($conn) {
    // Example: Fetch and display all users
    $users = $db->fetchUsers();
    echo "<h2>Users List:</h2>";
    foreach ($users as $user) {
        echo "ID: " . $user['id'] . ", Username: " . $user['username'] . ", Email: " . $user['email'] . ", Age: " . $user['age'] . "<br>";
    }

    // Example: Insert a new user
    echo "<br><h2>Inserting a New User:</h2>";
    $success = $db->insertUser("john_doe", "john@example.com", 25, "securepassword");
    if ($success) {
        echo "User added successfully.";
    } else {
        echo "Failed to add user.";
    }
}
?>