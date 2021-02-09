<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
Confirm_Login(); ?>

 <?php
  if(isset($_POST["Submit"])){                                        //setting connection of name submit
    $UserName = $_POST["Username"];
    $Name = $_POST["Name"];
    $Password = $_POST["Password"];
    $ConfirmPassword = $_POST["ConfirmPassword"];
    $Admin = $_SESSION["UserName"];
    date_default_timezone_set("Asia/Kolkata");
    $CurrentTime=time();
    $DateTime=strftime("%B-%d-%Y %H: %M: %S",$CurrentTime);


    if(empty($UserName)||empty($Password)||empty($ConfirmPassword)){                                              //assigning variable to name of "CatagoryTitle"
        $_SESSION["ErrorMessage"] = "All fields must be filled out";
        Redirect_to("Admins.php");
    }elseif (strlen($Password)<4){
        $_SESSION["ErrorMessage"] = "Password should be greater than 3 character";
        Redirect_to("Admins.php");
    }elseif ($Password !== $ConfirmPassword) {
        $_SESSION["ErrorMessage"] = "Password and confirm password should match";
        Redirect_to("Admins.php");
    }elseif (CheckUserNameExistsOrNot($UserName)) {
        $_SESSION["ErrorMessage"] = "Username Exists. Try Another One!";
        Redirect_to("Admins.php");
    }
    else{
       //Query to insert new admin in database when everything is good
        global $ConnectingDB;
        $sql = "INSERT INTO admins(datetime,username,password,aname,addedby)VALUES(:dateTime,:userName,:password,:aName,:adminName)";
        $stmt = $ConnectingDB->prepare($sql);

        //binding the values
        $stmt->bindValue(':dateTime',$DateTime);
        $stmt->bindValue(':userName',$UserName);
        $stmt->bindValue(':password',$Password);
        $stmt->bindValue(':aName',$Name);
        $stmt->bindValue(':adminName',$Admin);
        $Execute=$stmt->execute();

        if($Execute){
            $_SESSION["SuccessMessage"]="New Admin with the username of ".$UserName." added Successfully";
            Redirect_to("Admins.php");
        }else{
            $_SESSION["ErrorMessage"]= "something went wrong... Try Again !";
            Redirect_to("Admins.php");
        }
    }

  }

  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scal=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/7f6ee3d237.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <title>Admin </title>
</head>
<body>
<?php  ?>
<!--NAVIGATION BAR-->
<div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a href="#" class="navbar-brand " style= "color:aliceblue;">MindSaga</a>
        <button style="background-color: #BEC9F2;" class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#Rcollapse">
            <span class="navbar-toggle-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="Rcollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="MyProfile.php" class="nav-link"><i class="fas fa-user text-success"></i> My Profile</a>
                </li>
                <li class="nav-item">
                    <a href="Dashboard.php" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="Posts.php" class="nav-link">Posts</a>
                </li>
                <li class="nav-item">
                    <a href="Categories.php" class="nav-link">Categories</a>
                </li>

                <li class="nav-item">
                    <a href="Admins.php" class="nav-link">Manage Admins</a>
                </li>
                <li class="nav-item">
                    <a href="Comments.php" class="nav-link">Comments</a>
                </li>
                <li class="nav-item">
                    <a href="index.php" class="nav-link">GO Live</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="Logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

    </div>
</div>
<!--NAVBAR-->

<!--HEADER-->
<header class="bg-dark text-white py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><i class="fas fa-user" style="color:#27aae1;"></i> Manage Admins</h1>
            </div>
        </div>
    </div>
</header>
<!--HEADER-->

<!--MAIN AREA-->
<section class="container py-2 mb-4">
  <div class="row">
      <div class="offset-lg-1 col-lg-10" style="min-height: 540px;">
          <?php  echo ErrorMessage();
                 echo SuccessMessage();
          ?>
          <form class="" action="Admins.php" method="post">
              <div class="card bg-secondary text-light mb-3">
                  <div class="card-header">
                      <h1>Add New Admin</h1>
                  </div>
                  <div class="card-body bg-dark">
                      <div class="form-group">
                          <label for="username"><span class="fieldinfo"> Username:</span></label>
                          <input class="form-control" type="text" name="Username" id="username" value="">
                      </div>
                      <div class="form-group">
                          <label for="Name"><span class="fieldinfo"> Name:</span></label>
                          <input class="form-control" type="text" name="Name" id="Name" value="">
                          <small class="text-warning text-muted">Optional</small>
                      </div>
                      <div class="form-group">
                          <label for="Password"><span class="fieldinfo"> Password:</span></label>
                          <input class="form-control" type="password" name="Password" id="Password" value="">
                      </div>
                      <div class="form-group">
                          <label for="ConfirmPassword"><span class="fieldinfo"> Confirm Password:</span></label>
                          <input class="form-control" type="Password" name="ConfirmPassword" id="ConfirmPassword" value="">
                      </div>
                      <div class="row">
                          <div class="col-lg-6 mb-2">
                              <a href="Dashboard.php" class="btn btn-warning btn-block"> <i class="fas fa-arrow-left"></i> Back to dashboard</a>
                          </div>
                          <div class="col-lg-6 mb-2">
                              <button type="Submit" name="Submit" class="btn btn-success btn-block">
                                  <i class="fas fa-check"></i> Publish
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </form>

           <!--  deleting admins-->
          <h2>Existing Admins</h2>
          <div class="table-responsive">
          <table class="table table-striped table-hover">
              <thead class="thead-dark">
              <tr>
                  <th>No. </th>
                  <th>Date&Time</th>
                  <th>Username</th>
                  <th>Admin name</th>
                  <th>Added by</th>
                  <th>Action</th>
              </tr>
              </thead>
              <?php
              global $ConnectingDB;
              $sql = "SELECT * FROM admins  ORDER BY id desc";
              $Execute = $ConnectingDB->query($sql);
              $SrNo = 0;
              while ($DataRows=$Execute->fetch()){
                  $AdminId = $DataRows["id"];
                  $DateTime= $DataRows["datetime"];
                  $AdminUsername = $DataRows["username"];
                  $AdminName = $DataRows["aname"];
                  $AddedBy = $DataRows["addedby"];
                  $SrNo++;
                  ?>
                  <tbody>
                  <tr>
                      <td><?php echo htmlentities($SrNo); ?></td>
                      <td><?php echo htmlentities($DateTime); ?></td>
                      <td><?php echo htmlentities($AdminUsername); ?></td>
                      <td><?php echo htmlentities($AdminName); ?></td>
                      <td><?php echo htmlentities($AddedBy); ?></td>
                      <td><a href="DeleteAdmin.php?id=<?php echo $AdminId;?>" class="btn btn-danger">Delete</a></td>
                  </tr>
                  </tbody>
              <?php }?>
          </table>
          </div>
      </div>
  </div>
</section>

<!--FOOTER-->
<div style="height: 5px; background: cornflowerblue"></div>
<footer class="bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col">
                <p class="lead text-center">THINK HARD  |  <span id="year"></span>&copy: -----All right reserved</p>
            </div>
        </div>
    </div>
</footer>
<div style="height: 5px; background: cornflowerblue"></div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script>
    $('#year').text(new Date().getFullYear());
</script>
</body>
</html>

