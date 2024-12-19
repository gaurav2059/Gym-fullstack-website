<?php
// Include the database connection script
require_once 'connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql_delete = "DELETE FROM offers WHERE id = ?";

    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<div class='message success'>Offer with ID $id deleted successfully.</div>";
    } else {
        echo "<div class='message error'>Error deleting offer: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Handle Edit Action - Display Edit Form
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql_select = "SELECT * FROM offers WHERE id = ?";

    $stmt = $conn->prepare($sql_select);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <div class="container">
            <a href="admin_pannel.php" class="button-home">Home</a>
            <h2>Edit Offer</h2>
            <form method="post" action="offers.php?action=update&id=<?php echo $id; ?>">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required><br>
                <label for="end_date">End Date:</label>
                <input type="datetime-local" id="end_date" name="end_date" value="<?php echo date('Y-m-d\TH:i', strtotime($row['end_date'])); ?>" required><br>
                <label for="promo_code">Promo Code:</label>
                <input type="text" id="promo_code" name="promo_code" value="<?php echo htmlspecialchars($row['promo_code']); ?>" required><br>
                <label for="discount_percentage">Discount Percentage:</label>
                <input type="number" id="discount_percentage" name="discount_percentage" value="<?php echo htmlspecialchars($row['discount_percentage']); ?>" required><br>
                <input type="submit" value="Update Offer" class="button-submit">
            </form>
            <br>
            <a href="offers.php" class="button-back">Back to Offers</a>
        </div>
        <?php
    } else {
        echo "<div class='message error'>Offer not found.</div>";
    }

    $stmt->close();
}

// Handle Update Action - Process Form Submission
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_GET['id']);
    $title = $_POST['title'];
    $end_date = $_POST['end_date'];
    $promo_code = $_POST['promo_code'];
    $discount_percentage = $_POST['discount_percentage'];

    $sql_update = "UPDATE offers SET title = ?, end_date = ?, promo_code = ?, discount_percentage = ? WHERE id = ?";

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssii", $title, $end_date, $promo_code, $discount_percentage, $id);

    if ($stmt->execute()) {
        echo "<div class='message success'>Offer with ID $id updated successfully.</div>";
    } else {
        echo "<div class='message error'>Error updating offer: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Function to delete expired offers
function deleteExpiredOffers($conn) {
    $current_date = date('Y-m-d H:i:s');  // Current date and time in the correct format
    $sql_delete_expired = "DELETE FROM offers WHERE end_date < ?";  // SQL query to delete expired offers

    $stmt = $conn->prepare($sql_delete_expired);
    $stmt->bind_param("s", $current_date);

    if ($stmt->execute()) {
        return true;  // Return true on successful deletion
    } else {
        echo "<div class='message error'>Error deleting expired offers: " . $stmt->error . "</div>";
        return false;  // Return false on failure
    }
}

// Call function to delete expired offers
deleteExpiredOffers($conn);

// Query to fetch all offers
$sql = "SELECT * FROM offers";
$result = $conn->query($sql);

// Display offers in a table
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Offers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .button-home, .button-back, .button-add, .button-delete, .button-submit {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px 0;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            border-radius: 5px;
            border: none;
        }

        .button-delete {
            background-color: #dc3545;
        }

        .button-home:hover, .button-back:hover, .button-add:hover, .button-delete:hover, .button-submit:hover {
            background-color: #0056b3;
        }

        .button-delete:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_pannel.php" class="button-home">Home</a>
        <h2>Manage Offers</h2>
        <table>
            <tr><th>ID</th><th>Title</th><th>End Date</th><th>Promo Code</th><th>Discount Percentage</th><th>Action</th></tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['promo_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['discount_percentage']) . "%</td>";
                    echo "<td><a href='offers.php?action=edit&id=" . htmlspecialchars($row['id']) . "' class='button-edit'>Edit</a> | ";
                    echo "<a href='offers.php?action=delete&id=" . htmlspecialchars($row['id']) . "' class='button-delete' onclick='return confirm(\"Are you sure you want to delete this offer?\")'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No offers found</td></tr>";
            }
            ?>
        </table>
        <br><a href="add_offer.php" class="button-add">Add New Offer</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
