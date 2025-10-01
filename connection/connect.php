<?php
$host = "localhost"; //server
$username = "root"; //username
$password = ""; //password
$dbname = "dbfood";  //database
// Create connection
$db = mysqli_connect($host, $username, $password, $dbname); // connecting 
// Check connection
if (!$db) {       //checking connection to DB	
    die("Connection failed: " . mysqli_connect_error());
}


try {
    // Create a new PDO instance and assign it to the $pdo variable
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display the error message and stop the script
    echo "Connection failed: " . $e->getMessage();
    exit();
}

?>