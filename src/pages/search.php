<?php

// Track Search Frontend Page
// Cameron Paul Fleming - 2021

session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';

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
              <option value="track">Track</option>
              <option value="album">Album</option>
              <option value="artist">Artist</option>
            </select>

          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-warning">Search</button>
          </div>
        </div>
      </form>
    </div>
  </div>
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