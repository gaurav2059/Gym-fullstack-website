<?php
session_start();

// Check if admin session variables are not set or incorrect
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'connection.php';

// Example: Get username from session
$username = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';

// Function to fetch count of users
function getUserCount($conn) {
    $query = "SELECT COUNT(*) as count FROM users";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getMemberCount($conn) {
    $query = "SELECT COUNT(*) as count FROM membership_requests";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getOrderCount($conn) {
    $query = "SELECT COUNT(*) as count FROM orders";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getStoreCount($conn) {
    $query = "SELECT COUNT(*) as count FROM products"; // Assuming store_post should be products
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

// Fetch counts
$userCount = getUserCount($conn);
$orderCount = getOrderCount($conn); // Adjusted name to match function
$memberCount = getMemberCount($conn);
$storeCount = getStoreCount($conn);

// Fetch latest notifications
$query = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5";
$result = mysqli_query($conn, $query);
$notifications = [];
while ($notification = mysqli_fetch_assoc($result)) {
    $notifications[] = $notification;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_panel.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
        /* Basic styling for demo purposes */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: #333;
            color: #fff;
            padding-top: 60px;
            transition: all 0.3s ease;
        }
        .sidebar .side-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar .side-header h3 {
            margin: 0;
            font-size: 1.8rem;
        }
        .sidebar .side-header span {
            color: #FF5722;
        }
        .sidebar .side-content {
            padding: 20px;
            height: calc(100% - 60px);
            overflow-y: auto;
        }
        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
            background-size: cover;
            background-position: center;
        }
        .profile h4 {
            margin: 0;
            font-size: 1.2rem;
        }
        .side-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .side-menu ul li {
            margin-bottom: 10px;
        }
        .side-menu ul li a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .side-menu ul li a.active {
            background-color: #FF5722;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .header {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h2 {
            margin: 0;
            font-size: 1.6rem;
        }
        .header .user {
            display: flex;
            align-items: center;
        }
        .header .user h2 {
            margin-right: 10px;
        }
        .header .user a {
            color: #333;
            text-decoration: none;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .header .user a:hover {
            background-color: #ddd;
            color: #333;
        }
        .header .notifications {
            position: relative;
            display: flex;
            align-items: center;
            margin-left: 20px;
            cursor: pointer;
        }
        .header .notifications .las.la-bell {
            font-size: 1.5rem;
            color: #333;
        }
        .header .notifications .notification-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 4px;
            width: 250px;
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
        }
        .header .notifications:hover .notification-dropdown {
            display: block;
        }
        .header .notifications .notification-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .header .notifications .notification-dropdown ul li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 0.9rem;
            color: #333;
        }
        .header .notifications .notification-dropdown ul li:last-child {
            border-bottom: none;
        }
        .header .notifications .notification-dropdown .notification-time {
            font-size: 0.8rem;
            color: #666;
            display: block;
        }
        .stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .stats .stat-block {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            width: 200px;
            text-align: center;
        }
        .stats .stat-block h3 {
            margin: 0;
            font-size: 2rem;
        }
        .stats .stat-block p {
            margin: 5px 0 0;
            font-size: 1rem;
            color: #666;
        }
    </style>
</head>
<body>
    <input type="checkbox" id="menu-toggle">
    <div class="sidebar">
        <div class="side-header">
            <h3>Fit<span>Nepal</span></h3>
        </div>
        
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url(img/3.jpeg)"></div>
                <h4><?php echo $username; ?></h4>
            </div>
            
            <div class="side-menu">
                <ul>
                    <li>
                        <a href="#" class="active">
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                        <a href="user.php">
                            <span class="las la-user-alt"></span>
                            <small>Users</small>
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
                            <span class="las la-envelope"></span>
                            <small>Orders</small>
                        </a>
                    </li>
                    <li>
                        <a href="membership.php">
                            <span class="las la-calendar"></span>
                            <small>Members</small>
                        </a>
                    </li>
                    <li>
                        <a href="show_product.php">
                            <span class="las la-box"></span>
                            <small>Store</small>
                        </a>
                    </li>
                    <li>
                        <a href="offers.php">
                            <span class="las la-box"></span>
                            <small>offer</small>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <header class="header">
            <div class="header-content">
                <label for="menu-toggle">
                    <span class="las la-bars"></span>
                </label>
                
                <div class="header-menu">
                    <h2>Welcome, <?php echo $username; ?>!</h2>
                    <div class="user">
                        <a href="logout.php">Logout</a>
                    </div>
                    <div class="notifications">
                        <span class="las la-bell"></span>
                        <div class="notification-dropdown">
                            <ul>
                                <?php foreach ($notifications as $notification): ?>
                                    <li>
                                        <?php echo htmlspecialchars($notification['message']); ?>
                                        <span class="notification-time"><?php echo date('H:i', strtotime($notification['created_at'])); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="stats">
            <div class="stat-block">
                <h3><?php echo $userCount; ?></h3>
                <p>Users</p>
            </div>
            <div class="stat-block">
                <h3><?php echo $memberCount; ?></h3>
                <p>Members</p>
            </div>
            <div class="stat-block">
                <h3><?php echo $orderCount; ?></h3>
                <p>Orders</p>
            </div>
            <div class="stat-block">
                <h3><?php echo $storeCount; ?></h3>
                <p>Products</p>
            </div>
        </div>
    </div>
</body>
</html>
