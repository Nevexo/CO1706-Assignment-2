<?php

// EcksMusic Home Page
// Cameron Paul Fleming - 2021

session_start();
require_once 'php/auth.php';
require_once 'php/offers.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);
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
            <a class="nav-link" href="pages/tracks.php">Tracks</a>
          </li>
          <li class="navbar-item">
            <a class="nav-link" href="pages/albums.php">Albums</a>
          </li>
          <li class="navbar-item">
            <a class="nav-link" href="pages/search.php">Search</a>
          </li>
          <li class="navbar-item">
            <a class="nav-link" href="pages/playlist.php">Playlists</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
        <?php
          if (isset($_SESSION['User'])) {
            echo '<li class="nav-item dropdown">';
            echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown">';
            echo $user->Username . ' <span class="badge badge-secondary">' . $user->PricingPlan->Name . '</span></a>';
            echo '<div class="dropdown-menu dropdown-menu-right">';
            echo '<a class="dropdown-item" href="pages/recommendations.php">Recommended Tracks</a>';
            echo '<a class="dropdown-item" href="pages/account.php">Account Settings</a>';
            echo '<a class="dropdown-item" href="php/logout.php">Logout</a>';
            echo '</div></li>';
          } else {
            echo '<li class="navbar-item"><div class="btn-group" role="group">
                  <a href="pages/login.php" class="btn btn-outline-warning">Login</a>';
            echo '<a href="pages/register.php" class="btn btn-warning">Register</a>
                  </div></li>';
          }
        ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Offers Carousel -->

  <?php
    $offers = Offers::getAllOffers();
  ?>

  <!-- Adapted from bootstrap documentation examples -->
  <!-- https://getbootstrap.com/docs/4.0/components/carousel/ -->
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
          echo '<h5>' . $offers[$i]->Name . '</h5><p>' . $offers[$i]->Description . ' - £' . $offers[$i]->Price . '/mo</p>';
          if (!isset($_SESSION['User'])) {
            // Show register button if the user isn't logged in.
            echo '<p><a class="btn btn-primary" href="pages/register.php?setOfferId=' . $offers[$i]->Id . '">Register Now</a></p>';
          } else {
            // Show switch to this plan button if the user is logged in.
            if ($user->PricingPlanId != $offers[$i]->Id) {
              echo '<p><a class="btn btn-secondary" href="pages/account.php?newPricingPlan=' . $offers[$i]->Id . '">Switch to This Plan</a></p>';
            }
          }
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
  <div class="jumbotron">
    <?php
      // If the user is logged in, get a recommended track for them
      if (isset($user))
      {
        $Recom = Recommendations::getForUser($user->Id);
        if (count($Recom) != 0) {
          // Recommendations are available, get a random track
          $track = $Recom[random_int(0, count($Recom) - 1)];
        } else {
          // No recommendations available, use a random track.
          $track = Tracks::random(1)[0];
        }
      } else {
        // The user isn't logged in, display a random track.
        $track = Tracks::random(1)[0];
      }
    ?>
    <div class="row">
      <div class="col-md-2">
        <img src="<?php echo $track->ImagePath; ?>" class="img-thumbnail img-fluid">
      </div>
      <div class="col-md-10">
        <span class="lead font-italic">
          <?php
            if (isset($user)) echo "Recommended Track for You"; else echo "Random Track of the Day";
          ?>
        </span>
        <h1 class="display-4">
          <a href="pages/track.php?id=<?php echo $track->Id; ?>">
            <?php echo $track->Name; ?>
          </a>
        </h1>
        <span class="lead">
          <span title="Artist" class="fas fa-users"></span>
          <?php echo $track->Artist->Name ?>
           |
          <span title="Album" class="fas fa-compact-disc"></span>
          <?php echo $track->Album->Name ?>
        </span>
        <?php
          if (!isset($user))
            echo '<p><span class="text-muted">Login to see tracks recommended for you.</span></p>';
          else if (count($Recom) == 0)
            echo '<p><span class="text-muted">Review some tracks to customise this area.</span></p>';
        ?>
      </div>
    </div>
  </div>

<!-- Offer information cards -->
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        Available Platform Offers
      </div>
      <div class="card-body">
        <div class="row">
          <?php
          foreach($offers as $offer)
          {
            echo '
            <div class="col-md-4">
              <div class="card">
                <img class="card-img-top" src=' . $offer->ImagePath . ' alt="' . $offer->Name . ' Offer Image">
                <div class="card-body">
                  <h5 class="card-title">' . $offer->Name . ' - £' . $offer->Price . '/mo</h5>
                  <p class="card-text">' . $offer->Description . '</p>';

            // Display join/change plan buttons
            if (isset($user)){
              if ($user->PricingPlanId != $offer->Id)
                echo '<a class="btn btn-secondary" 
                      href="pages/account.php?newPricingPlan=' . $offer->Id . '">Switch to This Plan</a>';
            }else {
              echo '<a class="btn btn-warning" 
                      href="pages/register.php?setOfferId=' . $offer->Id . '">Register</a>';
            }
            echo '</div></div></div>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>