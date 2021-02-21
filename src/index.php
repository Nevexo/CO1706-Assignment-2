<?php
session_start();
require_once 'php/auth.php';
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
  <title>Home | EcksMusic</title>
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
          <li class="navbar-item active">
            <a class="nav-link" href="#">Home</a>
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
        <!--Search Bar-->
<!--        <form class="form-inline my-2 my-lg-0">-->
<!--          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">-->
<!--          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
<!--        </form>-->
        <ul class="navbar-nav ml-auto">
        <?php
          if (isset($_SESSION['User'])) {
            echo '<li class="nav-item dropdown">';
            echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown">';
            echo $user->Username . '</a>';
            echo '<div class="dropdown-menu dropdown-menu-right">';
            echo '<a class="dropdown-item disabled" href="#">Settings</a>';
            echo '<a class="dropdown-item" href="/php/logout.php">Logout</a>';
            echo '</div></li>';
          } else {
            echo '<li class="navbar-item"><div class="btn-group" role="group">
                  <button type="button" onclick="location.href=`pages/register.php`" class="btn btn-outline-warning">Login</button>';
            echo '<button type="button" onclick="location.href=`pages/register.php`" class="btn btn-warning">Register</button>
                  </div></li>';
          }
        ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Offers Carousel -->

  <?php
    require_once "php/offers.php";

    $offers = Offers::getAllOffers();
  ?>

  <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <?php
        // Carousel position indicators
        for ($i=0; $i < count($offers); $i++) {
          if ($i == 0) {
            // Set first indicator to active
            echo '<li data-target="#carouselIndicators" data-slide-to="'. $i .'" class="active"></li>';
          } else {
            // Subsequent indicators will be activated at a later time.
            echo '<li data-target="#carouselIndicators" data-slide-to="'. $i .'"></li>';
          }
        }
      ?>
    </ol>
    <div class="carousel-inner">
      <?php
        // Add a carousel item for every offer in $offers (populated above from Offers::getAllOffers)
        for ($i=0; $i < count($offers); $i++) {
          echo $i;
          if ($i == 0) {
            // The first element should be active
            echo '<div class="carousel-item active">';
          }else {
            // Subsequent elements will be activated automatically.
            echo '<div class="carousel-item">';
          }

          // Add image/meta to the carousel
          echo '<img class="d-block w-100" src="'. $offers[$i]->ImagePath .'" alt="' . $offers[$i]->Name . '">';
          echo '<div class="carousel-caption d-none d-md-block text-dark">';
          echo '<h5>' . $offers[$i]->Name . '</h5><p>' . $offers[$i]->Description . '</p>';
          // Closing tags
          echo '</div></div>';
        }
      ?>
    </div>
    <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <!-- Jumbotron showing a random track or recommended track (if signed in -->
  <!--  TODO: Show track from recommendation engine if user is logged in-->
<!--  <div class="jumbotron">-->
<!--    <div class="row">-->
<!--      <div class="col-md-2">-->
<!--        <img src="images/getabraded.jpg" class="img-thumbnail img-fluid">-->
<!--      </div>-->
<!--      <div class="col-md-10">-->
<!--        <span class="lead font-italic">Track of The Day</span>-->
<!--        <h1 class="display-4">8 Binary Digits</h1>-->
<!--        <span class="lead">Deceased Rod3nt | Get Abraded</span>-->
<!--        <p class="text-muted font-italic">Login to see recommended tracks for you</p>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->

<!--TODO: Add cards for all levels with more detail (include price!)-->
</body>
</html>