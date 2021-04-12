<?php

// Track Search Frontend Page
// Cameron Paul Fleming - 2021

session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

// Supported search filters
$SearchFilters = ["track", "album", "artist"];

require_once '../php/auth.php';
require_once '../php/music.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);

function RunSearch(string $Query, string $Filter): array
{
  // Query the database for a/multiple track(s)
  global $SearchFilters;

  // Check for valid filter, if not using all.
  if ($Filter != "all" && !in_array($Filter, $SearchFilters)) throw new Exception("InvalidSearchFilter");

  $results = [];

  if ($Filter == "all")
  {
    // If 'all' is selected, then run a search for all providers.
    try {
      // Merge all results into one
      $results = array_merge(
        Tracks::search($Query),
        Albums::search($Query),
        Artists::search($Query)
      );
    } catch(Exception $e) {throw $e;}
  } else
  {
    // Run a specific search filter
    try {
      switch ($Filter)
      {
        case "track":
          $results = Tracks::search($Query);
          break;
        case "album":
          $results = Albums::search($Query);
          break;
        case "artist":
          $results = Artists::search($Query);
          break;
      }
    } catch (Exception $e) {throw $e;}
  }

  return $results;
}

if (isset($_GET['search']))
{
  // A search query is present, run the search system.

  // Resolve the filter, if it's not defined, use all.
  $filter = (isset($_GET['filter']) ? $_GET['filter'] : "all");

  try {
    $results = RunSearch($_GET['search'], $filter);
  } catch (Exception $e) {
    // TODO: excep handle
    print_r($e);
  }

  // Split results into three arrays.
  $Tracks = $Albums = $Artists = [];
  foreach ($results as $result)
  {
    if (is_a($result, "track")) array_push($Tracks, $result);
    if (is_a($result, "album")) array_push($Albums, $result);
    if (is_a($result, "artist")) array_push($Artists, $result);
  }
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
  <title>Search | EcksMusic</title>
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
          <a class="nav-link active" href="#">Search</a>
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
  <h1 class="display-4">Search</h1>
</div>

<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      Search
    </div>
    <div class="card-body">
      <form action="#" method="get" id="searchForm">
        <div class="form-group row">
          <div class="col-md-8">
            <input
                    type="text"
                    class="form-control"
                    id="textSearch"
                    placeholder="Search..."
                    aria-label="Search"
                    autofocus
                    autocapitalize="off"
                    spellcheck="false"
                    maxlength="2048"
                    name="search"
            >
          </div>
          <div class="col-md-3">
            <select class="form-control" aria-label="Filter" name="filter">
              <option value="all" selected>Everything</option>
              <option value="track">Tracks</option>
              <option value="album">Albums</option>
              <option value="artist">Artists</option>
            </select>

          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-warning">Search</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php
    if ($Tracks != [])
    {
      // Echo Tracks card into dom
      echo '
      <div class="card">
        <div class="card-header">
          Tracks
        </div>
        <div class="card-body">
      ';
      // PrettyPrint all tracks
      foreach ($Tracks as $Track)
      {
        echo '
          <div class="col-md-3">
            ' . $Track->prettyPrint(true) . '
          </div>
        ';
      }
      // Close divs for tracks card.
      echo '</div></div>';
    }

    if ($Albums != [])
    {
      // Echo albums into dom
      echo '
      <div class="card">
        <div class="card-header">
          Albums
        </div>
        <div class="card-body">
      ';

      foreach ($Albums as $Album)
      {
        echo '
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">' . $Album->Name . '</h5>
              <p class="card-text">' . $Album->Artist->Name . '</p>
              <a href="album.php?id=' . $Album->Id . '" class="btn btn-warning">More Info</a>
            </div>
          </div>
        </div>
      ';
      }

      // Close card divs
      echo '</div></div>';
    }

    if ($Artists != [])
    {
      // Echo artists onto the dom
      echo '
      <div class="card">
        <div class="card-header">
          Artists
        </div>
        <div class="card-body">
      ';

      foreach ($Artists as $Artist)
      {
        echo '
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">' . $Artist->Name . '</h5>
              <a href="artist.php?id=' . $Artist->Id . '" class="btn btn-warning">More Info</a>
            </div>
          </div>
        </div>
      ';
      }

      // Close card divs
      echo '</div></div>';
    }
  ?>
</div>

</body>
<script>
  // Script to restore options to form
  const queryParams = new URLSearchParams(window.location.search);
  const form = document.forms['searchForm']

  if (queryParams.has("search")) form['search'].value = queryParams.get("search");
  if (queryParams.has("filter"))
  {
    const filter = queryParams.get("filter");

    if (["all", "artist", "album", "track"].includes(filter))
      form['filter'].value = queryParams.get("filter");
    else
      form['filter'].value = "all";
  }
</script>
</html>