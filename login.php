<?php
include 'connection.php';
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Fetch user record based on email
    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

    if (mysqli_num_rows($select_user) > 0) {
        $row = mysqli_fetch_assoc($select_user);

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables based on user type
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id']; // Adjust as per your database structure
                header("Location: admin/admin_panel.php");
                exit;
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['username'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id']; // Adjust as per your database structure
                header('Location: index.php');
                exit;
            }
        } else {
            $_SESSION['login_error'] = 'Incorrect email or password';
        }
    } else {
        $_SESSION['login_error'] = 'Incorrect email or password';
    }
}

// Clear error message after displaying
if (isset($_SESSION['login_error'])) {
    $message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <style>
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: inline-block;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Now</h2>
        <?php if (isset($message)): ?>
            <div class="error-message" id="error-message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="login" value="Login">
        </form>
        <p>Do not have an account? <a href="register.php" class="register-link">Register now</a></p>
        <a href="forgot_password.php" class="register-link">Forgot password?</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                // Hide the error message after 5 seconds
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>
</html>
