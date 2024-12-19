<?php
// Include config file and start session
include 'connection.php';
session_start();

// Initialize variables
$old_password_err = '';
$new_password_err = '';
$confirm_password_err = '';
$change_password_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in and is a user role
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $old_password = trim($_POST["old_pass"]);
        $new_password = trim($_POST["new_pass"]);
        $confirm_password = trim($_POST["confirm_pass"]);

        // Validate old password
        if (empty($old_password)) {
            $old_password_err = "Please enter your old password.";
        } else {
            $sql = "SELECT password FROM users WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $hashed_password);
                mysqli_stmt_fetch($stmt);

                if (!password_verify($old_password, $hashed_password)) {
                    $old_password_err = "Invalid password.";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Validate new password
        if (empty($new_password)) {
            $new_password_err = "Please enter the new password.";
        } elseif (strlen($new_password) < 8) {
            $new_password_err = "Password must be at least 8 characters long.";
        }

        // Validate confirm password
        if (empty($confirm_password)) {
            $confirm_password_err = "Please confirm the password.";
        } elseif ($new_password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }

        // Check input errors before updating the database
        if (empty($old_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET password = ? WHERE id = ?";
            $stmt_update = mysqli_prepare($conn, $sql_update);

            if ($stmt_update) {
                mysqli_stmt_bind_param($stmt_update, "si", $hashed_new_password, $user_id);

                if (mysqli_stmt_execute($stmt_update)) {
                    $change_password_success = "Password updated successfully.";
                    header("Location: profile.php"); // Redirect to user profile
                    exit();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt_update);
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group .help-block {
            color: red;
            font-size: 0.875em;
        }
        .btn {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (empty($change_password_success)) { ?>
            <div class="signup_form">
                <h2>Change Password</h2>
                <form id="modify-password" method="POST">
                    <div class="form-group">
                        <label>Old Password</label>
                        <input type="password" name="old_pass" class="form-control"
                               placeholder="Enter Old Password" required/>
                        <span class="help-block"><?php echo $old_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_pass" class="form-control"
                               placeholder="Enter New Password" required/>
                        <span class="help-block"><?php echo $new_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_pass" class="form-control"
                               placeholder="Confirm New Password" required/>
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <input type="submit" name="submit" class="btn" value="Submit"/>
                </form>
            </div>
        <?php } else {
            echo "<div class='alert alert-success'>$change_password_success</div>";
        } ?>
    </div>
</body>
</html>
