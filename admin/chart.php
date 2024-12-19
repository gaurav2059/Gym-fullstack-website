<?php
session_start();
include "../connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Query to fetch users with active memberships
$sql = "SELECT u.id, u.username, u.email, mp.subscription, mp.payment_amount, mp.payment_date, mp.expiry_date
        FROM users u
        LEFT JOIN memberships mp ON u.id = mp.user_id
        WHERE mp.user_id IS NOT NULL
        ORDER BY u.id";

$result = mysqli_query($conn, $sql);

// Error handling for SQL query execution
if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}

// Initialize arrays to store data for charts
$subscriptionCounts = ['Basic' => 0, 'Premium' => 0, 'Standard' => 0];
$monthlyCounts = []; // Array to store monthly membership counts
$totalAmount = 0;

// Fetch data and populate arrays
while ($row = mysqli_fetch_assoc($result)) {
    // Count subscriptions
    $subscription = $row['subscription'];
    if (array_key_exists($subscription, $subscriptionCounts)) {
        $subscriptionCounts[$subscription]++;
    }

    // Calculate monthly count based on payment date (example logic)
    $paymentDate = date('F Y', strtotime($row['payment_date']));
    if (!isset($monthlyCounts[$paymentDate])) {
        $monthlyCounts[$paymentDate] = 0;
    }
    $monthlyCounts[$paymentDate]++;

    // Calculate total payment amount
    $totalAmount += $row['payment_amount'];
}

// Prepare data for monthly chart
$months = array_keys($monthlyCounts);
$monthlyValues = array_values($monthlyCounts);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Gym Membership Charts</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js library -->
    <style>
        /* General styles */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .container h1 {
            text-align: center;
        }
        .chart-container {
            width: 100%;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel - Gym Membership Charts</h1>

        <!-- Charts Section -->
        <div class="charts">
            <!-- Monthly Subscription Chart -->
            <div class="chart-container">
                <canvas id="monthlySubscriptionChart"></canvas>
            </div>
            <!-- Membership Type Distribution Chart -->
            <div class="chart-container">
                <canvas id="membershipTypeChart"></canvas>
            </div>
            <!-- Total Amount Chart -->
            <div class="chart-container">
                <canvas id="totalAmountChart"></canvas>
            </div>
        </div>

        <a class="logout-link" href="logout.php">Logout</a>
        <a class="logout-link" href="member.php">Back to Member Details</a>
    </div>

    <script>
        // Chart.js code to render Monthly Subscription Chart
        var ctx1 = document.getElementById('monthlySubscriptionChart').getContext('2d');
        var monthlySubscriptionChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Monthly Subscriptions',
                    data: <?php echo json_encode($monthlyValues); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart.js code to render Membership Type Distribution Chart
        var ctx2 = document.getElementById('membershipTypeChart').getContext('2d');
        var membershipTypeChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Basic', 'Premium', 'Standard'],
                datasets: [{
                    label: 'Membership Type Distribution',
                    data: [
                        <?php echo $subscriptionCounts['Basic']; ?>,
                        <?php echo $subscriptionCounts['Premium']; ?>,
                        <?php echo $subscriptionCounts['Standard']; ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart.js code to render Total Amount Chart
        var ctx3 = document.getElementById('totalAmountChart').getContext('2d');
        var totalAmountChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['Total Amount'],
                datasets: [{
                    label: 'Total Amount',
                    data: [<?php echo $totalAmount; ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
