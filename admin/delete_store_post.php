<?php
  include "connection.php";
  session_start();

  
  // Check if admin is logged in
  if (!isset($_SESSION['admin_id'])) {
      header("Location: index.php");
      exit();
  }
  
  $equipment_id = $_GET['id'];


  $sql1 = "SELECT * FROM store_post WHERE id = {$equipment_id}";
  $result = mysqli_query($conn, $sql1) or die("Query Failed : Select");
  $row = mysqli_fetch_assoc($result);

  unlink("upload/".$row['image']);

  $sql = "DELETE FROM store_post WHERE id = {$equipment_id}";
 

  if(mysqli_multi_query($conn, $sql)){
    header("Location: show_store_post.php");
  }else{
    echo "<p style='color:red;margin: 10px 0;'>Can\'t Delete the User Record.</p>";
  }
  
?>
