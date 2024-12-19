<?php
// Include the database connection file
include('conn.php');

// Check if a user_id is provided via the query string
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Prepare the DELETE SQL statement
    $sql = "DELETE FROM Users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);

    // Bind the parameter and execute the statement
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect to the full dashboard (staff_dashboard.php) and load the users section
        header("Location: staff_dashboard.php?page=users&message=User%20deleted%20successfully");
        exit;  // Always call exit after a redirect to ensure no further code is executed
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "No user ID provided.";
}

// Close the database connection
$pdo = null;
?>
