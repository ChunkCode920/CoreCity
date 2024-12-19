<?php
session_start();
require_once './conn.php'; // Ensure the connection file is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form data and validation
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $error = "";

    // Check passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: registration.php');
        exit();
    }

    // Ensure all fields are filled
    if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: registration.php');
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header('Location: registration.php');
        exit();
    }

    // Password validation - check length and strength
    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long.";
        header('Location: registration.php');
        exit();
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $_SESSION['error'] = "Password must contain at least one uppercase letter.";
        header('Location: registration.php');
        exit();
    } elseif (!preg_match('/[a-z]/', $password)) {
        $_SESSION['error'] = "Password must contain at least one lowercase letter.";
        header('Location: registration.php');
        exit();
    } elseif (!preg_match('/[0-9]/', $password)) {
        $_SESSION['error'] = "Password must contain at least one number.";
        header('Location: registration.php');
        exit();
    } elseif (!preg_match('/[\W_]/', $password)) { // Check for special characters
        $_SESSION['error'] = "Password must contain at least one special character.";
        header('Location: registration.php');
        exit();
    }

    // File upload logic
    if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] == 0) {
        $profile_picture = $_FILES['pfp'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($profile_picture['type'], $allowed_types)) {
            $_SESSION['error'] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            header('Location: registration.php');
            exit();
        }

        if ($profile_picture['size'] > 10 * 1024 * 1024) {  // Max file size 10MB
            $_SESSION['error'] = "File size must not exceed 10MB.";
            header('Location: registration.php');
            exit();
        }

        // File upload directory and filename
        $upload_dir = 'uploads/';
        $filename = uniqid() . '-' . basename($profile_picture['name']);
        $upload_file = $upload_dir . $filename;

        if (!move_uploaded_file($profile_picture['tmp_name'], $upload_file)) {
            $_SESSION['error'] = "Failed to upload profile picture.";
            header('Location: registration.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Profile picture is required.";
        header('Location: registration.php');
        exit();
    }

    // If no errors, insert into the database
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'User';
        $joined_on = date('Y-m-d');

        // SQL to insert data into users table
        $sql = "INSERT INTO users (fullname, email, username, password, pfp, role, joined_on) 
                VALUES (:fullname, :email, :username, :password, :pfp, :role, :joined_on)";
        
        // Prepare the statement using the PDO object ($pdo)
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':pfp', $upload_file, PDO::PARAM_STR); // Path to the uploaded file
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':joined_on', $joined_on, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['message'] = "Registration successful! You can now log in.";
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = "Database error. Please try again later.";
            header('Location: registration.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error. Please try again later.";
        header('Location: registration.php');
        exit();
    }
}
?>
