<?php
session_start();
include "connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $village = mysqli_real_escape_string($conn, $_POST['village']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    // Check if cart is empty
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
        exit;
    }

    // Insert order into database with pending status
    $sql = "INSERT INTO orders (user_id, address, phone, province, city, village, payment_method, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $user_id, $address, $phone, $province, $city, $village, $payment_method);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // Get the last inserted order ID

        // Insert order details
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $quantity = $item['quantity'];
            $price = $item['price'];
            
            // Insert order details
            $sql_details = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_details = $conn->prepare($sql_details);
            $stmt_details->bind_param("iiii", $order_id, $product_id, $quantity, $price);
            $stmt_details->execute();
        }

        // Clear the cart after successful order placement
        unset($_SESSION['cart']);

        // Redirect to a confirmation page or back to the store
        header("Location: store.php?message=Order placed successfully!");
        exit;
    } else {
        echo "Failed to place order. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Checkout</h1>

    <form method="post" action="checkout.php">
        <div class="form-group">
            <label for="address">Address*</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone*</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="province">Province*</label>
            <input type="text" class="form-control" id="province" name="province" required>
        </div>
        <div class="form-group">
            <label for="city">City*</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <div class="form-group">
            <label for="village">Village*</label>
            <input type="text" class="form-control" id="village" name="village" required>
        </div>
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select class="form-control" id="payment_method" name="payment_method" required>
                <option value="cash_on_delivery">Cash on Delivery</option>
                <option value="esewa">eSewa</option>
                <option value="khalti">Khalti</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

</body>
</html>
