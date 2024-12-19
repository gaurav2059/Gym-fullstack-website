<?php
include 'connection.php';
session_start();

$update_message = '';
$update_error = '';
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST["new_pass"]);
    $confirm_password = trim($_POST["confirm_pass"]);

    // Validate new password
    if (empty($new_password)) {
        $update_error = "Please enter the new password.";
    } elseif (strlen($new_password) < 8) {
        $update_error = "Password must be at least 8 characters long.";
    } elseif ($new_password != $confirm_password) {
        $update_error = "Passwords do not match.";
    } else {
        // Check if the token is valid
        $sql = "SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()";
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            die('Prepare failed: ' . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $token);
        $execute = mysqli_stmt_execute($stmt);

        if (!$execute) {
            die('Execute failed: ' . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user_id = mysqli_fetch_assoc($result)['user_id'];
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if (!$stmt) {
                die('Prepare failed: ' . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, "si", $hashed_new_password, $user_id);
            $execute = mysqli_stmt_execute($stmt);

            if (!$execute) {
                die('Execute failed: ' . mysqli_stmt_error($stmt));
            }

            $update_message = "Password updated successfully.";
            mysqli_stmt_close($stmt);

            // Delete the token after successful password update
            $sql = "DELETE FROM password_resets WHERE token = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

        } else {
            $update_error = "Invalid or expired token.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <?php if (!empty($update_error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($update_error); ?></div>
    <?php elseif (!empty($update_message)): ?>
        <div class="success-message"><?php echo htmlspecialchars($update_message); ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="new_pass">New Password:</label>
        <input type="password" id="new_pass" name="new_pass" required>
        <label for="confirm_pass">Confirm New Password:</label>
        <input type="password" id="confirm_pass" name="confirm_pass" required>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
