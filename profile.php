<?php
include 'connection.php';
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Updated query using INNER JOIN
$sql = "
    SELECT u.fullname, u.email, u.username, 
           m.membership_type, m.start_date, m.end_date, m.schedule_id
    FROM users u
    INNER JOIN membership_requests m ON u.id = m.user_id
    WHERE u.id = ? AND m.status = 'confirmed'
";

$row = []; // Initialize $row to avoid undefined variable warnings

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check for SQL errors
    if (!$result) {
        die('Error executing query: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);
} else {
    die('Error preparing query: ' . mysqli_error($conn));
}

// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<?php include 'header.php'; ?>

<div id="user_profile-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="section-head">My Profile</h2>
                <?php if (!empty($row)): ?>
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <td><b>Full Name :</b></td>
                            <td><?php echo htmlspecialchars($row["fullname"]); ?></td>
                        </tr>
                        <tr>
                            <td><b>Email :</b></td>
                            <td><?php echo htmlspecialchars($row["email"]); ?></td>
                        </tr>
                        <tr>
                            <td><b>Username :</b></td>
                            <td><?php echo htmlspecialchars($row["username"]); ?></td>
                        </tr>
                        <?php if (!empty($row['membership_type'])): ?>
                            <tr>
                                <td><b>Membership Type :</b></td>
                                <td><?php echo htmlspecialchars($row["membership_type"]); ?></td>
                            </tr>
                            <tr>
                                <td><b>Start Date :</b></td>
                                <td><?php echo htmlspecialchars($row["start_date"]); ?></td>
                            </tr>
                            <tr>
                                <td><b>End Date :</b></td>
                                <td><?php echo htmlspecialchars($row["end_date"]); ?></td>
                            </tr>
                            <tr>
                                <td><b>Schedule ID :</b></td>
                                <td><?php echo htmlspecialchars($row["schedule_id"]); ?></td>
                            </tr>
                            
                        <?php else: ?>
                            <tr>
                                <td colspan="2">No active membership found.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                <?php else: ?>
                    <p>No user found.</p>
                <?php endif; ?>

                <a class="modify-btn btn" href="modify_user.php?user=<?php echo htmlspecialchars($_SESSION['user_id']); ?>">Modify Details</a>
                <a class="modify-btn btn" href="change_pw.php">Change Password</a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <button class="back-button" onclick="goBack()">Go Back</button>
</div>
<?php include 'footer.php'; ?>

<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>
