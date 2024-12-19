<?php
include 'connection.php';

$today = date('Y-m-d');
$query = "DELETE FROM memberships WHERE end_date < ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $today);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_close($conn);
?>
