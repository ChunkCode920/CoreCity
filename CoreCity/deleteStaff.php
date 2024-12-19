<?php
require_once './Control/MemberController.php';
$error_message = '';
$success_message = '';
$name = $price = $description = '';
$staff_id = null;
$staff = null;

$controller = new MemberController();

if (isset($_GET['id'])) {
    
    $staff_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    
    
    // Fetch user details from the controller
    $staff = $controller->viewStaff($staff_id);
    
    
    // If user found, store user data in variables
    if ($staff) {
        $staff_name = $staff['fullname'];
        $joined = $staff['joined_on'];
        $email = $staff['email'];
    } else {
        // If no user found, show error message
        $error_message = "Staff not found.";
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user ID from POST request
    $staff_id = filter_var($_POST['staff_id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete the user using the controller
    if ($controller->deleteStaff($staff_id)) {  // Corrected method to use $user_id
        $success_message = "User has been successfully removed!";
        header("Location: admin_staff_list.php");
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
    <title>Delete This Staff Member</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">

</head>
<body class="bg-light"> 
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h1 class="card-title mb-0">Staff Member Removal</h1>
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

                <?php if ($staff): ?> <!-- Check if $user is found -->
                    <p><strong>ID:</strong> <?php echo htmlspecialchars($staff['staff_id']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($staff['fullname']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($staff['email']); ?></p>
                    <p><strong>Join Date:</strong> <?php echo htmlspecialchars($staff['joined_on']); ?></p>
                <?php endif; ?>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash-alt"></i> Remove
                </button>
                <a href="admin_staff_list.php" class="btn btn-secondary">Back to Staff List</a>
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
                    Are you sure you want to remove this staff member?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="deleteStaff.php" style="display: inline;">
                        <!-- Use the correct user_id for deletion -->
                        <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staff_id); ?>">
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
