<?php
// populate_times.php — Fetch available time slots for the selected staff and date

include 'conn.php';

// Get the staff ID and appointment date from the request
$staff_id = $_GET['staff_id']; // Staff ID from user selection
$appointment_date = $_GET['appointment_date']; // Selected date

// Query to fetch existing appointments for the selected staff on the selected date
$query = "SELECT appointment_date FROM appointments WHERE staff_id = :staff_id AND DATE(appointment_date) = :appointment_date AND status = 'confirmed'";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':staff_id' => $staff_id,
    ':appointment_date' => $appointment_date
]);

// Fetch all existing appointments for the selected staff and date
$existing_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define available time slots (assuming 9 AM to 5 PM working hours)
$available_times = [];
$start_time = strtotime('09:00 AM');
$end_time = strtotime('05:00 PM');
$interval = 30 * 60; // 30 minutes intervals

// Check for existing appointments to exclude those times
$occupied_times = array_map(function($appointment) {
    return date('H:i', strtotime($appointment['appointment_date'])); // Store in 24-hour format for comparison
}, $existing_appointments);

// Loop through the time slots
for ($time = $start_time; $time <= $end_time; $time += $interval) {
    $formatted_time = date('H:i', $time); // 24-hour format for comparison
    if (!in_array($formatted_time, $occupied_times)) {
        // Convert to 12-hour format for display
        $available_times[] = date('h:i A', $time); // 12-hour format for user selection
    }
}

// Output available time options in 12-hour format (for user selection)
foreach ($available_times as $time) {
    echo "<option value='" . $time . "'>" . $time . "</option>";
}
?>
