<?php session_start();
include 'connection.php';

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
    $amount = $_POST['amount']; // Amount is already calculated client-side
    $promo_code = $_POST['promo_code']; // Get the promo code

    // Promo code validation and amount adjustment
    if (!empty($promo_code)) {
        if ($promo_code === 'DISCOUNT10') {
            $amount = $amount * 0.90; // 10% discount
        } else if ($promo_code === 'DISCOUNT20') {
            $amount = $amount * 0.80; // 20% discount
        } else {
            echo "Invalid promo code.";
            exit;
        }
    }

    $amount = round($amount, 2); // Round the amount to 2 decimal places

    // Determine the duration of the membership plan
    switch ($membership_type) {
        case 'Basic Member':
            $duration = 30;
            break;
        case 'Premium Member':
            $duration = 90;
            break;
        case 'Standard Member':
            $duration = 180;
            break;
        default:
            $duration = 0;
    }

    $end_date = date('Y-m-d', strtotime("+$duration days", strtotime($start_date)));

    $sql = "
        INSERT INTO members (
            user_id, phone, address, age, gender, membership_type, 
            start_date, end_date, schedule_id, payment_method, amount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt, 'issssssssis',
        $user_id, $phone, $address, $age, $gender, $membership_type,
        $start_date, $end_date, $schedule_id, $payment_method, $amount
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "Membership registered successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

$schedules_sql = "SELECT id, start_time, end_time FROM schedules ORDER BY start_time";
$schedules_result = mysqli_query($conn, $schedules_sql);

mysqli_close($conn);
?>
