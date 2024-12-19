<?php
// populate_staff.php — Fetch staff members for the user to choose from

include 'conn.php';

// Query to get staff members
$query = "SELECT staff_id, fullname FROM staff"; // Assuming 'staff' table has 'staff_id' and 'name'
$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetch the results and output them as options for the dropdown
$staff_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if any staff members are found
if ($staff_members) {
    foreach ($staff_members as $staff) {
        echo "<option value='" . $staff['staff_id'] . "'>" . htmlspecialchars($staff['fullname']) . "</option>";
    }
} else {
    echo "<option value='' disabled>No staff available</option>";
}
?>
