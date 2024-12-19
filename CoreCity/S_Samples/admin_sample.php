<?php
// Database connection details
$servername = "localhost";  // Your database host
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "core_city";      // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sample admin member to insert (using plaintext password here)
$admin_member = [
    'fullname' => 'Oliver King',          // Unique name for admin
    'username' => 'oliverking',           // Unique username
    'email' => 'oliverking@example.com',  // Unique email
    'password' => 'kingStrongPass2024',   // Unique password
    'role' => 'admin',                    // Admin role
    'joined_on' => date('Y-m-d'),         // Current date for 'joined_on'
    'pfp' => 'uploads/pfp14.png'      // Path to the admin's profile picture
];

// Hash the password before storing it
$hashedPassword = password_hash($admin_member['password'], PASSWORD_DEFAULT);

// Prepare the SQL query
$stmt = $conn->prepare("INSERT INTO admin (fullname, username, email, password, role, joined_on, pfp) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");

// Bind parameters to the SQL query
$stmt->bind_param("sssssss", $admin_member['fullname'], $admin_member['username'], $admin_member['email'], $hashedPassword, $admin_member['role'], $admin_member['joined_on'], $admin_member['pfp']);

// Execute the query
if ($stmt->execute()) {
    echo "Admin user " . $admin_member['fullname'] . " added successfully!<br>";
} else {
    echo "Error adding admin user " . $admin_member['fullname'] . ": " . $stmt->error . "<br>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
