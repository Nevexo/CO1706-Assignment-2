<?php
session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';
require_once '../php/music.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);
// Redirect user if there's no track ID selected
if (!isset($_GET['id'])) header('Location: tracks.php');

// Get the track and place it in the $Track variable
try {
  $Track = Tracks::get($_GET['id']);
} catch (Exception $e) {
  // Cannot find this track, take the user back to the track listl
  if (!isset($_GET['id'])) header('Location tracks.php');
}
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
  <title><?php echo $Track->FullName; ?> | EcksMusic</title>
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
  <div class="row">
    <div class="col-auto">
      <img alt="Album thumbnail" src="../<?php echo $Track->ThumbPath; ?>"/>
    </div>
    <div class="col">
      <h1 class="display-4">
        <?php echo $Track->Name; ?>
      </h1>
      <p class="lead">
        <span class="text-muted fas fa-users"></span>
        <?php echo $Track->Artist->Name; ?>
      </p>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <!-- Left column - used for information -->
    <div class="col-md-6">
      <!-- Track sample -->
      <div class="card">
        <div class="card-header">
          Track Preview
        </div>
        <div class="card-body">
          <p class="card-text">Listen to a sample of this track</p>
          <audio controls src="../<?php echo $Track->SamplePath; ?>"></audio>
        </div>
      </div>
      <!-- Track information -->
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              Track Information
            </div>
            <div class="card-body">
              <span class="badge badge-warning"><span class="fas fa-star"></span> Recommended for You</span>
              <p class="card-text">
                <span title="Album" class="fas fa-music"></span> Genre: <i><?php echo $Track->Genre; ?> <br/></i>
                <span title="Album" class="fas fa-compact-disc"></span> Album: <i><?php echo $Track->Album->Name; ?></i>
              </p>
              <a href="#" class="card-link">More from This Artist</a>
              <a href="#" class="card-link">More in This Genre</a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              Track Description
            </div>
            <div class="card-body">
              <p class="card-text"><?php echo $Track->Description; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Right column - used for reviews -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          Reviews by EcksMusic Users
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Ronnie Pickering</h5>
                  <span class="badge badge-danger"><span class="fas fa-star"></span> 2/10</span>
                  <p class="card-text">This is a negative review</p>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Ed</h5>
                  <span class="badge badge-success"><span class="fas fa-star"></span> 10/10</span>
                  <p class="card-text">This is a positive review</p>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Sandra</h5>
                  <span class="badge badge-warning"><span class="fas fa-star"></span> 5/10</span>
                  <p class="card-text">This is a neutral review</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          Review This Track
        </div>
        <div class="card-body">
          <form>
            <div class="form-group">
              <label>Rating (/10)</label>
              <select class="custom-select">
                <option value=1>1/10</option>
                <option value=2>2/10</option>
                <option value=3>3/10</option>
                <option value=3>4/10</option>
                <option value=3 selected>5/10</option>
                <option value=3>6/10</option>
                <option value=3>7/10</option>
                <option value=3>8/10</option>
                <option value=3>9/10</option>
                <option value=3>10/10</option>
              </select>
            </div>
            <div class="form-group">
              <label for="reviewBody">Review</label>
              <textarea class="form-control" id="reviewBody" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-warning">Submit Review</button>
          </form>
        </div>
        <div class="card-footer text-muted">
          Reviews are public, do not share any personal information.
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          Review This Track
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Your Review</h5>
                  <span class="badge badge-danger"><span class="fas fa-star"></span> 1/10</span>
                  <p class="card-text">Show this card if the user has reviewed this track.</p>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-danger">Delete Review</button>
        </div>
        <div class="card-footer text-muted">
          Reviews are public, do not share any personal information.
        </div>
      </div>

    </div>
  </div>

</body>
</html>