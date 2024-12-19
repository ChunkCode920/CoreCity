<?php
// Get the user ID from the URL (for editing)
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query to get the current user data
    $sql = "SELECT * FROM Users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Pre-fill the form with the current user's data
        $username = $user['username'];
        $fullname = $user['fullname'];
        $email = $user['email'];
        $role = $user['role'];
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "User ID not provided.";
    exit;
}
?>

<form action="edit.php" method="POST">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>

    <label for="fullname">Full Name:</label>
    <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label for="role">Role:</label>
    <select name="role" id="role">
        <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
        <option value="staff" <?php echo ($role == 'staff') ? 'selected' : ''; ?>>Staff</option>
        <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
    </select>

    <button type="submit">Update User</button>
</form>
