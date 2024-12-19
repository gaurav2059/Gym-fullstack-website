<?php
// Connection to the database
include "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $membership_type = $_POST['membership_type'];
    $start_date = $_POST['start_date'];
    $schedule_id = $_POST['schedule_id'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];

    // Calculate end_date based on membership_type
    switch ($membership_type) {
        case 'Basic Member':
            $duration = 30; // 1 month
            break;
        case 'Premium Member':
            $duration = 90; // 3 months
            break;
        case 'Standard Member':
            $duration = 180; // 6 months
            break;
        default:
            $duration = 0;
    }

    $end_date = date('Y-m-d', strtotime("+$duration days", strtotime($start_date)));

    // Check if the user exists
    $check_user_sql = "SELECT id FROM users WHERE id = '$user_id'";
    $user_result = mysqli_query($conn, $check_user_sql);

    if (mysqli_num_rows($user_result) > 0) {
        // User exists, proceed to insert the membership
        $insert_sql = "
            INSERT INTO membership_requests (user_id, phone, address, age, gender, membership_type, start_date, end_date, schedule_id, payment_method, amount) 
            VALUES ('$user_id', '$phone', '$address', '$age', '$gender', '$membership_type', '$start_date', '$end_date', '$schedule_id', '$payment_method', '$amount')
        ";
        if (mysqli_query($conn, $insert_sql)) {
            echo "New membership added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: User with ID $user_id does not exist.";
    }

    mysqli_close($conn);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Membership</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Membership</h1>
        <form method="POST" action="add_member.php">
            <label for="user_id">User ID:</label>
            <input type="text" name="user_id" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" required>

            <label for="address">Address:</label>
            <input type="text" name="address" required>

            <label for="age">Age:</label>
            <input type="number" name="age" required>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="membership_type">Membership Type:</label>
            <select name="membership_type" required>
                <option value="Basic Member">Basic Member (1 Month)</option>
                <option value="Premium Member">Premium Member (3 Months)</option>
                <option value="Standard Member">Standard Member (6 Months)</option>
            </select>

            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>

            <label for="schedule_id">Schedule ID:</label>
            <input type="text" name="schedule_id" required>

            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" required>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="Cash">Cash</option>
                <option value="Mobile Payment">Mobile Payment</option>
            </select>

            <label for="amount">Amount:</label>
            <input type="number" name="amount" required>

            <button type="submit">Add Membership</button>
        </form>
    </div>
</body>
</html>
