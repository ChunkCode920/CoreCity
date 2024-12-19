<?php
require_once './Control/MemberController.php';

$error_message = '';
$success_message = '';
$user_id = $_SESSION['user_id'];  // Get the currently logged-in user's ID
$controller = new MemberController();

// Check if user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($user_id)) {
    header('Location: index.php'); // Redirect to login if not logged in
    exit;
}

// Fetch user details from the database
require_once 'conn.php';  // Include the database connection
$stmt = $pdo->prepare("SELECT fullname, username, email, pfp FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_data) {
    $error_message = "Failed to fetch user data.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the delete request
    if (isset($_POST['delete_account'])) {
        // Call the deleteUser method
        if ($controller->deleteUser($user_id)) {
            session_destroy();  // Log the user out after account deletion
            $success_message = "Your account has been successfully removed.";
            header("Location: index.php");  // Redirect to login page
            exit();
        } else {
            $error_message = "Failed to delete your account. Please try again.";
        }
    } else {
        // If not deleting, update profile as usual
        $fullname = !empty($_POST['fullname']) ? htmlspecialchars(trim($_POST['fullname'])) : $user_data['fullname'];
        $username = !empty($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : $user_data['username'];
        $email = !empty($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : $user_data['email'];
        $password = !empty($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : null; // Only change if the password is provided

        // Handle profile picture upload
        $pfpPath = $user_data['pfp'];  // Default to current profile picture if no new picture is uploaded
        if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] == 0) {
            $profile_picture = $_FILES['pfp'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($profile_picture['type'], $allowed_types)) {
                $error_message = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            } elseif ($profile_picture['size'] > 10 * 1024 * 1024) {  // Max file size 10MB
                $error_message = "File size must not exceed 10MB.";
            } else {
                // File upload directory and filename
                $upload_dir = 'uploads/';
                $filename = uniqid() . '-' . basename($profile_picture['name']);
                $upload_file = $upload_dir . $filename;

                if (move_uploaded_file($profile_picture['tmp_name'], $upload_file)) {
                    $pfpPath = $upload_file;  // Store the new file path
                } else {
                    $error_message = "Failed to upload profile picture.";
                }
            }
        }

        // If there are no errors, proceed to update the user profile
        if (empty($error_message)) {
            $result = $controller->modUser($user_id, $fullname, $username, $email, $password, $pfpPath);

            if ($result) {
                $success_message = "Your profile has been updated successfully!";
            } else {
                $error_message = "Failed to update your profile. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">

</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Profile</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php elseif (!empty($success_message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="user_profile.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    value="<?php echo isset($user_data['fullname']) ? htmlspecialchars($user_data['fullname']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo isset($user_data['username']) ? htmlspecialchars($user_data['username']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo isset($user_data['email']) ? htmlspecialchars($user_data['email']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <div class="mb-3">
                                <label for="pfp" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="pfp" name="pfp" accept="image/*">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                        </form>

                        <div class="mt-3">
                            <!-- Trigger the delete account modal -->
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Delete Account
                            </button>
                            
                        </div>
                        <div class="mt-3">
                            <a href="user_dashboard.php" class="btn btn-dark w-100 mb-2">Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Removal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete your account? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- Form for deleting the user -->
                    <form method="POST" action="user_profile.php" style="display: inline;">
                        <input type="hidden" name="delete_account" value="1">
                        <button type="submit" class="btn btn-danger">Delete My Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
