<?php
session_start();
include "connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if order_id is provided
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete from order_details table
        $sql1 = "DELETE FROM order_details WHERE order_id = ?";

        $stmt1 = $conn->prepare($sql1);
        if ($stmt1 === false) {
            throw new Exception("Prepare Failed: " . htmlspecialchars($conn->error));
        }
        $stmt1->bind_param("i", $order_id);
        if (!$stmt1->execute()) {
            throw new Exception("Execute Failed: " . htmlspecialchars($stmt1->error));
        }

        // Delete from orders table
        $sql2 = "DELETE FROM orders WHERE order_id = ? AND user_id = ?";

        $stmt2 = $conn->prepare($sql2);
        if ($stmt2 === false) {
            throw new Exception("Prepare Failed: " . htmlspecialchars($conn->error));
        }
        $stmt2->bind_param("ii", $order_id, $user_id);
        if (!$stmt2->execute()) {
            throw new Exception("Execute Failed: " . htmlspecialchars($stmt2->error));
        }

        // Commit transaction
        $conn->commit();
        
        // Redirect back to orders page
        header('Location: orders.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    echo "No order ID provided.";
}
?>
