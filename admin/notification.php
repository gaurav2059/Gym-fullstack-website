<?php
// Function to send notification
function sendNotification($user_id, $message, $conn) {
    $query = "INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    $stmt->close();
}

// Example usage:
if (isset($_POST['confirm_order']) || isset($_POST['cancel_order'])) {
    $order_id = (int)$_POST['order_id'];
    
    $query = "SELECT user_id, status FROM orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $status);
    $stmt->fetch();
    $stmt->close();

    if (isset($_POST['confirm_order'])) {
        sendNotification($user_id, "Your order #$order_id has been confirmed.", $conn);
    } elseif (isset($_POST['cancel_order'])) {
        sendNotification($user_id, "Your order #$order_id has been cancelled.", $conn);
    }
}
?>
