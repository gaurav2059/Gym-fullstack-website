<?php
session_start();
include "connection.php";

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch only pending orders
$sql = "SELECT order_id, user_id, address, status, created_at FROM orders WHERE status = 'Pending'";
$result = mysqli_query($conn, $sql);

// Check for errors in the SQL query
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Order Management</h1>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <form method="post" action="admin_confirm_order.php" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                <button type="submit" name="confirm_order" class="btn btn-success">Confirm</button>
                            </form>
                            <form method="post" action="admin_confirm_order.php" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                <button type="submit" name="cancel_order" class="btn btn-danger">Cancel</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Fetch and display the items in the order -->
                    <?php
                    $order_id = $row['order_id'];
                    $sql_items = "SELECT product_id, quantity, price FROM order_details WHERE order_id = '$order_id'";
                    $result_items = mysqli_query($conn, $sql_items);

                    // Check for errors in the SQL query
                    if (!$result_items) {
                        die("Query Failed: " . mysqli_error($conn));
                    }

                    while ($item = mysqli_fetch_assoc($result_items)):
                    ?>
                        <tr>
                            <td colspan="2">Product ID: <?php echo htmlspecialchars($item['product_id']); ?></td>
                            <td>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>Price: <?php echo htmlspecialchars($item['price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending orders found.</p>
    <?php endif; ?>
</div>
</body>
</html>
