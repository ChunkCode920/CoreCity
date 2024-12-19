<?php

session_start();
require_once 'conn.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit a report";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    
    if (!empty($message)) {
        $user_id = $_SESSION['user_id'];
        $created_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO reports (user_id, message, created_at)
                 VALUES (:user_id, :message, :created_at)";
        
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Set success message in session
            $_SESSION['report_success'] = "Your report has been successfully submitted.";
        } else {
            // Set failure message if insertion fails
            $_SESSION['report_error'] = "There was an error submitting your report. Please try again later.";
        }
    } else {
        $_SESSION['report_error'] = "Please provide a valid message.";
    }

    // Redirect to the main report page
    header("Location: user_dashboard.php?page=report");
    exit; // Always call exit after header redirect
}
?>
