<?php
session_start();
include 'connection.php';
include 'header.php';

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to get membership request status
function getMembershipRequestStatus($user_id, $conn) {
    $query = "SELECT status FROM membership_requests WHERE user_id = ? AND status IN ('confirmed', 'pending', 'canceled') ORDER BY id DESC LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $status);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $status;
    } else {
        die('Query Error: ' . mysqli_error($conn));
    }
}

// Fetch available schedules with current member count
$schedules_sql = "
    SELECT s.id AS schedule_id, s.start_time, s.end_time, s.max_capacity, COUNT(m.user_id) AS current_capacity
    FROM schedules s
    LEFT JOIN membership_requests m ON s.id = m.schedule_id AND m.end_date > NOW()
    GROUP BY s.id
    ORDER BY s.start_time
";

$schedules_result = mysqli_query($conn, $schedules_sql);

if (!$schedules_result) {
    die('Error executing query: ' . mysqli_error($conn));
}

$membership_status = '';
if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $membership_status = getMembershipRequestStatus($user_id, $conn);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Classes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="css/membership.css">
    <link rel="stylesheet" href="css/modal.css">
    <style>
        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease, visibility 0.5s;
            z-index: 1000;
        }
    </style>
</head>
<body>
<div class="main-container">
    <h1>JOIN THE FAMILY</h1>
    <h4>UNLOCK 3 DAYS FREE TRIAL TO GET STARTED</h4>
</div>
<?php
// Check for a success message in the URL
// if (isset($_GET['message']) && isset($_GET['status']) && $_GET['status'] == 'success') {
//     $message = htmlspecialchars($_GET['message']);
//     echo "<div id='successMessage' class='success-message'>$message</div>";
// }
// ?>

<div class="memberships">
    <div class="plans">
        <?php
        $plans = [
            ['Basic Member', '1500/month', 'Full Day access of gym', 'Standard Support', 'Personal trainer'],
            ['Premium Member', '4000/3month', 'Full Day access of gym', 'Standard Support', 'Personal trainer'],
            ['Standard Member', '6000/6month', 'Full Day access of gym', 'Standard Support', 'Personal trainer']
        ];

        foreach ($plans as $index => $plan) {
            echo '<div class="planCard">';
            echo '<h2>' . $plan[0] . '</h2>';
            echo '<p>' . $plan[1] . '</p>';
            echo '<p>' . $plan[2] . '</p>';
            echo '<p>' . $plan[3] . '</p>';
            echo '<p>' . $plan[4] . '</p>';
            
            if (isLoggedIn()) {
                switch ($membership_status) {
                    case 'confirmed':
                        echo '<button disabled><span class="button-link">You Already Have a Membership</span></button>';
                        break;
                    case 'pending':
                        echo '<button disabled><span class="button-link">Pending Request</span></button>';
                        break;
                    case 'canceled':
                        echo '<button><a href="gym_register.php?plan=' . $index . '" class="button-link">Buy Now</a></button>';
                        break;
                    default:
                        echo '<button><a href="gym_register.php?plan=' . $index . '" class="button-link">Buy Now</a></button>';
                }
            } else {
                echo '<button><a href="login.php?redirect=gym_register.php?plan=' . $index . '" class="button-link">Login to Buy</a></button>';
            }
            echo '</div>';
        }
        ?>
    </div>
</div>

<div class="schedules">
    <h1>Available Gym Schedules</h1>
    <div class="schedule-list">
        <?php
        if (mysqli_num_rows($schedules_result) > 0) {
            while ($row = mysqli_fetch_assoc($schedules_result)) {
                $schedule_id = $row['schedule_id'];
                $start_time = $row['start_time'];
                $end_time = $row['end_time'];
                $current_capacity = $row['current_capacity'];
                $max_capacity = $row['max_capacity'];

                echo '<div class="schedule-card" data-schedule-id="' . $schedule_id . '">';
                echo '<h2>Time: ' . $start_time . ' - ' . $end_time . '</h2>';
                echo '<p>Current Capacity: ' . $current_capacity . '/' . $max_capacity . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No schedules available.</p>';
        }
        ?>
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Members List</h2>
        <ul id="modal-body">
            <!-- Members will be dynamically added here -->
        </ul>
    </div>
</div>

<div class="container">
    <button class="back-button" onclick="goBack()">Go Back</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var scheduleCards = document.querySelectorAll('.schedule-card');
    var modal = document.getElementById('modal');
    var modalBody = document.getElementById('modal-body');
    var closeBtn = document.querySelector('.close');

    scheduleCards.forEach(function(card) {
        card.addEventListener('click', function() {
            var scheduleId = card.getAttribute('data-schedule-id');

            fetch('fetch_members.php?schedule_id=' + scheduleId)
                .then(response => response.json())
                .then(data => {
                    modalBody.innerHTML = '';
                    data.forEach(member => {
                        var li = document.createElement('li');
                        li.textContent = member.name;
                        modalBody.appendChild(li);
                    });
                    modal.style.display = 'block';
                })
                .catch(error => console.error('Error fetching members:', error));
        });
    });

    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});

document.addEventListener('DOMContentLoaded', function() {
    var successMessage = document.getElementById('successMessage');

    if (successMessage) {
        // Show the message
        successMessage.style.visibility = 'visible';
        successMessage.style.opacity = '1';

        // Hide the message after 3 seconds
        setTimeout(function() {
            successMessage.style.opacity = '0';
            successMessage.style.visibility = 'hidden';
        }, 3000); // 3 seconds
    }
});

function goBack() {
    window.history.back();
}
</script>
<?php include 'footer.php'; ?>
</body>
</html>
