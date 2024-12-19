<?php
session_start();
require_once './conn.php'; // Ensure the connection file is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);  // role is received from the form

    // Validate inputs
    if (empty($username) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: index.php');
        exit();
    }

    try {
        // Set the table and ID field based on the role
        if ($role === 'User') {
            $table = 'users';
            $id_field = 'user_id';  // ID field for 'users' table
        } elseif ($role === 'Staff') {
            $table = 'staff';
            $id_field = 'staff_id';  // ID field for 'staff' table
        } elseif ($role === 'Admin') {
            $table = 'admin';
            $id_field = 'admin_id';  // ID field for 'admin' table
        } else {
            $_SESSION['error'] = "Invalid role selected.";
            header('Location: index.php');
            exit();
        }

        // Prepare and execute query to fetch user details
        $sql = "SELECT * FROM $table WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Check if user exists in the database
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if the password is hashed
            if (password_get_info($user['password'])['algo']) {
                // The password is hashed, verify using password_verify
                if (password_verify($password, $user['password'])) {
                    // Password is correct, set session variables
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_id'] = $user[$id_field]; // Use dynamic field name based on role
                    
                    // Redirect based on the role
                    if ($role == 'User') {
                        header('Location: user_dashboard.php');
                    } elseif ($role == 'Staff') {
                        header('Location: staff_dashboard.php');
                    } elseif ($role == 'Admin') {
                        header('Location: admin_dashboard.php');
                    }
                    exit();
                } else {
                    $_SESSION['error'] = "Incorrect password.";
                    header('Location: index.php');
                    exit();
                }
            } else {
                // The password is plaintext, compare directly
                if ($password === $user['password']) {
                    // Password is correct, set session variables
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_id'] = $user[$id_field]; // Use dynamic field name based on role
                    
                    // Redirect based on the role
                    if ($role == 'User') {
                        header('Location: user_dashboard.php');
                    } elseif ($role == 'Staff') {
                        header('Location: staff_dashboard.php');
                    } elseif ($role == 'Admin') {
                        header('Location: admin_dashboard.php');
                    }
                    exit();
                } else {
                    $_SESSION['error'] = "Incorrect password.";
                    header('Location: index.php');
                    exit();
                }
            }
        } else {
            $_SESSION['error'] = "User not found.";
            header('Location: index.php');
            exit();
        }
    } catch (PDOException $e) {
        // Log error and redirect with message
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error'] = "Database error. Please try again later.";
        header('Location: index.php');
        exit();
    }
}
