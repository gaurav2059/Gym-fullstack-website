<?php
session_start();

// Check if admin session variables are not set or incorrect
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'connection.php';

// Get the product ID from the URL
if (!isset($_GET['id'])) {
    header("Location: view_product.php");
    exit;
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch product details from the database
$query = "SELECT * FROM products WHERE id='$product_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: view_product.php");
    exit;
}

$product = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = 'admin/equipments/';
        $target_file = $target_dir . $image_name;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $image_name;
        } else {
            $image = $product['image'];
        }
    } else {
        $image = $product['image'];
    }

    // Update the product in the database
    $update_query = "UPDATE products 
                     SET product_name='$product_name', category='$category', price='$price', discount='$discount', quantity='$quantity', description='$description', image='$image'
                     WHERE id='$product_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: view_product.php");
        exit;
    } else {
        $errors[] = "Failed to update the product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Edit Product</h1>

    <?php
    if (!empty($errors)) {
        echo '<div class="alert alert-danger">';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
    ?>

    <form action="edit_product.php?id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Price (NPR)</label>
            <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>
        <div class="form-group">
            <label for="discount">Discount (%)</label>
            <input type="text" class="form-control" id="discount" name="discount" value="<?php echo htmlspecialchars($product['discount']); ?>">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
            <p>Current Image: <img src="admin/equipments/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width: 100px;"></p>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
