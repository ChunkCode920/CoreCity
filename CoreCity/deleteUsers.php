<?php
require_once './Control/MemberController.php';
$error_message = '';
$success_message = '';
$name = $price = $description = '';
$user_id = null;
$user = null;

$controller = new MemberController();

if (isset($_GET['id'])) {
    // Get and sanitize the user ID from the URL
    $user_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    
    
    // Fetch user details from the controller
    $user = $controller->viewUser($user_id);
    
    
    // If user found, store user data in variables
    if ($user) {
        $user_name = $user['fullname'];
        $joined = $user['joined_on'];
        $email = $user['email'];
    } else {
        // If no user found, show error message
        $error_message = "User not found.";
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user ID from POST request
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete the user using the controller
    if ($controller->deleteUser($user_id)) {  // Corrected method to use $user_id
        $success_message = "User has been successfully removed!";
        header("Location: admin_users_list.php");
        exit();
    } else {
        $error_message = "Failed to remove user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete This User</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">

</head>
<body class="bg-light"> 
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h1 class="card-title mb-0">User Removal</h1>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php elseif ($success_message): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($user): ?> <!-- Check if $user is found -->
                    <p><strong>ID:</strong> <?php echo htmlspecialchars($user['user_id']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Join Date:</strong> <?php echo htmlspecialchars($user['joined_on']); ?></p>
                <?php endif; ?>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash-alt"></i> Remove
                </button>
                <a href="admin_users_list.php" class="btn btn-secondary">Back to Users List</a>
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
                    Are you sure you want to remove this user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="deleteUsers.php" style="display: inline;">
                        <!-- Use the correct user_id for deletion -->
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                        <button type="submit" class="btn btn-danger">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
