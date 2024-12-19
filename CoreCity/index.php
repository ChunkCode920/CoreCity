<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="images/barbell-icon-test.png" type="image/png">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="wrapper">
        <form action="process_login.php" method="POST">
            <h1>Login</h1> 
            <div class="input-box">
                <input type="text" name="username" placeholder="Username">
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <div class="input-box">
                <label for="role" class="form-label">Select Role</label>
                    <select class="form-select" id="role" name="role" required>
                    <option value="" disabled selected>Select a test</option>
                    <option value="User">User</option>
                    <option value="Staff">Staff</option>
                    <option value="Admin">Admin</option>
                    </select>
            </div>

            <button type="submit" class="btnb">Login</button>

            <div class="register-link">
                <p>Don't have an account? <a href="registration.php">Register</a></p>
            </div>
        </form>
    </div>
    
</body>
</html>