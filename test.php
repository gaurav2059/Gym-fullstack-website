<?php
session_start(); // Start session if not already started

// Database connection parameters (adjust as per your setup)
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "dbname";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch site header information
$sql = "SELECT site_name, site_logo, currency_format FROM options LIMIT 1";
$result = mysqli_query($conn, $sql);

$header = mysqli_fetch_assoc($result);

// Default currency format
$cur_format = '$';
if (!empty($header['currency_format'])) {
    $cur_format = $header['currency_format'];
}

// Start HTML document
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'OnlineShop'; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900|Montserrat:400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
</head>
<body>
<div id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <?php if (!empty($header['site_logo'])): ?>
                    <a href="<?php echo $hostname; ?>" class="logo-img"><img src="images/<?php echo $header['site_logo']; ?>" alt=""></a>
                <?php else: ?>
                    <a href="<?php echo $hostname; ?>" class="logo"><?php echo $header['site_name']; ?></a>
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <form action="search.php" method="GET">
                    <div class="input-group search">
                        <input type="text" class="form-control" name="search" placeholder="Search for...">
                        <span class="input-group-btn">
                            <input class="btn btn-default" type="submit" value="Search">
                        </span>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <ul class="header-info">
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                            <?php if (isset($_SESSION["user_role"])): ?>
                                Hello <?php echo $_SESSION["username"]; ?><i class="caret"></i>
                            <?php else: ?>
                                <i class="fa fa-user"></i>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (isset($_SESSION["user_role"])): ?>
                                <li><a href="user_profile.php">My Profile</a></li>
                                <li><a href="user_orders.php">My Orders</a></li>
                                <li><a href="logout.php" class="user_logout">Logout</a></li>
                            <?php else: ?>
                                <li><a data-toggle="modal" data-target="#userLogin_form" href="#">Login</a></li>
                                <li><a href="register.php">Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li><a href="wishlist.php"><i class="fa fa-heart"></i>
                            <?php if (isset($_COOKIE['wishlist_count'])) echo '<span>' . $_COOKIE["wishlist_count"] . '</span>'; ?>
                        </a></li>
                    <li><a href="cart.php"><i class="fa fa-shopping-cart"></i>
                            <?php if (isset($_COOKIE['cart_count'])) echo '<span>' . $_COOKIE["cart_count"] . '</span>'; ?>
                        </a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="header-menu">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="menu-list">
                    <?php
                    $sql = "SELECT * FROM sub_categories WHERE cat_products > 0 AND show_in_header = '1'";
                    $result = mysqli_query($conn, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($res = mysqli_fetch_assoc($result)) {
                            echo '<li><a href="category.php?cat=' . $res['sub_cat_id'] . '">' . $res['sub_cat_title'] . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userLogin_form" tabindex="-1" role="dialog">
    <!-- Your modal content -->
</div>
<!-- /Modal -->

<!-- JavaScript dependencies (Bootstrap, jQuery) should be included at the end -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<!-- Your custom scripts -->
<script src="js/custom.js"></script>
</body>
</html>

<?php
// Close MySQL connection
mysqli_close($conn);
?>
