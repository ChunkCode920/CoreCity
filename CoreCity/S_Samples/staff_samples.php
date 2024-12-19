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

// Sample staff members to insert (without password hashing here)
$staff_members = [
    [
        'fullname' => 'Michael Johnson',
        'username' => 'michaeljohnson',
        'email' => 'michaeljohnson@example.com',
        'password' => 'johnsonSecure987', // Plaintext password (hashing will be done before storing)
        'role' => 'Staff',
        'joined_on' => date('Y-m-d'),  // Current date for 'joined_on'
        'pfp' => 'uploads/pfp4.png'
    ],
    [
        'fullname' => 'Sara Lee',
        'username' => 'saralee',
        'email' => 'saralee@example.com',
        'password' => 'leeStrong123', // Plaintext password (hashing will be done before storing)
        'role' => 'Staff',
        'joined_on' => date('Y-m-d'),  // Current date for 'joined_on'
        'pfp' => 'uploads/pfp8.png'
    ],
    [
        'fullname' => 'Daniel Carter',
        'username' => 'danielcarter',
        'email' => 'danielcarter@example.com',
        'password' => 'carterPower456', // Plaintext password (hashing will be done before storing)
        'role' => 'Staff',
        'joined_on' => date('Y-m-d'),  // Current date for 'joined_on'
        'pfp' => 'uploads/pfp9.png'
    ]
];

// Insert staff members into the database
foreach ($staff_members as $member) {
    // Hash the password before storing it
    $hashedPassword = password_hash($member['password'], PASSWORD_DEFAULT);

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO staff (fullname, username, email, password, role, joined_on, pfp) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters to the SQL query
    $stmt->bind_param("sssssss", $member['fullname'], $member['username'], $member['email'], $hashedPassword, $member['role'], $member['joined_on'], $member['pfp']);
    
    if ($stmt->execute()) {
        echo "Staff member " . $member['username'] . " added successfully!<br>";
    } else {
        echo "Error adding staff member " . $member['username'] . ": " . $stmt->error . "<br>";
    }
}

$stmt->close();
$conn->close();
?>
