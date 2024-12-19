<?php
session_start();
include "connection.php";

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];

    // Fetch the order details
    $sql_order = "SELECT product_id, quantity FROM order_details WHERE order_id = '$order_id'";
    $result_order = mysqli_query($conn, $sql_order);
    if (!$result_order) {
        die("Query Failed: " . mysqli_error($conn));
    }

    if (isset($_POST['confirm_order'])) {
        // Confirm the order and update product availability and sold count
        while ($order_item = mysqli_fetch_assoc($result_order)) {
            $product_id = $order_item['product_id'];
            $quantity = $order_item['quantity'];

            // Update product availability
            $update_product_sql = "UPDATE products SET available = available - $quantity, sold = sold + $quantity WHERE id = '$product_id' AND available >= $quantity";
            if (!mysqli_query($conn, $update_product_sql)) {
                die("Failed to update product availability: " . mysqli_error($conn));
            }
        }

        // Update order status to 'Confirmed'
        $sql_confirm = "UPDATE orders SET status = 'Confirmed' WHERE order_id = '$order_id'";
        if (mysqli_query($conn, $sql_confirm)) {
            $message = "Order #$order_id confirmed successfully.";
        } else {
            $message = "Failed to confirm order: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['cancel_order'])) {
        // Update order status to 'Canceled'
        $sql_cancel = "UPDATE orders SET status = 'Canceled' WHERE order_id = '$order_id'";
        if (mysqli_query($conn, $sql_cancel)) {
            $message = "Order #$order_id canceled successfully.";
        } else {
            $message = "Failed to cancel order: " . mysqli_error($conn);
        }
    }

    // Redirect back to the orders page with a message
    header("Location: orders.php?message=" . urlencode($message));
    exit;
}
?>
