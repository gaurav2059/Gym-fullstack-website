<!-- include "header.php";
if($_SESSION["user_role"] == '0'){
  header("Location: {$hostname}/admin/post.php");
} -->
<?php
if(isset($_POST['submit'])){
  include "connection.php";

  $userid = mysqli_real_escape_string($conn,$_POST['user_id']);
  $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
  $email = mysqli_real_escape_string($conn,$_POST['email']);
  $username = mysqli_real_escape_string($conn,$_POST['username']);
  $user_type = mysqli_real_escape_string($conn,$_POST['user_type']);

  $sql = "UPDATE users SET fullname = '{$fullname}', email = '{$email}', username = '{$username}' ,user_type = '{$user_type}' WHERE id = {$userid}";

  if(mysqli_query($conn, $sql)){
    header("Location: user.php");
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
<link rel="stylesheet" href="css/update.css">
</head>
<body>
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="admin-heading">Modify User Details</h1>
            </div>
            <div class="col-md-offset-4 col-md-4">
              <?php
              include "connection.php";
              $user_id = $_GET['id']; // fetch data by id
              $sql = "SELECT * FROM users WHERE id = {$user_id}";
              $result = mysqli_query($conn, $sql) or die("Query Failed.");
              if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
              ?>
                <!-- Form Start -->
                <form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="form-group">
                        <input type="hidden" name="user_id" class="form-control" value="<?php echo $row['id']; ?>">
                    </div>
                    <div class="form-group">
                        <label>FullName</label>
                        <input type="text" name="fullname" class="form-control" value="<?php echo $row['fullname']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $row['username']; ?>" required>
                    </div>
                   
                    <div class="form-group">
                        <label>User Type</label>
                        <select class="form-control" name="user_type">
                          <?php
                            if($row['user_type'] == 'admin'){
                              echo "<option value=' user'>Normal User</option>
                                    <option value='admin' selected>Admin</option>";
                            }else{
                              echo "<option value='user' selected>Normal User</option>
                                    <option value='admin'>Admin</option>";
                            }
                          ?>
                        </select>
                    </div>
                    <input type="submit" name="submit" class="btn btn-primary" value="Update" required />
                </form>
                <!-- /Form -->
                <?php
              }
            }
                 ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<!-- include "footer.php"; -->
