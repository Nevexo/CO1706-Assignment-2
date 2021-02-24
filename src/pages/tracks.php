<?php
session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}
require_once '../php/auth.php';
require_once '../php/tracks.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap Framework -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
          integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
          crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
          crossorigin="anonymous"></script>
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous"/>
  <!-- Local stylesheets -->
  <link rel="stylesheet" href="../css/stylesheet.css"/>
  <title>All Tracks | EcksMusic</title>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
  <!-- Navigation Bar-->
  <div class="container-fluid">
    <h2><a class="navbar-brand" href="#">EcksMusic 2 <span class="badge badge-warning">Beta</span></a></h2>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav mr-auto">
        <li class="navbar-item">
          <a class="nav-link" href="../">Home</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="#">Tracks</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="#">Albums</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="#">Search</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <?php
        echo '<li class="nav-item dropdown">';

        echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown">';
        echo $user->Username . ' <span class="badge badge-secondary">' . $user->PricingPlan->Name . '</span></a>';
        echo '<div class="dropdown-menu dropdown-menu-right">';
        echo '<a class="dropdown-item" href="#">Account Settings</a>';
        echo '<a class="dropdown-item" href="../php/logout.php">Logout</a>';
        echo '</div></li>';
        ?>
      </ul>
    </div>
  </div>
</nav>
<div class="jumbotron">
  <h1 class="display-4">Tracks</h1>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <img class="card-img-top" src="../images/chapintherecess.jpg" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title">
            Taking A Seat
          </h5>
          <p>
            <span class="badge badge-warning"><span class="fas fa-star"></span> Recommended for You</span>
            <span class="badge badge-info"><span class="fas fa-music"></span> Genre: Rap</span>
            <span class="badge badge-success"><span class="fas fa-user-edit"></span> Average Rating: 9.3</span>
          </p>
          <p class="text-muted"><span title="Artist" class="fas fa-users"></span> Disoriented Soundrel</p>
          <p class="text-muted"><span title="Album" class="fas fa-compact-disc"></span> Chap In The Recess</p>
          <a href="#" class="card-link">More Info</a>
          <a href="#" class="card-link">Add to Playlist</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card" >
        <img class="card-img-top" src="../images/chapintherecess.jpg" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title">
            Repeat Attempt
          </h5>
          <p>
            <span class="badge badge-info"><span class="fas fa-music"></span> Genre: Rap</span>
            <span class="badge badge-success"><span class="fas fa-user-edit"></span> Average Rating: 9.3</span>
          </p>
          <p class="text-muted"><span title="Artist" class="fas fa-users"></span> Disoriented Soundrel</p>
          <p class="text-muted"><span title="Album" class="fas fa-compact-disc"></span> Chap In The Recess</p>
          <a href="#" class="card-link">More Info</a>
          <a href="#" class="card-link">Add to Playlist</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card" >
        <img class="card-img-top" src="../images/getabraded.jpg" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title">
            Memo From The Void
          </h5>
          <p>
            <span class="badge badge-info"><span class="fas fa-music"></span> Genre: Rap</span>
            <span class="badge badge-success"><span class="fas fa-user-edit"></span> Average Rating: 9.3</span>
          </p>
          <p class="text-muted"><span title="Artist" class="fas fa-users"></span> Deceased Rod3nt</p>
          <p class="text-muted"><span title="Album" class="fas fa-compact-disc"></span> Get Abraded</p>
          <a href="#" class="card-link">More Info</a>
          <a href="#" class="card-link">Add to Playlist</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card" >
        <img class="card-img-top" src="../images/daybreaktriumph.jpg" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title">
            A Number May Affirm
          </h5>
          <p>
            <span class="badge badge-info"><span class="fas fa-music"></span> Genre: Rock</span>
            <span class="badge badge-success"><span class="fas fa-user-edit"></span> Average Rating: 9.3</span>
          </p>
          <p class="text-muted"><span title="Artist" class="fas fa-users"></span> Watering Hole</p>
          <p class="text-muted"><span title="Album" class="fas fa-compact-disc"></span> Daybreak Triumph</p>
          <a href="#" class="card-link">More Info</a>
          <a href="#" class="card-link">Add to Playlist</a>
        </div>
      </div>
    </div>
  </div>
</div>


</body>
</html>