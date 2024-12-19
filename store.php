<?php
session_start();
include "store_logic.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate input
    if (empty($product_id) || empty($quantity) || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product or quantity.']);
        exit;
    }

    // Attempt to add to cart
    $success = add_to_cart($product_id, $quantity);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unable to add product to cart.']);
    }
    exit;
}

// Fetch products based on search or all products if no search term is provided
$search_term = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1; // Current page
$limit = 10; // Number of products per page



// Fetch products and calculate total pages
$result = fetch_products($search_term, $page, $limit);
$total_pages = calculate_total_pages($search_term, $limit);



// Delete out-of-stock products
delete_out_of_stock_products();


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Product List</h1>
        <div class="icons">
            <div class="order-icon">
                <a href="orders.php">
                    <i class="fas fa-box"></i>
                </a>
            </div>
            <div class="cart-icon">
                <a href="cart.php">
                    <div id="notification" style="display:none; color: green;"></div>
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?></span>
                </a>
            </div>
        </div>
    </div>

    <form method="get" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="Search for products" value="<?php echo htmlspecialchars($search_term); ?>">
        <button type="submit" class="search-button">Search</button>
    </form>

    <div class="product-list">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="product-item">
                <?php if (!empty($row['image'])): ?>
                    <img src="equipments/<?php echo htmlspecialchars($row['image']); ?>" class="product-image" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                <?php else: ?>
                    <img src="path/to/default-image.jpg" class="product-image" alt="No Image Available">
                <?php endif; ?>
                <div class="product-details">
                    <h5 class="product-title"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                    <?php
                    $discounted_price = $row['price'] * (1 - $row['discount'] / 100);
                    ?>
                    <p class="product-price">
                        <span class="price-old">NPR <?php echo number_format($row['price']); ?></span> 
                        NPR <?php echo number_format($discounted_price); ?>
                    </p>
                    <p class="product-discount">Discount: <?php echo htmlspecialchars($row['discount']); ?>%</p>
                    <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="product-availability">Available: <?php echo htmlspecialchars($row['available']); ?></p>
                    <p class="product-sold">Sold: <?php echo htmlspecialchars($row['sold']); ?></p>
                    <form method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination at the Bottom of Products -->
    <div class="pagination-wrapper">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="pagination-item">
                    <a class="pagination-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_term); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="pagination-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="pagination-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_term); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="pagination-item">
                    <a class="pagination-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_term); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

</div>

<?php include "footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$('.add-to-cart-form').on('submit', function(event) {
    event.preventDefault();
    let button = $(this).find('.add-to-cart-button');
    button.prop('disabled', true);

    $.ajax({
        type: 'POST',
        url: 'store.php',
        data: $(this).serialize() + '&add_to_cart=true',
        dataType: 'json',
        success: function(response) {
            console.log(response); // Log the response for debugging
            button.prop('disabled', false);
            $('#notification').text(response.message).fadeIn().delay(3000).fadeOut();
            if (response.status === 'success') {
                let newCount = parseInt($('.cart-count').text()) + parseInt($('input[name="quantity"]').val());
                $('.cart-count').text(newCount);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error); // Log any errors
            button.prop('disabled', false);
            $('#notification').text('').fadeIn().delay(3000).fadeOut();
        }
    });
});

</script>
</body>
</html>
