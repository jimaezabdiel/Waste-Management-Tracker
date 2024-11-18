<?php
   $servername = “localhost”;
   $username = “your_username”;
   $password = “your_password”;
   $dbname = “your_database_name”;
 
$conn = new mysqli($servername, $username, $password, $dbname);
 
   If ($conn->connect_error) {
     Die(“Connection failed: “ . $conn->connect_error);
}
?>
