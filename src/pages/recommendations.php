<?php

// Track Search Frontend Page
// Cameron Paul Fleming - 2021

session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';
require_once '../php/music.php';
require_once '../php/recommend.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);

// Check for the refresh button being pressed
if (isset($_POST['refreshRec']))
{
  Recommendations::update($user);
}

// Get recommendations for this user.
try {
  $Recommend = Recommendations::getForUser($user->Id);
} catch (Exception $e) {
  header('Location: ../');
  die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap Framework -->
  <!-- https://getbootstrap.com/ & https://jquery.com/ -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
          integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
          crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
          crossorigin="anonymous"></script>
  <!-- FontAwesome Icons -->
  <!-- https://fontawesome.com/ -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous"/>
  <!-- Local stylesheets -->
  <link rel="stylesheet" href="../css/stylesheet.css"/>
  <title>Recommendations | EcksMusic</title>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
  <!-- Navigation Bar-->
  <div class="container-fluid">
    <h2><a class="navbar-brand" href="../">EcksMusic 2 <span class="badge badge-warning">Beta</span></a></h2>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav mr-auto">
        <li class="navbar-item">
          <a class="nav-link" href="../">Home</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="tracks.php">Tracks</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="albums.php">Albums</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="#">Search</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="playlist.php">Playlists</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <?php
        echo '<li class="nav-item dropdown">';

        echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown">';
        echo $user->Username . ' <span class="badge badge-secondary">' . $user->PricingPlan->Name . '</span></a>';
        echo '<div class="dropdown-menu dropdown-menu-right">';
        echo '<a class="dropdown-item" href="account.php">Account Settings</a>';
        echo '<a class="dropdown-item" href="../php/logout.php">Logout</a>';
        echo '</div></li>';
        ?>
      </ul>
    </div>
  </div>
</nav>
<div class="jumbotron">
  <h1 class="display-4">Recommended for You</h1>
  <p class="lead">EcksMusic recommends songs based on your existing reviews, we'll refresh these everytime you
  update, create or delete a review.</p>
</div>

<div class="container-fluid">
  <?php
    if (count($Recommend) == 0)
    {
      // Nothing is available to recommend, display a warning.
      echo '<div class="card">
              <div class="card-header">No Recommendations Available</div>
              <div class="card-body">
                Recommendations can only be generated if you\'ve reviewed at least one track.<br>
                Please visit <a href="tracks.php">the Tracks page</a> and review some tracks, then come back here later.
              </div>
            </div>';
    } else
    {
      // Tracks are available, display them.
      echo '<div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                Tracks
                <form method="post" action="#">
                <button type="submit" name="refreshRec" class="btn btn-sm btn-warning">Refresh</button>
                </form>
              </div>
              <div class="card-body">
                <div class="row">';

      // Pretty print all tracks, no need to tag the tracks as "recommend for you"
      foreach($Recommend as $Track)
      {
        echo '
          <div class="col-md-3">
            ' . $Track->prettyPrint(true) . '
          </div>
        ';
      }

      // Close the card divs.
      echo '</div></div></div>';
    }
  ?>
</body>
</html>