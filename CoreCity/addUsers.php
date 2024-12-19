<?php
require_once './Control/MemberController.php';

$controller = new MemberController();
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Initialize error message
    $error_message = '';

    // Handle profile picture upload
    $pfpPath = null;  // Default to null if no file is uploaded
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
                $pfpPath = $upload_file;  // Store the file path
            } else {
                $error_message = "Failed to upload profile picture.";
            }
        }
    }

    // If there are no errors, proceed to add the user
    if (empty($error_message)) {
        if ($controller->addUser($fullname, $username, $email, $password, $pfpPath)) {
            header("Location: admin_users_list.php");
            die();
        } else {
            $error_message = "Failed to add user. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">

</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Add User</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="addUsers.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" required
                                    value="<?php echo isset($fullname) ? htmlspecialchars($fullname) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required
                                    value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="pfp" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="pfp" name="pfp" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add User</button>
                        </form>
                        <div class="mt-3">
                                <a href="admin_users_list.php" class="btn btn-secondary w-100">Back to Users List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
