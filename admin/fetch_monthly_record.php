<?php
include "../connection.php";

// Query for 1 month memberships
$sql1Month = "SELECT COUNT(*) as count, SUM(payment_amount) as total_amount
              FROM memberships
              WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

$result1Month = mysqli_query($conn, $sql1Month);
$row1Month = mysqli_fetch_assoc($result1Month);

// Query for 3 month memberships
$sql3Month = "SELECT COUNT(*) as count, SUM(payment_amount) as total_amount
              FROM memberships
              WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";

$result3Month = mysqli_query($conn, $sql3Month);
$row3Month = mysqli_fetch_assoc($result3Month);

// Query for 6 month memberships
$sql6Month = "SELECT COUNT(*) as count, SUM(payment_amount) as total_amount
              FROM memberships
              WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";

$result6Month = mysqli_query($conn, $sql6Month);
$row6Month = mysqli_fetch_assoc($result6Month);

// Query for total memberships and members
$sqlTotal = "SELECT COUNT(*) as total_members, SUM(payment_amount) as total_amount
             FROM memberships";

$resultTotal = mysqli_query($conn, $sqlTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);

$data = [
    '1MonthCount' => $row1Month['count'],
    '1MonthTotal' => $row1Month['total_amount'],
    '3MonthCount' => $row3Month['count'],
    '3MonthTotal' => $row3Month['total_amount'],
    '6MonthCount' => $row6Month['count'],
    '6MonthTotal' => $row6Month['total_amount'],
    'totalMembers' => $rowTotal['total_members'],
    'totalAmount' => $rowTotal['total_amount']
];

echo json_encode($data);

mysqli_close($conn);
?>
