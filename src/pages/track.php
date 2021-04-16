<?php

// Track Information Frontend Page
// Cameron Paul Fleming - 2021

session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';
require_once '../php/music.php';
require_once "../php/reviews.php";
require_once '../php/playlists.php';
require_once '../php/recommend.php';

if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);
// Redirect user if there's no track ID selected
if (!isset($_GET['id'])) {
  header('Location: tracks.php');
  die();
}

// Get the track and place it in the $Track variable
try {
  $Track = Tracks::get($_GET['id']);
  // Check if the track is recommended for this user
  $Recommended = Recommendations::isRecommended($user->Id, $Track->Id);
} catch (Exception $e) {
  // Cannot find this track, take the user back to the track listl
  header('Location: tracks.php');
  die();
}

// Check for review POST data
if (isset($_POST['reviewBody'])) {
  try {
    // Create a new review
    Reviews::create($Track, $user, $_POST['rating'], htmlspecialchars($_POST['reviewBody']));
    // Update recommendations for this user
    Recommendations::update($user);
  } catch (Exception $e) {
    header('Location: ?id=' . $Track->Id . '&reviewError=' . $e->getMessage());
    die();
  }
}

// Check for delete review button being pressed
if (isset($_POST['deleteReview'])) {
  try {
    // Get the track for this user and attempt to delete it
    $r = Reviews::getForUserByTrack($Track->Id, $user->Id);
    $r->Delete();
    // Update recommendations for this user
    Recommendations::update($user);
  } catch (Exception $e) {
    header('Location: ?id=' . $Track->Id . '&reviewError=' . $e->getMessage());
    die();
  }
}

// Add track to Playlist handler
if (isset($_POST['playlistSelection']))
{
  $PlaylistId = $_POST['playlistSelection'];
  $TrackId = $_POST['trackId'];
  try {
    $Playlist = Playlists::get($PlaylistId);
  } catch (Exception $e) {
    header('Location: ?id=' . $Track->Id . '&playlistStatus=error');
    die();
  }

  try {
    $Playlist->addTrack($TrackId);
    header('Location: ?id=' . $Track->Id . '&playlistStatus=added&playlistName=' . $Playlist->Name);
    die();
  } catch (Exception $e) {
    switch($e->getMessage())
    {
      case "DuplicateTrack":
        header('Location: ?id=' . $Track->Id . '&playlistStatus=duplicate');
        die();
      default:
        header('Location: ?id=' . $Track->Id . '&playlistStatus=error');
        die();
    }
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

<!-- Add to Playlist modal -->
<div id="addToPlaylistModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="playlist-modal-header">Add to Playlist</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="#" method="post" id="addToPlaylistForm" onsubmit="return catchPlaylistForm();">
        <div class="modal-body">
          <div class="form-group">
            <label for="playlistSelection">Playlist</label>
            <select id="playlistSelection" name="playlistSelection" class="form-control">
              <?php
              $playlists = Playlists::getForUser($user->Id);
              foreach($playlists as $playlist) {
                echo '
                    <option value="' . $playlist->Id . '">' . $playlist->Name . '</option>
                  ';
              }
              ?>
              <option value="CreateNewPlaylist">New Playlist...</option>
            </select>
          </div>
          <!-- Hidden element used for storing the trackId the user is adding to a playlist -->
          <input type="hidden" name="trackId" id="trackId"/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Track</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="jumbotron">
  <div class="row">
    <div class="col-auto">
      <img alt="Album thumbnail" src="../<?php echo $Track->ThumbPath; ?>"/>
    </div>
    <div class="col">
      <h1 class="display-4">
        <?php echo $Track->Name; ?>
      </h1>
      <a href="artist.php?id=<?php echo $Track->Artist->Id; ?>"><p class="lead">
        <span class="text-muted fas fa-users"></span>
        <?php echo $Track->Artist->Name; ?>
      </p></a>
    </div>
  </div>
</div>

<div class="container-fluid">
  <span id="playlistLabel"></span>
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
      <!-- Album Information -->
      <div class="card">
        <div class="card-header">
          Album Information
        </div>
        <div class="card-body">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo $Track->Album->Name; ?></h5>
              <p class="card-text"><?php echo $Track->Artist->Name; ?></p>
              <a href="album.php?id=<?php echo $Track->Album->Id; ?>" class="btn btn-warning">More Info</a>
            </div>
          </div>
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
              <?php
              // Display recommended for you tag if the user has a recommendation for this track.
                if ($Recommended) {
                  echo '<span class="badge badge-warning"><span class="fas fa-star"></span> Recommended for You</span>';
                }
              ?>
              <p class="card-text">
                <span title="Album" class="fas fa-music"></span>
                  Genre: <a href="tracks.php?genre=<?php echo $Track->Genre; ?>"><i><?php echo $Track->Genre; ?>
                  </i></a><br/>
                <span title="Album" class="fas fa-users"></span>
                  Average Rating: <i><?php echo $Track->AverageRating?></i>
              </p>
              <button
                      onclick="addToPlaylist(<?php echo $Track->Id ?>); return true;"
                      class="btn btn-warning">
                Add to Playlist
              </button><br/>
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
            <?php
              try {
                // Attempt to fetch all reviews for this track.
                $reviews = Reviews::getForTrack($Track->Id);

                // Echo all reviews to the dom
                foreach($reviews as $Review) {
                  // Get the label colour
                  $Tag = "badge-success";
                  if ($Review->Rating <= 3) {
                    $Tag = "badge-danger";
                  } elseif ($Review->Rating > 3 and $Review->Rating < 7) {
                    $Tag = "badge-warning";
                  }
                  $html = '
                <div class="col-sm-6">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">' . $Review->OwnerName . '</h5>
                      <span class="badge ' . $Tag . '"><span class="fas fa-star"></span> ' . $Review->Rating . ' /10</span>
                      <p class="card-text">' . $Review->Review . '</p>
                    </div>
                  </div>
                </div>';
                  echo $html;
                }

              } catch (Exception $e) {
                if ($e->getMessage() == "NoReviews") {
                  // No reviews have been posted, show a warning.
                  echo '
                  <div class="col-sm-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">No Reviews</h5>
                      <p class="card-text">We use reviews to recommend our users similar tracks they may like.
                      Review this track below!</p>
                    </div>
                  </div>
                </div>';
                } else {
                  // Something else went wrong fetching reviews, display an error.
                  echo '
                  <div class="col-sm-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Failed to Fetch Reviews</h5>
                      <p class="card-text">Try again later.</p>
                    </div>
                  </div>
                </div>';
                }
              }
            ?>
          </div>
        </div>
      </div>

      <?php
        $UserReview = Reviews::getForUserByTrack($Track->Id, $user->Id);

        if ($UserReview == null) {
          // The user hasn't reviewed this track, so display the submit review form.
          echo '
            <div class="card">
            <div class="card-header">
              Review This Track
            </div>
            <div class="card-body">
              <form id="trackReview" action="#" method="post" onsubmit="return validateReviewForm();">
                <span id="reviewFormLabel"></span>
                <div class="form-group">
                  <label>Rating (/10)</label>
                  <select class="custom-select" name="rating" id="rating" required>
                    <option value=1>1/10</option>
                    <option value=2>2/10</option>
                    <option value=3>3/10</option>
                    <option value=4>4/10</option>
                    <option value=5 selected>5/10</option>
                    <option value=6>6/10</option>
                    <option value=7>7/10</option>
                    <option value=8>8/10</option>
                    <option value=9>9/10</option>
                    <option value=10>10/10</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="reviewBody">Review</label>
                  <textarea class="form-control"
                   required minlength="10" maxlength="250" name="reviewBody" id="reviewBody" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-warning">Submit Review</button>
              </form>
            </div>
            <div class="card-footer text-muted">
              Reviews are public, do not share any personal information.
            </div>
          </div>
          ';
        } else {
          // The user has reviewed this track, show their review in a card with a delete button
          echo '
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
                      <span class="badge badge-secondary"><span class="fas fa-star"></span> ' . $UserReview->Rating . '/10</span>
                      <p class="card-text">' . $UserReview->Review . '</p>
                    </div>
                  </div>
                </div>
              </div>
              <p class="text-muted font-italic">We\'ll use your reviews to recommend you other songs.</p>
              <form method="post" action="#">
                <button type="submit" name="deleteReview" class="btn btn-danger">Delete Review</button>
              </form>
            </div>
            <div class="card-footer text-muted">
              Reviews are public, do not share any personal information.
            </div>
          </div>
          ';
        }
      ?>
    </div>
  </div>
</body>
<script>
  // Validation scripts for review form
  const writeInfoLabel = (type, level, text) => {
    const span = document.getElementById(type);
    span.innerHTML = `<div class="alert alert-` + level + `" role="alert">${text}</div>`;
  }

  const validateReviewForm = () => {
    const form = document.forms['trackReview'];

    // Check review body length
    if (form['reviewBody'].value.length > 250) {
      writeInfoLabel("Review text too long, limited to 250 characters.");
      return false;
    }

    if (form['reviewBody'].value.length < 10) {
      writeInfoLabel("Review too short, please write at least 10 characters.");
      return false;
    }

    return true;
  }

  // Check for query parameters
  const params = new URLSearchParams(window.location.search);
  if (params.has("reviewError")) {
    switch (params.get("reviewError")) {
      case "QueryFailed":
        writeInfoLabel("reviewFormLabel", "danger", "Something went wrong on our side, please try again later.")
        break;
      case "InvalidRatingValue":
        writeInfoLabel("reviewFormLabel", "danger", "Invalid rating specified.");
        break;
      case "ReviewTextTooLong":
        writeInfoLabel("reviewFormLabel", "danger", "Review is too long, limited to 250 characters.");
        break;
      case "ExistingReview":
        writeInfoLabel("reviewFormLabel", "danger", "Your account already has a review for this track, did you make it on another device?");
        break;
      default:
        writeInfoLabel("reviewFormLabel", "danger", "Something went wrong creating your review. Please try again later.");
        break;
    }
  }

  if (params.has("playlistStatus"))
  {
    switch(params.get("playlistStatus")) {
      case "duplicate":
        writeInfoLabel("playlistLabel", "danger", "This track has already been added to that playlist.");
        break
      case "error":
        writeInfoLabel("playlistLabel", "danger", "Something went wrong adding this track to the playlist, please try again later.");
        break;
      default:
        writeInfoLabel("playlistLabel", "success", `Added this track to the ${params.get('playlistName')} playlist.`);
        break;
    }
  }

  const addToPlaylist = (trackId) => {
    $('#trackId').prop('value', trackId);
    $('#addToPlaylistModal').modal('show');
  }

  const catchPlaylistForm = () => {
    // Handle responses from the Add to Playlist modal form, if the "New Playlist..." option is selected
    // forward the user to the playlists creation page.
    const form = document.forms["addToPlaylistForm"];

    if (form['playlistSelection'].value === "CreateNewPlaylist")
    {
      window.location = "playlist.php?action=createNew&addTrack=" + params.get("id");
      return false;
    }
    return true;
  }
</script>
</html>