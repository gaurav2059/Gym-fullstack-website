<?php
include 'connection.php';

if (isset($_POST['submit_btn'])) {
    $filter_fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
    $fullname = mysqli_real_escape_string($conn, $filter_fullname);
    
    $filter_username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $username = mysqli_real_escape_string($conn, $filter_username);

    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = mysqli_real_escape_string($conn, $filter_email);

    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'") or die('query failed');

    if (mysqli_num_rows($select_user) > 0) {
        $message[] = 'User already exists';
    } else {
        if ($password != $cpassword) {
            $message[] = 'Passwords do not match';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            mysqli_query($conn, "INSERT INTO users (fullname, email, username, password) VALUES ('$fullname', '$email', '$username', '$hashed_password')") or die('Query failed');
            $message[] = 'Registered successfully';
            header('location: login.php');
            exit;
        }
    }
}
?>
