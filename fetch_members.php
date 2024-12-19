<?php
include 'connection.php';

$schedule_id = isset($_GET['schedule_id']) ? intval($_GET['schedule_id']) : 0;

$query = "
    SELECT u.name
    FROM membership_requests m
    JOIN users u ON m.user_id = u.id
    WHERE m.schedule_id = ? AND m.end_date > NOW()
";

if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $schedule_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $members = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }

    mysqli_stmt_close($stmt);

    header('Content-Type: application/json');
    echo json_encode($members);
} else {
    die('Query Error: ' . mysqli_error($conn));
}

mysqli_close($conn);
?>
