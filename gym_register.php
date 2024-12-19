<?php
session_start();
include 'connection.php';

// Fetch schedule options from the database for the form
$schedules_sql = "SELECT id, start_time, end_time FROM schedules ORDER BY start_time";
$schedules_result = mysqli_query($conn, $schedules_sql);

// Define membership prices
$membership_prices = [
    'Basic' => 1500,
    'Premium' => 4000,
    'Standard' => 6000
];

// Define promo codes and their discounts
$promo_codes = [
    'DISCOUNT10' => 0.10, // 10% discount
    'DISCOUNT20' => 0.20  // 20% discount
];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $membership_type = $_POST['membership_type'];
    $start_date = date('Y-m-d');
    $schedule_id = $_POST['schedule_id'];
    $payment_method = $_POST['payment_method'];
    $promo_code = $_POST['promo_code'];
    $amount = $_POST['amount']; // Amount already discounted by JavaScript

    // Determine the duration of the membership plan
    switch ($membership_type) {
        case 'Basic':
            $duration = 30; // 1 month
            break;
        case 'Premium':
            $duration = 90; // 3 months
            break;
        case 'Standard':
            $duration = 180; // 6 months
            break;
        default:
            $duration = 0;
    }

    // Calculate the end date
    $end_date = date('Y-m-d', strtotime("+$duration days", strtotime($start_date)));

    // Insert the data into the membership_requests table
    $sql = "
        INSERT INTO membership_requests (
            user_id, phone, address, age, gender, membership_type, 
            start_date, end_date, schedule_id, payment_method, amount, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt, 'issssssssis',
        $user_id, $phone, $address, $age, $gender, $membership_type,
        $start_date, $end_date, $schedule_id, $payment_method, $amount
    );

    if (mysqli_stmt_execute($stmt)) {
        // Redirect to membership.php with success message
        $message = "Your membership has been successfully registered";
        $status = "success";
        header("Location: membership.php?message=" . urlencode($message) . "&status=" . $status);
        exit();
    
        
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Registration</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>



<form action="gym_register.php" method="POST">
<h2>Register for a Gym Membership</h2>
<br>
    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" required>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" required>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>

    <label for="membership_type">Membership Type:</label>
    <select id="membership_type" name="membership_type" onchange="updateAmount()" required>
        <option value="Basic">Basic (1 Month)</option>
        <option value="Premium">Premium (3 Months)</option>
        <option value="Standard">Standard (6 Months)</option>
    </select>

    <label for="promo_code">Promo Code:</label>
    <input type="text" id="promo_code" name="promo_code" oninput="applyPromoCode()">

    <label for="schedule_id">Preferred Schedule:</label>
    <select id="schedule_id" name="schedule_id" required>
        <?php
        if (mysqli_num_rows($schedules_result) > 0) {
            while ($row = mysqli_fetch_assoc($schedules_result)) {
                echo '<option value="' . $row['id'] . '">Time: ' . $row['start_time'] . ' - ' . $row['end_time'] . '</option>';
            }
        } else {
            echo '<option value="">No schedules available</option>';
        }
        ?>
    </select>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="Cash">Cash</option>
        <option value="Online">Online</option>
    </select>

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" readonly required>

    <button type="submit">Register</button>
</form>


<script>
    const membershipPrices = <?php echo json_encode($membership_prices); ?>;

    function updateAmount() {
        const membershipType = document.getElementById('membership_type').value;
        let amount = membershipPrices[membershipType];
        document.getElementById('amount').value = amount.toFixed(2);
        applyPromoCode(); // Apply promo code if any
    }

    function applyPromoCode() {
        const promoCode = document.getElementById('promo_code').value;
        const membershipType = document.getElementById('membership_type').value;
        let amount = membershipPrices[membershipType];

        if (promoCode) {
            // Make an AJAX request to fetch the discount percentage
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_discount.php?promocode=' + encodeURIComponent(promoCode), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const discountPercentage = response.discount_percentage;

                    // Apply discount
                    if (discountPercentage > 0) {
                        amount -= amount * (discountPercentage / 100);
                    }

                    // Update the amount field
                    document.getElementById('amount').value = amount.toFixed(2);
                }
            };
            xhr.send();
        } else {
            // No promo code, reset the amount
            document.getElementById('amount').value = amount.toFixed(2);
        }
    }

    // Initialize the amount when the page loads
    document.addEventListener('DOMContentLoaded', updateAmount);
</script>

</body>
</html>
