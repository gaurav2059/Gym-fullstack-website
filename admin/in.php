
<?php
 session_start();

  
 // Check if admin is logged in
 if (!isset($_SESSION['admin_id'])) {
     header("Location: index.php");
     exit();
 }
// session_start();
// include 'connection.php'; // Adjust the path as per your actual file location

if(isset($_POST['Login'])){
    // $username = mysqli_real_escape_string($conn, $_POST['username']);
    // $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verify password (replace with actual password verification method if hashed)
            if ($row['password'] == $password) {
                // $_SESSION['admin_name'] = $row['name'];
                // $_SESSION['admin_email'] = $row['admin'];
                // $_SESSION['admin_id'] = $row['id'];
                header('Location: admin_pannel.php'); // Redirect to admin panel
                exit;
            } else {
                echo "<script>alert('Incorrect password');</script>";
            }
        } else {
            echo "<script>alert('Admin not found');</script>";
        }
    } else {
        echo "<script>alert('Query failed');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .forgot-password {
            margin-top: 10px;
            text-align: right;
        }

        .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<!-- 
    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
        <h2>Admin Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="Login">Login</button>

        <div class="forgot-password">
            <a href="#">Forgot Password?</a>
        </div>
    </form>
</body>
</html> -->





            