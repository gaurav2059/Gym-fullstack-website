<?php
session_start();

// Check if admin session variables are not set or incorrect
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'connection.php';

// Handle deletion of a product
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM products WHERE id='$delete_id'";
    mysqli_query($conn, $delete_query);
    header("Location: view_product.php");
    exit;
}

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
    <title>View Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Products</h1>
    <a href="add_product.php" class="btn btn-primary mb-3">Add Product</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price (NPR)</th>
                <th>Discount</th>
                <th>Available</th>
                <th>Sold</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
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
                <td><?php echo htmlspecialchars($row['available']); ?></td>
                <td><?php echo htmlspecialchars($row['sold']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><img src="admin/equipments/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width: 100px;"></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="view_product.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
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
// Free the result set
mysqli_free_result($result);

// Close the database connection
mysqli_close($conn);
?>
