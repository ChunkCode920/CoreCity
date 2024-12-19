<?php
// Database connection details
$servername = "localhost";  // or the host of your database
$username = "root";         // your database username
$password = "";             // your database password
$dbname = "core_city";  // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sample users to add
$users = [
    [
        'fullname' => 'John Doe',
        'username' => 'johndoe',
        'email' => 'johndoe@example.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT), // Hashed password
        'joined_on' => '2024-11-30',
        'role' => 'user',
        'pfp' => 'uploads/pfp1.png'  // To be updated with actual file path
    ],
    [
        'fullname' => 'Jane Smith',
        'username' => 'janesmith',
        'email' => 'janesmith@example.com',
        'password' => password_hash('securepassword', PASSWORD_DEFAULT),
        'joined_on' => '2024-11-30',
        'role' => 'user',
        'pfp' => 'uploads/pfp5.png'  // To be updated with actual file path
    ],
    [
        'fullname' => 'Alice Johnson',
        'username' => 'alicejohnson',
        'email' => 'alicejohnson@example.com',
        'password' => password_hash('alicepassword', PASSWORD_DEFAULT),
        'joined_on' => '2024-11-30',
        'role' => 'user',
        'pfp' => 'uploads/pfp11.png'  // To be updated with actual file path
    ],
    [
        'fullname' => 'Bob Williams',
        'username' => 'bobwilliams',
        'email' => 'bobwilliams@example.com',
        'password' => password_hash('bobsecurepass', PASSWORD_DEFAULT),
        'joined_on' => '2024-11-30',
        'role' => 'user',
        'pfp' => 'uploads/pfp2.png'  // To be updated with actual file path
    ],
    [
        'fullname' => 'Charlie Brown',
        'username' => 'charliebrown',
        'email' => 'charliebrown@example.com',
        'password' => password_hash('charliepassword', PASSWORD_DEFAULT),
        'joined_on' => '2024-11-30',
        'role' => 'user',
        'pfp' => 'uploads/pfp3.png'  // To be updated with actual file path
    ],
    [
        'fullname' => 'Eve Davis',
        'username' => 'evedavis',
        'email' => 'evedavis@example.com',
        'password' => password_hash('evesecretpass', PASSWORD_DEFAULT),
        'joined_on' => '2024-11-30',
        'role' => 'user',
        'pfp' => 'uploads/pfp13.png'  // To be updated with actual file path
    ]
];


// Insert users into the database
foreach ($users as $user) {
    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, joined_on, role, pfp) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $user['fullname'], $user['username'], $user['email'], $user['password'], $user['joined_on'], $user['role'], $user['pfp']);
    
    if ($stmt->execute()) {
        echo "User " . $user['username'] . " added successfully!<br>";
    } else {
        echo "Error adding user " . $user['username'] . ": " . $stmt->error . "<br>";
    }
}

$stmt->close();
$conn->close();
?>
