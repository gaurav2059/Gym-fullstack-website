<?php
session_start();
include "connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user
$sql = "SELECT order_id, created_at, status, 
               address, phone, province, city, village,
               payment_method
        FROM orders
        WHERE user_id = ?
        ORDER BY created_at DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare Failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die("Execute Failed: " . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();
if (!$result) {
    die("Get Result Failed: " . htmlspecialchars($stmt->error));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .order-table td, .order-table th {
            vertical-align: middle;
        }
        .status-confirmed {
            color: green;
        }
        .status-cancelled {
            color: red;
        }
        .status-pending {
            color: orange;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>My Orders</h1>
    
    <table class="table order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Province</th>
                <th>City</th>
                <th>Village</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['province']); ?></td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><?php echo htmlspecialchars($row['village']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                        <td class="<?php echo 'status-' . strtolower(htmlspecialchars($row['status'])); ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </td>
                        <td>
                            <form method="post" action="delete_order.php" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
