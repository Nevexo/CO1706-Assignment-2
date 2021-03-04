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
                <span title="Album" class="fas fa-compact-disc"></span> Album: <i><?php echo $Track->Album->Name; ?><br/></i>
                <span title="Album" class="fas fa-users"></span> Average Rating: <i><?php echo $Track->AverageRating?></i>
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
      $UserReview = Reviews::getForUser($Track->Id, $user->Id);

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
  const writeWarningLabel = (text) => {
    const span = document.getElementById("reviewFormLabel");
    span.innerHTML = `<div class="alert alert-danger" role="alert">${text}</div>`;
  }

  const validateReviewForm = () => {
    const form = document.forms['trackReview'];

    // Check review body length
    if (form['reviewBody'].value.length > 250) {
      writeWarningLabel("Review text too long, limited to 250 characters.");
      return false;
    }

    if (form['reviewBody'].value.length < 10) {
      writeWarningLabel("Review too short, please write at least 10 characters.");
      return false;
    }

    return true;
  }

  // Check for query parameters
  const params = new URLSearchParams(window.location.search);
  if (params.has("reviewError")) {
    switch (params.get("reviewError")) {
      case "QueryFailed":
        writeWarningLabel("Something went wrong on our side, please try again later.")
        break;
      case "InvalidRatingValue":
        writeWarningLabel("Invalid rating specified.");
        break;
      case "ReviewTextTooLong":
        writeWarningLabel("Review is too long, limited to 250 characters.");
        break;
      case "ExistingReview":
        writeWarningLabel("Your account already has a review for this track, did you make it on another device?");
        break;
      default:
        writeWarningLabel("Something went wrong creating your review. Please try again later.");
        break;
    }
  }
</script>
</html>