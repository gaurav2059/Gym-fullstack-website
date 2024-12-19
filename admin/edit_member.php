<?php
include 'connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $membership_type = $_POST['membership_type'];
    $start_date = $_POST['start_date'];
    $schedule_id = $_POST['schedule_id'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];

    // Calculate end_date based on membership type
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

    // Update the member in the database
    $sql = "
        UPDATE membership_requests SET
            phone = ?, address = ?, age = ?, gender = ?, membership_type = ?,
            start_date = ?, end_date = ?, schedule_id = ?, payment_method = ?, amount = ?
        WHERE id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssisssssisi', $phone, $address, $age, $gender, $membership_type, $start_date, $end_date, $schedule_id, $payment_method, $amount, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Member updated successfully!";
        header("Location: members.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

// Fetch member details for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM membership_requests WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $member = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    header("Location: member.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Edit Member</h2>

<form action="edit_member.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?php echo $member['phone']; ?>" required>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?php echo $member['address']; ?>" required>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="<?php echo $member['age']; ?>" required>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
        <option value="Male" <?php if ($member['gender'] == 'Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if ($member['gender'] == 'Female') echo 'selected'; ?>>Female</option>
        <option value="Other" <?php if ($member['gender'] == 'Other') echo 'selected'; ?>>Other</option>
    </select>

    <label for="membership_type">Membership Type:</label>
    <select id="membership_type" name="membership_type" required>
        <option value="Basic Member" <?php if ($member['membership_type'] == 'Basic Member') echo 'selected'; ?>>Basic Member (1 Month)</option>
        <option value="Premium Member" <?php if ($member['membership_type'] == 'Premium Member') echo 'selected'; ?>>Premium Member (3 Months)</option>
        <option value="Standard Member" <?php if ($member['membership_type'] == 'Standard Member') echo 'selected'; ?>>Standard Member (6 Months)</option>
    </select>

    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo $member['start_date']; ?>" required>

    <label for="schedule_id">Preferred Schedule:</label>
    <select id="schedule_id" name="schedule_id" required>
        <?php
        $schedules_sql = "SELECT id, start_time, end_time FROM schedules ORDER BY start_time";
        $schedules_result = mysqli_query($conn, $schedules_sql);
        if (mysqli_num_rows($schedules_result) > 0) {
            while ($schedule = mysqli_fetch_assoc($schedules_result)) {
                $selected = ($member['schedule_id'] == $schedule['id']) ? 'selected' : '';
                echo '<option value="' . $schedule['id'] . '" ' . $selected . '>Time: ' . $schedule['start_time'] . ' - ' . $schedule['end_time'] . '</option>';
            }
        } else {
            echo '<option value="">No schedules available</option>';
        }
        ?>
    </select>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="Credit Card" <?php if ($member['payment_method'] == 'Credit Card') echo 'selected'; ?>>Credit Card</option>
        <option value="Debit Card" <?php if ($member['payment_method'] == 'Debit Card') echo 'selected'; ?>>Debit Card</option>
        <option value="Cash" <?php if ($member['payment_method'] == 'Cash') echo 'selected'; ?>>Cash</option>
        <option value="Mobile Payment" <?php if ($member['payment_method'] == 'Mobile Payment') echo 'selected'; ?>>Mobile Payment</option>
    </select>

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" value="<?php echo $member['amount']; ?>" required>

    <input type="submit" value="Update Member">
</form>

</body>
</html>

<?php
mysqli_close($conn);
?>
