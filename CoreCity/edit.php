<?php
// Include the database connection
include('conn.php');

// Check if a user_id is provided in the URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details from the database
    $sql = "SELECT * FROM Users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit;
    }
} else {
    echo "User ID not provided.";
    exit;
}

?>

<!-- Form to edit user -->
<form action="edit.php" method="POST">
    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

    <label for="username">Username:</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

    <label for="fullname">Full Name:</label>
    <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

    <label for="role">Role:</label>
    <select name="role">
        <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
        <option value="staff" <?php echo ($user['role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
        <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
    </select>

    <button type="submit">Update User</button>
</form>

<?php
// If form is submitted, update the user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Prepare SQL to update user details
    $sql = "UPDATE Users SET username = :username, fullname = :fullname, email = :email, role = :role WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters and execute
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to the dashboard users list with a success message
        header("Location: staff_dashboard.php?page=users&message=User%20updated%20successfully");
        exit; // Prevent further script execution
    } else {
        echo "Error updating user.";
    }
}

// Close database connection
$pdo = null;
?>
