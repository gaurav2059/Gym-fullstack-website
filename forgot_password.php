<?php
include 'connection.php';
session_start();

$reset_message = '';
$reset_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    if (empty($email) || empty($username)) {
        $reset_error = "Please enter both email and username.";
    } else {
        // Check if the user exists
        $sql = "SELECT id FROM users WHERE email = ? AND username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            die('Prepare failed: ' . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        $execute = mysqli_stmt_execute($stmt);

        if (!$execute) {
            die('Execute failed: ' . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $token = bin2hex(random_bytes(50));
            $user_id = mysqli_fetch_assoc($result)['id'];
            $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if (!$stmt) {
                die('Prepare failed: ' . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, "iss", $user_id, $token, $expiry);
            $execute = mysqli_stmt_execute($stmt);

            if (!$execute) {
                die('Execute failed: ' . mysqli_stmt_error($stmt));
            }

            $reset_link = "http://yourdomain.com/reset_password.php?token=$token";
            $subject = "Password Reset Request";
            $message = "Click the following link to reset your password: $reset_link";
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                $reset_message = "A password reset link has been sent to your email.";
            } else {
                $reset_error = "Failed to send the reset email. Please try again later.";
            }
        } else {
            $reset_error = "No user found with this email and username.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if (!empty($reset_error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($reset_error); ?></div>
    <?php elseif (!empty($reset_message)): ?>
        <div class="success-message"><?php echo htmlspecialchars($reset_message); ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
