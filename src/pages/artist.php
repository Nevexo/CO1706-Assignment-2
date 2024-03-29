<?php

// Artist Information Frontend Page
// Cameron Paul Fleming - 2021

session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';
require_once '../php/music.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);
// Redirect user if there's no album ID selected
if (!isset($_GET['id'])) {
  header('Location: ../');
  die();
}

// Get the artist object from the database
// Then create an array of Tracks for this artist.
try {
  $Artist = Artists::get($_GET['id']);
  $Tracks = Tracks::getArtist($Artist->Id);
} catch (Exception $e) {
  // Cannot find this artist (or something went wrong on the database, redirect to home page)
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
  <title><?php echo $Artist->Name; ?> | EcksMusic</title>
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
          <a class="nav-link" href="search.php">Search</a>
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
        echo '<a class="dropdown-item" href="recommendations.php">Recommended Tracks</a>';
        echo '<a class="dropdown-item" href="account.php">Account Settings</a>';
        echo '<a class="dropdown-item" href="../php/logout.php">Logout</a>';
        echo '</div></li>';
        ?>
      </ul>
    </div>
  </div>
</nav>

<div class="jumbotron">
  <div class="row">
    <div class="col">
      <h1 class="display-4">
        <?php echo $Artist->Name; ?>
      </h1>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-9">
      <div class="card">
        <div class="card-header">
          Tracks
        </div>
        <div class="card-body">
          <div class="row">
            <?php
            foreach ($Tracks as $t) {
              echo '
              <div class="col-md-3">
                ' . $t->prettyPrint() . '
              </div>
            ';
            }
            ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3">
      <div class="card">
        <div class="card-header">
          Albums
        </div>
        <div class="card-body">
          <div class="row">
            <?php
            // Get albums by this user.
            try {
              $albums = Albums::getAll($Artist->Id);
            } catch (Exception $e) {
              echo '<div class="alert alert-danger" role="alert">Something went wrong loading this section.</div>';
            }

            foreach ($albums as $a) {
              echo '
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">' . $a->Name . '</h5>
                      <p class="card-text">' . $a->Artist->Name . '</p>
                      <a href="album.php?id=' . $a->Id . '" class="btn btn-warning">More Info</a>
                    </div>
                  </div>
                </div>
              ';
            }
            ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
</body>
</html>