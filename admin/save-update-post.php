<?php
include "connection.php";
session_start();

  
// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['submit'])) {
    // Ensure that the 'id' field is set in the POST data
    if(isset($_POST['id'])) {
        $equipment_id = $_POST['id'];
        $equipment_name = mysqli_real_escape_string($conn, $_POST['equipment_name']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        
        // Check if a new image is uploaded
        if(isset($_FILES['new-image']['name']) && $_FILES['new-image']['name'] != ""){
            $file_name = $_FILES['new-image']['name'];
            $file_size = $_FILES['new-image']['size'];
            $file_tmp = $_FILES['new-image']['tmp_name'];
            $file_type = $_FILES['new-image']['type'];
            $file_ext = strtolower(end(explode('.', $file_name)));
            
            $extensions = array("jpeg", "jpg", "png");
            
            if(in_array($file_ext, $extensions) === false) {
                echo "Error: This extension file not allowed. Please choose a JPG or PNG file.";
                exit();
            }
            
            if($file_size > 5097152) {
                echo "Error: File size must be 2mb or lower.";
                exit();
            }
            
            $new_name = time()."-".basename($file_name);
            $target = "upload/".$new_name;
            
            // Move uploaded file to the upload directory
            move_uploaded_file($file_tmp, $target);
            
            // Delete old image file
            $old_image = $_POST['old_image'];
            unlink("upload/".$old_image);
        } else {
            // Keep the old image if no new image is uploaded
            $new_name = $_POST['old_image'];
        }
        
        // Update data in the database
        $sql = "UPDATE store_post SET equipment_name = '{$equipment_name}', price = '{$price}', image = '{$new_name}' WHERE id = {$equipment_id}";

        if(mysqli_query($conn, $sql)) {
            header("Location: show_store_post.php"); // Redirect to the update page with the same equipment ID
            exit(); // Exit after redirection
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: 'id' field is not set in the POST data.";
    }
}
?>
