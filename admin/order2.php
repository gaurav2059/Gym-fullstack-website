<?php
include 'connection.php';

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Query to fetch payment data with user names, with pagination
$query = "
    SELECT p.user_id, p.payment_amount, p.product_quantity, p.payment_date, u.fullname 
    FROM payments p
    JOIN users u ON p.user_id = u.id
    LIMIT $offset, $records_per_page
";
$result = mysqli_query($conn, $query);

// Query to get total number of records for pagination calculation
$total_query = "SELECT COUNT(*) AS total FROM payments";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $records_per_page);

if (mysqli_num_rows($result) > 0) {
    // Display the 'Home' button and table headers
    echo "
    <a href='admin_pannel.php'>
        <button type='button' class='button-home'>Home</button>
    </a>
    ";
    echo "<table>";
    echo "<tr><th>User ID</th><th>Full Name</th><th>Total Amount</th><th>Total Quantity</th><th>Payment Date</th></tr>";

    // Display data rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . $row['fullname'] . "</td>";
        echo "<td>" . $row['payment_amount'] . "</td>";
        echo "<td>" . $row['product_quantity'] . "</td>";
        echo "<td>" . $row['payment_date'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Pagination links
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i' class='pagination-link'>$i</a> ";
    }
    echo "</div>";
} else {
    echo "No payments found.";
}

// Free result set
mysqli_free_result($result);
mysqli_free_result($total_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Records</title>
    <style>
        /* CSS for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        /* Table header styles */
        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        /* Table row styles */
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        /* Alternate row background color */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Hover effect on rows */
        tr:hover {
            background-color: #e2e2e2;
        }

        /* Button styles */
        .button-home {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: skyblue; 
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            border: none;
        }

        /* Pagination styles */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination-link {
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #f2f2f2;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
        }

        .pagination-link:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
</body>
</html>
