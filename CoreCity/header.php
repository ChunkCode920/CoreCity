<?php 
// Start the session to get the logged-in user's data
session_start();

// Initialize default profile picture
$profile_picture = 'default-pfp.jpg';  // Default profile picture

// Check if the user is logged in and the session has the user data
if(!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
}


$role = strtolower(trim($_SESSION['role']));



// If a user is logged in (either user, staff, or admin)
if ($role) {
    require_once 'conn.php';  // Include your database connection

    // Prepare the query based on role
    if ($role === 'user') {
        $stmt = $pdo->prepare("SELECT pfp FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
    } elseif ($role === 'staff') {
        $stmt = $pdo->prepare("SELECT pfp FROM staff WHERE staff_id = :staff_id");
        $stmt->bindParam(':staff_id', $id, PDO::PARAM_INT);
    } elseif ($role === 'admin') {
        $stmt = $pdo->prepare("SELECT pfp FROM admin WHERE admin_id = :admin_id");
        $stmt->bindParam(':admin_id', $id, PDO::PARAM_INT);
    }

    // Execute the query
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user has a profile picture
    if ($user && !empty($user['pfp'])) {
        // Set the path to the profile picture in the 'uploads' directory
        $profile_picture = '' . $user['pfp'];
    }
}
?>

<header class="header">
    <div class="header-content">
        <h1>Core City Fitness</h1>

        <div class="header-right">
            <!-- Home link based on your role -->
            <?php if ($role == 'user'): ?>
                <a href="user_dashboard.php" class="header-link">Home</a>
            <?php elseif ($role == 'staff'): ?>
                <a href="staff_dashboard.php" class="header-link">Home</a>
            <?php elseif ($role == 'admin'): ?>
                <a href="admin_dashboard.php" class="header-link">Home</a>
            <?php endif; ?>
            
            <!-- Editing Profile link based on your role -->
            <?php if ($role == 'user'): ?>
                <a href="user_profile.php" class="header-link">My Profile</a>
            <?php elseif ($role == 'staff'): ?>
                <a href="staff_profile.php" class="header-link">My Profile</a>
            <?php elseif ($role == 'admin'): ?>
                <a href="admin_profile.php" class="header-link">My Profile</a>
            <?php endif; ?>

            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="PFP">
            </div>
        </div>
    </div>
</header>