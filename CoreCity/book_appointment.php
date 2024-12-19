<?php
// book_appointment.php — Handle the booking of an appointment

include 'conn.php';
session_start();  // Start the session to access session variables

// Check if the user is logged in (user_id is stored in the session)
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User is not logged in.']);
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $staff_id = $_POST['staff_id'];
    $appointment_date = $_POST['appointment_date'] . ' ' . $_POST['time']; // Combine date and time
    $status = 'confirmed'; // **Update to confirmed from the start**

    // Ensure the appointment date and time are in the correct format (Y-m-d H:i:s)
    // This converts the combined date-time string into a proper format
    $formatted_appointment_date = date('Y-m-d H:i:s', strtotime($appointment_date));

    // **IMPORTANT**: Check if the appointment time is already taken for the same staff and exact datetime
    $query = "SELECT appointment_id FROM appointments 
              WHERE staff_id = :staff_id 
              AND appointment_date = :appointment_date 
              AND status = 'confirmed'";  // Only check confirmed appointments
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':staff_id' => $staff_id,
        ':appointment_date' => $formatted_appointment_date
    ]);

    // If the appointment time is already taken, send an error response
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Sorry, this time slot is already taken. Please select a different time.']);
        exit();
    }

    // If the time slot is available, insert the new appointment with 'confirmed' status
    try {
        $query = "INSERT INTO appointments (user_id, staff_id, appointment_date, status, created_at, updated_at) 
                  VALUES (:user_id, :staff_id, :appointment_date, :status, NOW(), NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':user_id' => $user_id,
            ':staff_id' => $staff_id,
            ':appointment_date' => $formatted_appointment_date,
            ':status' => $status  // **Set status to 'confirmed' here**
        ]);

        // Send a success response
        echo json_encode(['status' => 'success', 'message' => 'Appointment successfully booked!']);
        exit();

    } catch (PDOException $e) {
        // Handle errors and send an error response
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
}
?>
