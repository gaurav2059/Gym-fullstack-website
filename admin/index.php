<?php
include 'connection.php';
session_start();

$message = []; // Initialize the message array

$hostname = "http://localhost/gymweb"; // Correct base URL

if (isset($_POST['login'])) {
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = mysqli_real_escape_string($conn, $filter_email);

    $filter_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $password = mysqli_real_escape_string($conn, $filter_password);

    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'") or die('query failed');

    if (mysqli_num_rows($select_user) > 0) {
        $row = mysqli_fetch_assoc($select_user);
        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = 'admin'; // Set appropriate admin identifier here
            header("Location: admin_pannel.php");
            exit;
        } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header("Location: {$hostname}/index.php");
            exit;
        } else {
            $message[] = 'Incorrect email or password';
        }
    } else {
        $message[] = 'Incorrect email or password';
    }
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
</head>
<body>
    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = document.getElementById('errorModal');
                    var span = document.getElementsByClassName('close')[0];
                    modal.style.display = 'block';
                    document.getElementById('modal-message').innerText = '$msg';
                    span.onclick = function() {
                        modal.style.display = 'none';
                    }
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    }
                });
            </script>";
        }
    }
    ?>
    <div class="login-container">
        <h2>Login Now</h2>
        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="login" value="Login">
        </form>
        <p>Do not have an account? <a href="register.php" class="register-link">Register now</a></p>
    </div>

    <!-- The Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-message"></p>
        </div>
    </div>
</body>
</html>
