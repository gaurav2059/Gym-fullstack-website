<?php
include 'connection.php';

$promocode = $_GET['promocode'];

function getDiscountByPromoCode($promocode, $conn) {
    $query = "SELECT discount_percentage FROM offers WHERE promo_code = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 's', $promocode);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $discount_percentage);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $discount_percentage ?: 0; // Return 0 if no discount found
    } else {
        die('Prepare failed: ' . mysqli_error($conn));
    }
}

$discount_percentage = getDiscountByPromoCode($promocode, $conn);

header('Content-Type: application/json');
echo json_encode(['discount_percentage' => $discount_percentage]);

mysqli_close($conn);
?>
