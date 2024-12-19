<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Today</title>
    <link rel="stylesheet" href="login.css"> <!-- Keep your stylesheet -->
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">
    <!-- Bootstrap 4/5 CDN for Alert -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <form action="process_registration.php" method="POST" enctype="multipart/form-data">
            <h1>Registration</h1>

            <!-- Display error message if exists -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?> <!-- Clear the error message after displaying it -->
            <?php endif; ?>

            <div class="input-box">
                <input type="text" name="fullname" placeholder="Full Name" required>
            </div>

            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="input-box">
                <input type="text" name="email" placeholder="Email Address" required>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <div class="profile">
                <input type="file" name="pfp" accept="image/*" required>
            </div>

            <button type="submit" class="btnb">Register</button>

            <div class="register-link">
                <p>Have an account? <a href="index.php">Login</a></p>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
