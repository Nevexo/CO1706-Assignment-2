<?php
session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';
require_once '../php/playlists.php';
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
  <title>Playlists | EcksMusic</title>
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
  <h1 class="display-4">Playlists</h1>
  <p class="lead">EcksMusic playlists let you save and share your favourite songs.</p>
  <hr class="my-4">
  <p>
    You can add tracks from the <a href="tracks.php">tracks list</a> page, or add individual tracks from their
    information pages.
  </p>
  <p class="lead">
    <a class="btn btn-warning" href="#" role="button">Create a Playlist</a>
  </p>
</div>

<div class="container-fluid">

  <?php
  function echoPlaylist(Playlist $playlist, bool $public)
  {
    // Set card and footer information if this playlist is public or private.
    $type = "Private";
    $boxColour = "warning";
    if ($public)
    {
      $type = "Public";
      $boxColour = "secondary";
    }

    echo '
      <div class="col-md-6">
      <div class="card border-' . $boxColour . ' mb-3">
        <div class="card-body text-secondary row">
          <div class="col-md-10">
            <h5 class="card-title">' . $playlist->Name . ' </h5>
            <p class="card-text">This playlist has ' . count($playlist->Tracks) . ' song(s).</p>
          </div>
          <div class="col-md-1 text-lg-right">
            <button class="btn btn-warning">Open Playlist</button>
          </div>
        </div>
        <div class="card-footer text-muted">
          ' . $type . ' Playlist - ' . $playlist->OwnerName . '
        </div>
      </div>
      </div>
      ';
  }
  ?>

  <div class="row">
    <?php
      // Write playlist entries to the dom, including public playlists.
      $privatePlaylists = Playlists::getForUser($user->Id);
      $publicPlaylists = Playlists::getPublic();

      foreach ($privatePlaylists as $playlist)
      {
        echoPlaylist($playlist, false);
      }

      foreach($publicPlaylists as $playlist)
      {
        if ($playlist->OwnerId != $user->Id) echoPlaylist($playlist, true);
      }
    ?>
  </div>
</div>

</body>
</html>