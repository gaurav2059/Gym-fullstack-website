<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post</title>
    <link rel="stylesheet" href="css/update_store_post.css">
</head>
<body>
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="admin-heading">Update Post</h1>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <!-- Form for edit -->
                <?php
                include "connection.php";

                // Check if the 'id' is set in the POST array
                if(isset($_GET['id'])) {
                    $equipment_id = $_GET['id'];
                    $sql = "SELECT * FROM store_post WHERE id = '{$equipment_id}'";
                    $result = mysqli_query($conn, $sql) or die("Query Failed.");

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                ?>
                       <form action="save-update-post.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group">
                        <input type="hidden" name="id" class="form-control" value="<?php echo $row['id']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="equipment_name">Equipment Name</label>
                        <input type="text" name="equipment_name" class="form-control" id="equipment_name" value="<?php echo $row['equipment_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" name="price" class="form-control" id="price" value="<?php echo $row['price']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="new-image">Post Image</label>
                        <input type="file" name="new-image" id="new-image">
                        <img src="upload/<?php echo $row['image']; ?>" height="150px">
                        <input type="hidden" name="old_image" value="<?php echo $row['image']; ?>">
                    </div>
                    <input type="submit" name="submit" class="btn btn-primary" value="Update">
                </form>
                <!-- Form End -->
                <?php
                        }
                    } else {
                        echo "Result Not Found.";
                    }
                } else {
                    echo "Equipment ID is not provided.";
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
