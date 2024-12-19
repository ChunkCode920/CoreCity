<?php
// conn.php — Database connection file

// Set database connection parameters
$host = 'localhost';      // Your database host (e.g., localhost)
$dbname = 'core_city'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

// Create a PDO instance and handle potential errors
try {
    // Establish the connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Database connection successful.");

} catch (PDOException $e) {
    // In case of error, show a message and stop execution
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
