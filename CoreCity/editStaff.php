<?php
require_once './Control/MemberController.php';
require_once 'conn.php';  // Include the database connection

$controller = new MemberController();
$error_message = '';
$success_message = '';
$staff_id = $_GET['id'];  // Get the staff_id from the query parameter

// Fetch user details from the database
$stmt = $pdo->prepare("SELECT staff_id, fullname, username, email, pfp FROM staff WHERE staff_id = :staff_id");
$stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
$stmt->execute();
$staff_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$staff_data) {
    $error_message = "Staff not found.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = !empty($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : null; // Optional password update

    // Handle profile picture upload
    $pfpPath = $staff_data['pfp'];  // Default to current profile picture if no new one is uploaded
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
        $result = $controller->modStaff($staff_id, $fullname, $username, $email, $password, $pfpPath);

        if ($result) {
            $success_message = "Staff profile updated successfully!";
        } else {
            $error_message = "Failed to update staff profile. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h2 class="mb-0">Edit Staff Member</h2>
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

                        <form method="POST" action="editStaff.php?id=<?php echo $staff_id; ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" required
                                    value="<?php echo isset($staff_data['fullname']) ? htmlspecialchars($staff_data['fullname']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required
                                    value="<?php echo isset($staff_data['username']) ? htmlspecialchars($staff_data['username']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="<?php echo isset($staff_data['email']) ? htmlspecialchars($staff_data['email']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password (Leave empty to keep current)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <div class="mb-3">
                                <label for="pfp" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="pfp" name="pfp" accept="image/*">
                                <?php if (!empty($staff_data['pfp'])): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo htmlspecialchars($staff_data['pfp']); ?>" alt="Current Profile Picture" class="img-fluid" style="max-width: 200px;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-warning w-100">Update Staff Member</button>
                        </form>
                        <div class="mt-3">
                                <a href="admin_staff_list.php" class="btn btn-secondary w-100">Back to Staff List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>