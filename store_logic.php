<?php
include "connection.php";
include "header.php";
// session_start(); 

// Function to fetch products with pagination
function fetch_products($search_term = '', $page = 1, $limit = 10) {
    global $conn; // Use the global DB connection

    if (!$conn) {
        die("Database connection failed.");
    }
    
    // Calculate offset for pagination
    $offset = ($page - 1) * $limit;
    
    // Prepare the SQL query with search term and pagination
    $search_term = mysqli_real_escape_string($conn, $search_term);
    $sql = "SELECT * FROM products WHERE product_name LIKE '%$search_term%' AND available > 0 LIMIT $offset, $limit";
    
    $result = mysqli_query($conn, $sql);
    
    // Check for query errors
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }
    
    return $result;
}

// Function to calculate total pages for pagination
function calculate_total_pages($search_term = '', $limit = 10) {
    global $conn; // Use the global DB connection

    if (!$conn) {
        die("Database connection failed.");
    }
    
    $search_term = mysqli_real_escape_string($conn, $search_term);
    $sql = "SELECT COUNT(*) AS total FROM products WHERE product_name LIKE '%$search_term%' AND available > 0";
    
    $result = mysqli_query($conn, $sql);
    
    // Check for query errors
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($result);
    $total_records = $row['total'];
    
    return ceil($total_records / $limit);
}

// Function to handle adding items to the cart
function add_to_cart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Update the quantity if already exists
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new product to the cart
        $_SESSION['cart'][$product_id] = array(
            'quantity' => $quantity
        );
    }
    
    // Check if the quantity is valid
    $product = get_product_by_id($product_id); // Fetch the product details
    if ($product && $quantity > 0 && $quantity <= $product['available']) {
        return true;
    }
    return false; // Return false if the product cannot be added
}

// Function to fetch product details by ID
function get_product_by_id($product_id) {
    global $conn; // Use the global DB connection
    
    if (!$conn) {
        die("Database connection failed.");
    }
    
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $sql = "SELECT * FROM products WHERE id = '$product_id'";
    
    $result = mysqli_query($conn, $sql);
    
    // Check for query errors
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }
    
    return mysqli_fetch_assoc($result);
}

// Function to delete products with zero availability
function delete_out_of_stock_products() {
    global $conn;

    if (!$conn) {
        die("Database connection failed.");
    }

    // First, delete related records in the order_details table
    $sql = "DELETE FROM order_details WHERE product_id IN (SELECT id FROM products WHERE available <= 0)";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    // Now, delete products with zero availability
    $sql = "DELETE FROM products WHERE available <= 0";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }
}
?>
