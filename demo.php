
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container"><br>
      <a class="btn btn-primary float-right" href="admin/login.php">Login</a>
          <h1 class="text-center">Welcome to Student Management System!</h1><br>

          <div class="row">
            <div class="col-md-4 offset-md-4">
              <form method="POST">
            <table class="text-center infotable">
              <tr>
                <th colspan="2">
                  <p class="text-center">Student Information</p>
                </th>
              </tr>
              <tr>
                <td>
                   <p>Choose Class</p>
                </td>
                <td>
                   <select class="form-control" name="choose">
                     <option value="">
                       Select
                     </option>
                     <option value="1st">
                       1St
                     </option>
                     <option value="2nd">
                       2nd
                     </option>
                     <option value="3rd">
                       3rd
                     </option>
                     <option value="4th">
                       4th
                     </option>
                     <option value="5th">
                       5th
                     </option>
                   </select>
                </td>
              </tr>

              <tr>
                <td>
                  <p><label for="roll">Roll No</label></p>
                </td>
                <td>
                  <input class="form-control" type="text" pattern="[0-9]{6}" id="roll" placeholder="Roll Num.." name="roll">
                </td>
              </tr>
              <tr>
                <td colspan="2" class="text-center">
                  <input class="btn btn-danger" type="submit" name="showinfo">
                </td>
              </tr>
            </table>
          </form>
            </div>
          </div>
        <br>
        <?php if (isset($_POST['showinfo'])) {
          $choose= $_POST['choose'];
          $roll = $_POST['roll'];
          if (!empty($choose && $roll)) {
            $query = mysqli_query($db_con,"SELECT * FROM `student_info` WHERE `roll`='$roll' AND `class`='$choose'");
            if (!empty($row=mysqli_fetch_array($query))) {
              if ($row['roll']==$roll && $choose==$row['class']) {
                $stroll= $row['roll'];
                $stname= $row['name'];
                $stclass= $row['class'];
                $city= $row['city'];
                $photo= $row['photo'];
                $pcontact= $row['pcontact'];
              ?>
        <div class="row">
          <div class="col-sm-6 offset-sm-3">
            <table class="table table-bordered">
              <tr>
                <td rowspan="5"><h3>Student Info</h3><img class="img-thumbnail" src="admin/images/<?= isset($photo)?$photo:'';?>" width="250px"></td>
                <td>Name</td>
                <td><?= isset($stname)?$stname:'';?></td>
              </tr>
              <tr>
                <td>Roll</td>
                <td><?= isset($stroll)?$stroll:'';?></td>
              </tr>
              <tr>
                <td>Class</td>
                <td><?= isset($stclass)?$stclass:'';?></td>
              </tr>
              <tr>
                <td>City</td>
                <td><?= isset($city)?$city:'';?></td>
              </tr>
              <tr>
                <td>Submit Date</td>
                <td><?= isset($pcontact)?$pcontact:'';?></td>
              </tr>
            </table>
          </div>
        </div>  
      <?php 
          }else{
                echo '<p style="color:red;">Please Input Valid Roll & Email</p>';
              }
            }else{
              echo '<p style="color:red;">Your Input Doesn\'t Match!</p>';
            }
            }else{?>
              <script type="text/javascript">alert("Data Not Found!");</script>
            <?php }
          }; ?>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>






 <!-- ...............News Slider................ -->
    <!-- <div class="page4 swiper">
        <div class="container">
            <h1>Latest News</h1>
            <div class="latest-news mySwiper">
                <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <img src="latest.webp" alt="">
                    <h2>student memberships</h2>
                    <a href="">Read More</a>
                </div>
                <div class="swiper-slide">
                    <img src="latest2.webp" alt="">
                    <h2>hyrox</h2>
                    <a href="">Read More</a>
                </div>
                <div class="swiper-slide">
                    <img src="latest3.webp" alt="">
                    <h2>female fitness classes</h2>
                    <a href="">Read More</a>
                </div>
                <div class="swiper-slide">
                    <img src="latest4.webp" alt="">
                    <h2>reducing carbon emissions</h2>
                    <a href="">Read More</a>
                </div>
                <div class="swiper-slide">
                    <img src="latest5.webp" alt="">
                    <h2>nhs for charities</h2>
                    <a href="">Read More</a>
                </div>
                <div class="swiper-slide">
                    <img src="latest6.webp" alt="">
                    <h2>free fiit launches</h2>
                    <a href="">Read More</a>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
             <button>Join Now</button> -->
        <!-- </div>
        </div>
        
    </div>  -->