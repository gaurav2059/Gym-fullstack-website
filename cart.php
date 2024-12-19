<?php
session_start();
include "connection.php";

// Handle cart update requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $product_id = (int)$product_id;
        $quantity = (int)$quantity;

        if (isset($_SESSION['cart'][$product_id])) {
            if ($quantity > 0) {
                // Check availability
                $stmt = $conn->prepare("SELECT available FROM products WHERE id = ?");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($available);
                $stmt->fetch();
                $stmt->close();

                if ($available >= $quantity) {
                    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Not enough stock available.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Quantity must be greater than 0.']);
                exit;
            }
        }
    }
    echo json_encode(['status' => 'success']);
    exit;
}

// Handle removal of items from the cart
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .cart-item {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Shopping Cart</h1>

    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <form id="cart-form" method="post">
            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                <?php
                // Fetch product information including discount
                $stmt = $conn->prepare("SELECT product_name, price, discount, available FROM products WHERE id = ?");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($product_name, $price, $discount, $available);
                $stmt->fetch();
                $stmt->close();

                // Calculate the discounted price
                $discounted_price = $price - ($price * ($discount / 100));
                $item_total = $discounted_price * $item['quantity'];
                $total += $item_total;
                ?>
                <div class="card cart-item">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product_name); ?></h5>
                        <p class="card-text">Price: NPR <?php echo number_format($discounted_price, 2); ?> (after discount)</p>
                        <p class="card-text">Quantity:
                            <input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $available; ?>" class="form-control d-inline-block quantity-field" style="width: auto;" data-price="<?php echo $discounted_price; ?>" data-available="<?php echo $available; ?>">
                            <button type="submit" name="update_cart" value="true" class="btn btn-primary ml-2">Update</button>
                        </p>
                        <p class="card-text total-per-item">Total: NPR <?php echo number_format($item_total, 2); ?></p>
                        <a href="cart.php?remove=<?php echo $product_id; ?>" class="btn btn-danger">Remove</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="mb-3">
                <strong>Total Price: NPR <span id="total-price"><?php echo number_format($total, 2); ?></span></strong>
            </div>
            <button type="submit" name="update_cart" class="btn btn-primary">Update Cart</button>
            <button type="button" id="buy-now-all" class="btn btn-success">Buy Now</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Update cart when quantity changes
    $('#cart-form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'cart.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();  // Reload the page to reflect changes
                } else {
                    alert(response.message);  // Show an error message
                }
            }
        });
    });

    // Handle "Buy Now" for all items
    $('#buy-now-all').on('click', function() {
        window.location.href = 'checkout.php';  // Redirect to checkout page
    });

    // Update total price dynamically based on quantity input
    $('.quantity-field').on('input', function() {
        var price = $(this).data('price');
        var quantity = $(this).val();
        var available = $(this).data('available');

        if (quantity > available) {
            alert('Quantity exceeds available stock.');
            $(this).val(available);
            quantity = available;
        }

        var totalPerItem = price * quantity;
        $(this).closest('.cart-item').find('.total-per-item').text('Total: NPR ' + totalPerItem.toLocaleString());

        // Recalculate total price
        var newTotal = 0;
        $('.quantity-field').each(function() {
            var itemPrice = $(this).data('price');
            var itemQuantity = $(this).val();
            newTotal += itemPrice * itemQuantity;
        });
        $('#total-price').text(newTotal.toLocaleString());
    });
});
</script>

</body>
</html>
