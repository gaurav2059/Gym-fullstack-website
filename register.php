<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/register.css">
    <style>  
    </style>
</head>
<body>
    <div class="login_container">
    <form action="registration.php" method="POST" onsubmit="return validateForm()">
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="message show">
                    <span>' . $msg . '
                        <i class="bx bx-x-circle" onclick="this.parentElement.style.display=\'none\'"></i>
                    </span>
                </div>';
            }
        }
        ?>
            <h1>Register</h1>
            <label for="name">Fullame:</label>
            <input type="text" id="name" name="fullname" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
              
            <label for="email">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="cpassword">Confirm Password:</label>
            <input type="password" id="cpassword" name="cpassword" required>
            
            <button type="submit" name="submit_btn">Register Now</button>
            <p>Already have an account? <a href="login.php" class="register-link">Login</a></p>
        </form>
    
    </div>
    <script src="js/register.js"></script>
</body>
</html>