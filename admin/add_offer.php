<?php
include 'connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $title = $_POST['title'];
    $end_date = $_POST['end_date'];
    $promo_code = $_POST['promo_code'];
    $discount_percentage = $_POST['discount_percentage'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO offers (title, end_date, promo_code, discount_percentage) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $end_date, $promo_code, $discount_percentage);

    if ($stmt->execute()) { 
        echo "<div class='message success'>New offer added successfully.</div>";
    } else {
        echo "<div class='message error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Offer</title>
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

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px 0;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        form input[type="text"], form input[type="datetime-local"], form input[type="number"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #28a745;
        }

        form input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="offers.php" class="button">Back to Offers</a>
        <h2>Add New Offer</h2>
        <form method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br>
            <label for="end_date">End Date:</label>
            <input type="datetime-local" id="end_date" name="end_date" required><br>
            <label for="promo_code">Promo Code:</label>
            <input type="text" id="promo_code" name="promo_code" required><br>
            <label for="discount_percentage">Discount Percentage:</label>
            <input type="number" id="discount_percentage" name="discount_percentage" min="0" max="100" required><br>
            <input type="submit" value="Add Offer" class="button">
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
