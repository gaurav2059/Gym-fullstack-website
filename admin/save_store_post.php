<?php
  include "connection.php";
  session_start();

  
  // Check if admin is logged in
  if (!isset($_SESSION['admin_id'])) {
      header("Location: index.php");
      exit();
  }
  if(isset($_FILES['fileToUpload']['error'])){
    $errors = array();

    $file_name = $_FILES['fileToUpload']['name'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $file_type = $_FILES['fileToUpload']['type'];
    $file_ext = end(explode('.',$file_name));

    $extensions = array("jpeg","jpg","png");

    if(in_array($file_ext,$extensions) === false)
    {
      $errors[] = "This extension file not allowed, Please choose a JPG or PNG file.";
    }

    if($file_size > 2097152){
      $errors[] = "File size must be 2mb or lower.";
    }
    $new_name = time(). "-".basename($file_name);
    $target = "upload/".$new_name;

    if(empty($errors) == true){
      move_uploaded_file($file_tmp,$target);
    }else{
      print_r($errors);
      die();
    }
  }

  session_start();
  $equipment_name = mysqli_real_escape_string($conn, $_POST['equipment_name']);
  $price = mysqli_real_escape_string($conn, $_POST['price']);
 
 


  $sql = "INSERT INTO store_post(equipment_name,price,image)
          VALUES('{$equipment_name}','{$price}','{$new_name}')";
  

  if(mysqli_multi_query($conn, $sql)){
    header("Location: show_store_post.php");
  }else{
    echo "<div class='alert alert-danger'>Query Failed.</div>";
  }

?>
