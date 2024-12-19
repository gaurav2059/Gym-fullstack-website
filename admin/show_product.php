<?php
session_start();

// Check if admin session variables are not set or incorrect
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'connection.php';

// Fetch products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Products</h1>
    <a href="add_product.php" class="btn btn-primary mb-3">Add Product</a>
    <a href="view_product.php" class="btn btn-primary mb-3">view_product</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price (NPR)</th>
                <th>Discount</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['discount']) . '%'; ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><img src="admin/equipments/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" width="100"></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Free result set and close connection
mysqli_free_result($result);
mysqli_close($conn);
?>
