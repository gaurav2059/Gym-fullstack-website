<?php
session_start();
include 'connection.php';

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Confirm membership request
if (isset($_GET['confirm']) && isset($_GET['id'])) {
    $request_id = $_GET['id'];
    
    // Fetch the request details
    $query = "SELECT * FROM membership_requests WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $request_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $request = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='error-message'>Error preparing select query: " . mysqli_error($conn) . "</div>";
        exit;
    }

    if ($request) {
        // Check if user already has an active membership
        $check_query = "SELECT * FROM membership_requests WHERE user_id = ? AND end_date > NOW()";
        if ($check_stmt = mysqli_prepare($conn, $check_query)) {
            mysqli_stmt_bind_param($check_stmt, 'i', $request['user_id']);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            mysqli_stmt_close($check_stmt);

         
        }

        // Update status in membership_requests
        $update_status_query = "UPDATE membership_requests SET status = 'Confirmed' WHERE id = ?";
        if ($update_status_stmt = mysqli_prepare($conn, $update_status_query)) {
            mysqli_stmt_bind_param($update_status_stmt, 'i', $request_id);
            if (mysqli_stmt_execute($update_status_stmt)) {
                echo "<div class='success-message'>Membership confirmed successfully.</div>";
            } else {
                echo "<div class='error-message'>Error updating membership status: " . mysqli_error($conn) . "</div>";
            }
            mysqli_stmt_close($update_status_stmt);
        } else {
            echo "<div class='error-message'>Error preparing update status query: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='error-message'>Membership request not found.</div>";
    }
}

// Cancel membership request
if (isset($_GET['cancel']) && isset($_GET['id'])) {
    $request_id = $_GET['id'];
    
    $query = "DELETE FROM membership_requests WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $request_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='success-message'>Membership request canceled successfully.</div>";
        } else {
            echo "<div class='error-message'>Error canceling membership request: " . mysqli_error($conn) . "</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='error-message'>Error preparing delete query: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch all membership requests excluding confirmed ones
$requests_query = "SELECT * FROM membership_requests WHERE status != 'Confirmed'";
$requests_result = mysqli_query($conn, $requests_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Requests</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Membership Requests</h2>
<a href="member.php">Members</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Membership Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Schedule ID</th>
            <th>Payment Method</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($requests_result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['membership_type']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td><?php echo $row['schedule_id']; ?></td>
                <td><?php echo $row['payment_method']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="?confirm&id=<?php echo $row['id']; ?>">Confirm</a>
                    <a href="?cancel&id=<?php echo $row['id']; ?>">Cancel</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>

<?php mysqli_close($conn); ?>
