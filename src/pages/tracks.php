<?php

// Track List Page
// Cameron Paul Fleming - 2021

session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';
require_once '../php/music.php';
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
        <li class="navbar-item active">
          <a class="nav-link" href="#">Tracks</a>
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
  <h1 class="display-4">Tracks</h1>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          Filter
        </div>
        <div class="card-body">
          <form action="#" method="get" id="filters">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="genre">Genre</label>
                <select name="genre" id="genre" class="form-control">
                  <option selected>Any</option>
                  <?php
                    foreach (Tracks::getGenreList() as $Genre)
                    {
                      echo "<option>" . $Genre['genre'] ."</option>";
                    }
                  ?>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-warning">Update Filter</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <?php
      // Check for filters
      if (isset($_GET['genre']) && $_GET['genre'] != "Any")
      {
        // The genre filter is active, search by genre.
        try {
          $Tracks = Tracks::getByGenre($_GET['genre']);
        } catch (Exception $e) {
          // No tracks found, likely a modified query parameter.
          echo '<div class="alert alert-danger" role="alert">
                  No tracks found for this filter.
                </div>';
          die();
        }
      } else
      {
        // No filtering, get all tracks.
        $Tracks = tracks::getAll();
      }

      // Create a new paginator to split the track listing into pages.
      $paginator = new TrackPaginator($Tracks);
      if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;

    try {
      // Get the pagination page.
      $tracks = $paginator->getPage($page);
    } catch (Exception $e) {
      // Invalid page, likely a modified query parameter.
      echo '<div class="alert alert-danger" role="alert">
              Failed to get tracks, please try again later.
            </div>';
      die();
    }

    // Enum the tracks and display them using the Track prettyPrint helper.
    // Pass 'true' as hyperlinks to enable links on album/artist names.
    foreach($tracks as $track) {
        echo '
          <div class="col-md-3">
            ' . $track->prettyPrint(true, $user->Id) . '
          </div>
        ';
      }
    ?>
  </div>

  <div class="row d-flex justify-content-center">
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <?php
          // Print the pagination box to the screen, mark the current page as 'active'
          for ($i = 1; $i <= $paginator->pageCount; $i++) {
            if ($i == $page) {
              // Show as active
              echo "<li class='page-item active'><a class='page-link' onclick='changePage(" . $i . ")'>" . $i . "</a></li>\r\n";
            } else {
              echo "<li class='page-item'><a class='page-link' onclick='changePage(" . $i . ")'>" . $i . "</a></li>\r\n";
            }
          }
        ?>
      </ul>
    </nav>
  </div>
</div>
</body>
<script>
  const queryParams = new URLSearchParams(window.location.search);

  const changePage = (newPage) => {
    // Handle a pagination page change by only adjusting one of the parameters (to preserve filters)
    queryParams.set("page", newPage);

    window.location.search = queryParams.toString()
  }

  // Update the genre filter if parameter is present
  const form = document.forms['filters']
  if (queryParams.has("genre")) form['genre'].value = queryParams.get("genre");
</script>
</html>