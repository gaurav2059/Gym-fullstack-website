<?php
// Include connection file and start session
include 'connection.php';
session_start();

// Redirect if user is not logged in or is not a 'user'
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit; // Ensure script stops execution after redirection
}

// Include header file containing styles and other header elements
include 'header.php';

// Check if 'user' parameter is set in GET request
if (isset($_GET['user'])) {
    $user_id = $_GET['user'];

    // Prepare SQL query to fetch user data
    $sql = "SELECT * FROM users WHERE id = '{$user_id}'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch user data
        $row = mysqli_fetch_assoc($result);

        // Initialize variables for messages
        $error_message = '';

        // Check if form is submitted for update
        if (isset($_POST['modify_profile'])) {
            $fullname = mysqli_real_escape_string($conn, $_POST['fullname']); // Escape input for security

            // Update user's full name in the database
            $sql_update = "UPDATE users SET fullname = '{$fullname}' WHERE id = '{$user_id}'";

            if (mysqli_query($conn, $sql_update)) {
                $_SESSION['success_message'] = "User details updated successfully.";
                // Redirect to prevent form resubmission on page refresh
                header("Location: {$_SERVER['REQUEST_URI']}");
                exit;
            } else {
                $error_message = "Error updating user details: " . mysqli_error($conn);
            }
        }

        // Check if there is a success message to display
        $success_message = '';
        if (isset($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']); // Clear session message after displaying
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Profile</title>
    <style>
        /* CSS for Modify Profile form */
        #user_profile-content {
            margin-top: 20px;
        }

        .container {
            width: 80%;
            margin:100px;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .signup_form {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .signup_form h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div id="user_profile-content">
        <div class="container">
            <div class="row">
                <div>
                    <!-- Form -->
                    <form id="modify-user" method="POST">
                        <div class="signup_form">
                            <h2>Modify Profile</h2>
                            <?php if (!empty($success_message)): ?>
                                <div id="success-message" class="alert alert-success"><?php echo $success_message; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="fullname" class="form-control fullname"
                                       placeholder="Full Name" value="<?php echo htmlspecialchars($row['fullname']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="Username"
                                       value="<?php echo htmlspecialchars($row['username']); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Email"
                                       value="<?php echo htmlspecialchars($row['email']); ?>" disabled>
                            </div>
                            <input type="submit" name="modify_profile" class="btn" value="Modify"/>
                        
                        </div>
                    </form>
                    <!-- /Form -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to hide success message after 5 seconds
        window.onload = function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 5000); // 5 seconds
            }
        };
    </script>
</body>
</html>
<?php
    } else {
        echo "No user found.";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
