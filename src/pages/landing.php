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
  <title>Welcome | EcksMusic</title>
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
        echo '<a class="dropdown-item" href="#">Recommended Tracks</a>';
        echo '<a class="dropdown-item" href="account.php">Account Settings</a>';
        echo '<a class="dropdown-item" href="../php/logout.php">Logout</a>';
        echo '</div></li>';
        ?>
      </ul>
    </div>
  </div>
</nav>
<div class="jumbotron">
  <h1 class="display-4">Welcome to EcksMusic, <?php echo $user->Username ?>.</h1>
  <p class="lead">We're glad you're here. This page explains a few features of EcksMusic.</p>
</div>

<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      Tracks
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <!-- Choose a random track to demonstrate with. -->
          <?php echo Tracks::random(1)[0]->prettyPrint(true) ?>
        </div>
        <div class="col-md-9">
          <p>EcksMusic tracks will always show up with this card. It displays the album art at the top,
            track name & genre.</p>
          <p>
            If the recommendations system picks this as a track recommended for you, we'll display this tag:<br>
            <span class="badge badge-warning"><span class="fas fa-star"></span> Recommended for You</span></p>
          <p>In most places, the artist & album names will be hyperlinked, you can select these to see more from that
            artist, or see the other tracks in this album.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      Search
    </div>
    <div class="card-body">
      <div class="row">
        <img src="../images/screenshots/search.png" class="img-fluid" alt="Search Bar Demonstration">
      </div>

      <p>The EcksMusic search feature allows you to search our whole platform. Use the search filter
      to the right of the search box to limit your search to specific kinds of content, such as tracks, artists
        and albums.</p>
    </div>
  </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        Reviews & Recommendations
        <a href="recommendations.php" class="btn btn-sm btn-warning">See your Recommendations</a>
      </div>
      <div class="card-body">
        <p>All tracks on EcksMusic can be reviewed, reviews are public, so make sure you don't include
        personal information. Once a review has been created, EcksMusic will take it into account for your
        recommended tracks.</p>
        <div class="row">
          <div class="col-md-9">
            <p>Once you're viewed a few tracks, select your username and visit the Recommended Tracks page.</p>
          </div>
          <div class="col-md-3">
            <img src="../images/screenshots/recom.png" class="img-fluid ml-auto" alt="Recommendations link">
          </div>
        </div>

      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        Playlists
        <a href="playlist.php?action=createNew" class="btn btn-sm btn-warning">Create a Playlist</a>
      </div>
        <div class="card-body">
          <p>EcksMusic playlists allow you to save your favourite songs to listen later. You can also make your playlists
          publicly accessible. Users can have an unlimited number of playlists each with an unlimited number of tracks.</p>
        <p><a href="playlist.php">Visit the Playlists Page</a></p>
        <div class="row">
          <div class="col-md-4">
            <img src="../images/screenshots/create_playlist.png" class="img-fluid" alt="Create Playlist Form">
          </div>
          <div class="col-md-8">
            <p>New playlists can be created from the playlists page, and can be marked as public.
            Public playlists cannot be edited by other users, but all users will be able to see your tracks.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <p>Once a playlist has been created, you can open it and use EcksMusic's recommendation system to automatically
              and 10 tracks, or you can visit a track's information page and add it manually.</p>
          </div>
          <div class="col-md-4">
            <img src="../images/screenshots/add_to_playlist.png" class="img-fluid ml-auto" alt="Add to playlist form">
          </div>
        </div>
          <p>After creating a playlist, you can select 'edit playlist' to rename it, change the visibility or delete
          the playlist.</p>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        Account Management
        <a href="account.php" class="btn btn-sm btn-warning">Manage my Account</a>
      </div>
      <div class="card-body">
        <p>You can manage your account at any time from the <a href="account.php">account page</a>, visit it by
        selecting your username in the top right and clicking 'Account Settings'</p>
        <p>From this page, you can change your password, change your subscription and delete your account.</p>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        Welcome to EcksMusic
      </div>
      <div class="card-body">
        <p>Thanks for joining EcksMusic! That should be enough to get you started. Feel free to contact support
        at any time with any questions.</p>
        <a href="../" class="btn btn-warning btn-xl">Start using EcksMusic</a>
      </div>
    </div>
</body>
</html>