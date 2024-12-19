<?php
session_start(); // Ensure this is called only once

include "connection.php";

// Initialize variables
$product_name = $category = $price = $discount = $quantity = $description = $image = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Validate discount input
    if (!is_numeric($discount) || $discount < 0 || $discount > 100) {
        $errors[] = "Discount must be a number between 0 and 100.";
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = 'admin/equipments/';
        $target_file = $target_dir . $image_name;

        // Create directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $image_name;
        } else {
            $errors[] = "Failed to upload image.";
        }
    } else {
        $errors[] = "No image uploaded or image upload error.";
    }

    // Check for errors
    if (empty($errors)) {
        // Check if the product with the same name and price already exists
        $check_query = "SELECT * FROM products WHERE product_name = '$product_name' AND price = '$price'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // If the product exists, update the quantity and available fields
            $existing_product = mysqli_fetch_assoc($check_result);
            $new_quantity = $existing_product['quantity'] + $quantity;
            $new_available = $existing_product['available'] + $quantity;
            $update_query = "UPDATE products SET quantity = '$new_quantity', available = '$new_available' WHERE id = " . $existing_product['id'];

            if (mysqli_query($conn, $update_query)) {
                header("Location: show_product.php");
                exit;
            } else {
                $errors[] = "Failed to update product: " . mysqli_error($conn);
            }
        } else {
            // If the product does not exist, insert a new product
            $insert_query = "INSERT INTO products (product_name, category, price, discount, quantity, available, description, image) 
                             VALUES ('$product_name', '$category', '$price', '$discount', '$quantity', '$quantity', '$description', '$image')";

            if (mysqli_query($conn, $insert_query)) {
                header("Location: show_product.php");
                exit;
            } else {
                $errors[] = "Failed to insert product: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add New Product</h1>
    
    <?php
    if (!empty($errors)) {
        echo '<div class="alert alert-danger">';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
    ?>

    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($category); ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Price (NPR)</label>
            <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
        </div>
        <div class="form-group">
            <label for="discount">Discount (%)</label>
            <input type="text" class="form-control" id="discount" name="discount" value="<?php echo htmlspecialchars($discount); ?>">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
