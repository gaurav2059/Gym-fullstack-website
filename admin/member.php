<?php
include 'connection.php';

// Fetch all membership requests from the database
$sql = "SELECT * FROM membership_requests";
$result = mysqli_query($conn, $sql);

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
<a href="add_member.php">Add Member</a>

<table border="1">
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
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>{$row['phone']}</td>";
                echo "<td>{$row['address']}</td>";
                echo "<td>{$row['age']}</td>";
                echo "<td>{$row['gender']}</td>";
                echo "<td>{$row['membership_type']}</td>";
                echo "<td>{$row['start_date']}</td>";
                echo "<td>{$row['end_date']}</td>";
                echo "<td>{$row['schedule_id']}</td>";
                echo "<td>{$row['payment_method']}</td>";
                echo "<td>{$row['amount']}</td>";
                echo "<td>{$row['status']}</td>";
                echo "<td>
                        <a href='edit_member.php?id={$row['id']}'>Edit</a> |
                        <a href='delete_member.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this member?');\">Delete</a> |
                        
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='14'>No membership requests found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
mysqli_close($conn);
?>
