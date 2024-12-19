<?php
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Button styles */
        .btn {
            background-color: #007bff; /* Light Blue */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3; /* Darker Blue */
        }

        /* Home button style */
        .home-btn {
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="home-btn">
        <a class="btn" href="admin_pannel.php">Home</a>
    </div>

    <table>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>User Type</th>
            <th colspan="2">Actions</th>
        </tr>
        <!-- PHP code to fetch data -->
        <?php
        $query = "SELECT * FROM users";
        $data = mysqli_query($conn, $query);
        $result = mysqli_num_rows($data);

        if ($result) {
            while ($row = mysqli_fetch_assoc($data)) {
                echo "<tr>
                        <td>{$row['fullname']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['user_type']}</td>
                        <td><a class='btn' href='update.php?id={$row['id']}'>Edit</a></td>
                        <td><a class='btn' href='delete-user.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                    </tr>";
            }
        } else {
            echo "<tr>
                    <td colspan='6'>No records found</td>
                  </tr>";
        }
        ?>
    </table>
    
</body>
</html>
