<?php
session_start(); // Ensure session is started at the beginning

// Assume $_SESSION["user_type"] and $_SESSION["username"] are properly set upon login
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Add your custom CSS styles here */
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: 20px;
        }
        .dropdown-menu a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-menu {
    display: block;
}

    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <h1>Fit<span>Nepal</span></h1>
        </div>
        <div class="navbar">
            <ul id="menuList">
                <li><a href="index.php">Home</a></li>
                <li><a href="membership.php">Membership</a></li>
                <li><a href="trainner.php">Training</a></li>
                <li><a href="store.php">Store</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
            <ul class="header-info">
                <li class="dropdown" onmouseover="toggleDropdown(true)" onmouseout="toggleDropdown(false)">
                    <a class="dropdown-toggle" href="#">
                        <?php
                        if(isset($_SESSION["user_id"])) {
                            echo '<i class="fa fa-user"></i>';
                        } else {
                            echo '<i class="fa fa-user"></i>';
                        }
                        ?>
                    </a>
                    <ul id="dropdownMenu" class="dropdown-menu">
                        <?php if(isset($_SESSION["user_id"])) { ?>
                            <li><a href="profile.php">My Profile</a></li>
                            <li><a href="logout.php" class="user_logout">Logout</a></li>
                        <?php } else { ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <!-- Uncomment below if you have a "Join now" button -->
                <!-- <li><a href="register.php"><button>Join now</button></a></li> -->
            </ul>
        </div>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var dropdownMenu = document.getElementById("dropdownMenu");
            dropdownMenu.style.display = "none"; // Initially hide the dropdown menu
        });

        function toggleDropdown(show) {
            var dropdownMenu = document.getElementById("dropdownMenu");
            dropdownMenu.style.display = show ? 'block' : 'none';
        }
    </script>
</body>
</html>
