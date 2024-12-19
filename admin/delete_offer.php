<?php
session_start();
include "../connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// if($_SESSION["user_role"] == '0'){
//   header("Location: {$hostname}/admin/post.php");
// }
$offer_id = $_GET['id'];//To get id 

echo $sql = "DELETE FROM offers WHERE id = {$offer_id}";

if(mysqli_query($conn, $sql)){
  header("Location: offers.php");
}else{
  echo "<p style='color:red;margin: 10px 0;'>Can\'t Delete the User Record.</p>";
}

mysqli_close($conn);

?>
