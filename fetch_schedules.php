<?php
session_start();
include 'connection.php';

$schedules_sql = "
    SELECT s.id AS schedule_id, s.start_time, s.end_time, s.max_capacity, COUNT(m.id) AS current_capacity
    FROM schedules s
    LEFT JOIN memberships m ON s.id = m.schedule_id AND m.status = 'active' AND m.end_date > NOW()
    GROUP BY s.id
    ORDER BY s.start_time
";

$schedules_result = mysqli_query($conn, $schedules_sql);

if (!$schedules_result) {
    die('Error executing query: ' . mysqli_error($conn));
}

$schedules = mysqli_fetch_all($schedules_result, MYSQLI_ASSOC);

echo json_encode($schedules);

mysqli_close($conn);
?>
