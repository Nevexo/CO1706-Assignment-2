<?php

// Playlist Track List Page
// Cameron Paul Fleming - 2021=

session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';
require_once '../php/playlists.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);

function getPlaylist($Id) {
  // Get the playlist
  try {
    return Playlists::get($_GET['id']);
  } catch (Exception $e) {
    // If the playlist can't be found, send the user to the playlists list.
    header('Location: playlist.php');
    die();
  }
}

if (!isset($_GET['id'])) {
  // No playlist was passed, redirect the user.
  header('Location: playlist.php');
  die();
}

$playlist = getPlaylist($_GET['id']);

// Form handlers
if (isset($_POST['randomTracks'])) {
  // Add random tracks to this playlist
  if ($playlist->OwnerId != $user->Id) return;
  $playlist->randomPopulate();
  // Refresh the playlist
  $playlist = getPlaylist($_GET['id']);
}

if (isset($_POST['deleteTrackId'])) {
  // Remove the selected track from this playlist.
  if ($playlist->OwnerId != $user->Id) return;
  $playlist->removeTrack($_POST['deleteTrackId']);
  // Refresh the playlist
  $playlist = getPlaylist($_GET['id']);
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
  <title><?php echo $playlist->Name ?> | EcksMusic</title>
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
  <h1 class="display-4"><?php echo $playlist->Name; ?></h1>
  <p class="lead">A
    <?php
    echo ($playlist->Public ? 'public' : 'private') . ' playlist by ' .
      ($playlist->OwnerId == $user->Id ? 'you' : $playlist->OwnerName); ?>
    on EcksMusic.</p>
  <hr class="my-4">
  <p>
    You can add tracks from the <a href="tracks.php">tracks list</a> page.
  </p>
</div>

<div class="container-fluid">
  <div class="card card-fluid">
    <div class="card-header">
      Playlist Tracks
    </div>
    <div class="card-body">
      <div class="row">
        <?php
          if (count($playlist->Tracks) == 0) {
            if ($playlist->OwnerId == $user->Id) {
              echo '
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">No Tracks</h5>
                    <p class="card-text">
                      This playlist doesn\'t have any tracks, 
                      you can add them from the <a href="tracks.php">tracks page</a>. 
                    </p>
                    <form action="#" method="post">
                      <label for="randomTracks" class="text-muted">
                      We\'ll add tracks recommended for you if you have reviewed tracks on EcksMusic</label><br>
                      <button name="randomTracks" type="submit" class="btn btn-warning">Add 10 Random Tracks</button>                     
                    </form>
                  </div>
                </div>
              </div>
              ';
            } else {
                echo '
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">No Tracks</h5>
                      <p class="card-text">
                        The owner of this playlist hasn\'t added any tracks yet.
                      </p>
                    </div>
                  </div>
                </div>
                ';
            }
          } else {
            foreach ($playlist->Tracks as $Track) {
              if ($playlist->OwnerId == $user->Id) {
                echo '
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-body">
                      ' . $Track->prettyPrint(true, $user->Id) . '
                      <form action="#" method="post">
                        <input type="hidden" name="deleteTrackId" id="deleteTrackId" value="' . $Track->Id . '"/>
                        <button type="submit" class="btn btn-outline-danger">Remove Track</button>
                      </form>
                      
                    </div>
                  </div>
                </div>
              ';
              }
              else
              {
                echo '
                <div class="col-md-3">
                  ' . $Track->prettyPrint(true, $user->Id) . '  
                </div>
                ';
              }
            }
          }
        ?>
      </div>
    </div>
  </div>
</div>

</body>
</html>