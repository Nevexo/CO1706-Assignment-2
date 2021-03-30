<?php
session_start();
if (!isset($_SESSION['User'])) {
  header('Location: login.php?error=NotLoggedIn');
  die();
}

require_once '../php/auth.php';
require_once '../php/playlists.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);

function deletePlaylist($Id) {
  global $user;
  try {
    $playlist = Playlists::get($Id);
    if ($playlist->OwnerId != $user->Id) return;
    $playlist->Delete();
  } catch (Exception $e) {
    header('Location: ?status=' . $e->getMessage());
    die();
  }
}

function updatePlaylist($Id, $Name, $Public) {
  // Replace 'on' / 'off' from the radio button with a boolean value
  global $user;
  $Public = $Public == "on";
  try {
    $playlist = Playlists::get(+$Id);
    if ($playlist->OwnerId != $user->Id) return;
    $playlist->rename($Name);
    $playlist->setPublic($Public);
  } catch (Exception $e) {
    header('Location: ?status=' . $e->getMessage());
    die();
  }

}

function createPlaylist($Name, $Public, $Track) {
  global $user;
  $Public = $Public == "on";
  try {
    $playlist = Playlists::create($user, $Name);
    $playlist->setPublic($Public);
    if ($Track) $playlist->addTrack($Track);
    header('Location: ?status=createdPlaylist&newPlaylistName='. htmlspecialchars($Name));
    die();
  } catch (Exception $e) {
    header('Location: ?status=playlistCreationFailed');
    die();
  }
}

// Form handling code
if (isset($_POST['playlistName'])) {
  if ($_POST['playlistId'] != "") {
    // An ID has been passed, the user is trying to edit/delete this playlist.
    if (isset($_POST['deletePlaylist'])) {
      // Delete this playlist
      deletePlaylist($_POST['playlistId']);
    } else {
      updatePlaylist($_POST['playlistId'], $_POST['playlistName'], $_POST['playlistPublicCheck']);
    }
  } else {
    // No ID was passed, this is a new playlist.
    createPlaylist($_POST['playlistName'], $_POST['playlistPublicCheck'], $_POST['autoTrackId']);
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
        <li class="navbar-item">
          <a class="nav-link active" href="#">Playlists</a>
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
    <a class="btn btn-warning" onclick="createPlaylistModal(); return true;" role="button">Create a Playlist</a>
  </p>
</div>

<!--Modal for creating or editing playlists-->
<div id="playlistEditModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="playlist-modal-header">Create a Playlist</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="#" method="post" id="editCreatePlaylistForm">
        <div class="modal-body">
          <div class="form-group">
            <label for="playlistName">Playlist Name</label>
            <input required type="text" class="form-control" id="playlistName" name="playlistName" placeholder="Playlist Name">
          </div>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" name="playlistPublicCheck" id="playlistPublicCheck">
            <label class="form-check-label" for="playlistPublicCheck">Make this Playlist Public</label>
          </div>
          <!-- Hidden element used for tracking the playlist ID if editing -->
          <input type="hidden" name="playlistId" id="playlistId"/>
          <!-- Hidden element used for automatically add tracks to new playlists -->
          <input type="hidden" name="autoTrackId" id="autoTrackId"/>
        </div>
        <div class="modal-footer">
          <button type="submit" id="deleteBttn" name="deletePlaylist" tabindex='3' class="btn btn-danger mr-auto">Delete Playlist</button>
          <button type="submit" tabindex='1' class="btn btn-primary">Save Changes</button>
          <button type="button" tabindex='2' class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="alert alert-primary" id="statusAlert" role="alert">
    <!-- Text added by JavaScript at runtime -->
  </div>
  <div class="card card-fluid">
    <div class="card-header">
      Your Playlists
    </div>
    <div class="card-body">
      <div class="row">
        <?php
        // Write playlist entries to the dom, including public playlists.
        $privatePlaylists = Playlists::getForUser($user->Id);

        if (count($privatePlaylists) == 0) {
          echo '
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">No Playlists</h5>
                <p class="card-text">
                  You don\'t have any playlists, create one at the top of this page to keep a collection
                  of your favourite tracks. 
                </p>
              </div>
            </div>
          </div>
          ';
        } else
        {
          foreach ($privatePlaylists as $playlist) {
            $type = ($playlist->Public ? "public" : "private");
            // JavaScript function for calling the editPlaylistModal function
            $editPlaylistFunction = "
            editPlaylistModal(" . $playlist->Id . ", '" . $playlist->Name . "', " . $playlist->Public . "); 
            return true;
            ";
            echo '
            <div class="col-md-6">
              <div class="card border-warning mb-3">
                <div class="card-body text-secondary row">
                  <div class="col-md-10">
                    <h5 class="card-title">' . $playlist->Name . '</h5>
                    <p class="card-text"><span class="fas fa-compact-disc"></span> 
                    This playlist has ' . count($playlist->Tracks) . ' song(s).</br>
                    <span class="fas fa-users"></span> This is a ' . $type . ' playlist.
                    </p>
                  </div>
                </div>
                <div class="card-footer">
                  <a class="btn btn-warning" href="playlist_info.php?id=' . $playlist->Id . '">Open Playlist</a>
                  <button onclick="' . $editPlaylistFunction . '" class="btn btn-outline-warning">Edit Playlist</button>
                </div>
              </div>
            </div>  
            ';
          }
        }
        ?>
      </div>
    </div>
  </div>

  <div class="card card-fluid">
    <div class="card-header">
      Public Playlists
    </div>
    <div class="card-body">
      <div class="row">
        <?php
        // Write playlist entries to the dom, including public playlists.
        $publicPlaylists = Playlists::getPublic();

        if (count($publicPlaylists) == 0) {
          echo '
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">No Public Playlists</h5>
                <p class="card-text">
                  Share your playlists from the <b>edit playlist</b> menu to share it with all
                  EcksMusic listeners. 
                </p>
              </div>
            </div>
          </div>
          ';
        } else
        {
          foreach ($publicPlaylists as $playlist) {
            echo '
            <div class="col-md-6">
              <div class="card border-secondary mb-3">
                <div class="card-body text-secondary row">
                  <div class="col-md-10">
                    <h5 class="card-title">' . $playlist->Name . '</h5>
                    <p class="card-text"><span class="fas fa-compact-disc"></span> 
                    This playlist has ' . count($playlist->Tracks) . ' song(s).</br>
                    <span class="fas fa-user"></span> Created by: ' . $playlist->OwnerName . '
                    </p>
                  </div>
                </div>
                <div class="card-footer">
                  <a class="btn btn-warning" href="playlist_info.php?id=' . $playlist->Id . '">Open Playlist</a>
                </div>
              </div>
            </div>  
            ';
          }
        }
        ?>
      </div>
    </div>
  </div>
</div>


</body>
<script>
  const editPlaylistModal = (playlistId, playlistName, playlistPublic) => {
    // Add the playlist ID to a hidden element so PHP can handle this as an edit request.
    $('#playlistId').prop('value', playlistId);
    // Configure the visible form
    $('#playlist-modal-header').text("Edit Playlist");
    $('#playlistName').prop('value', playlistName);
    $('#playlistPublicCheck').prop('checked', playlistPublic);
    $('#deleteBttn').show();
    // Display the modal
    $('#playlistEditModal').modal('show');
  }

  const createPlaylistModal = () => {
    // Reset the modal in case 'editPlaylist' was called and dismissed.
    $('#playlist-modal-header').text("Create Playlist");
    $('#playlistId').prop('value', "");
    $('#playlistName').prop('value', "");
    $('#playlistPublicCheck').prop('checked', false);
    $('#deleteBttn').hide();
    // Display the modal
    $('#playlistEditModal').modal('show');
  }

  // Update the alert label
  const params = new URLSearchParams(window.location.search);
  const statusAlert = document.getElementById("statusAlert");
  statusAlert.hidden = true;

  if (params.has("status")) {
    statusAlert.hidden = false;
    switch(params.get("status")) {
      case "createdPlaylist":
        statusAlert.innerText = `Successfully created playlist: ${params.get("newPlaylistName")}`;
        break;
      case "playlistCreationFailed":
        statusAlert.classList.remove("alert-primary");
        statusAlert.classList.add("alert-danger");
        statusAlert.innerText = "Something went wrong creating your playlist, please try again later.";
        break;
      default:
        statusAlert.classList.remove("alert-primary");
        statusAlert.classList.add("alert-warning");
        statusAlert.innerText = `Unknown error occurred: ${params.get("status")} please contact support.`;
        break;
    }
  }

  // Handlers for creating playlists when called from another page (i.e. tracks add to playlist button)
  if (params.has("action")) {
    if (params.get("action") === "createNew") {
      // Immediately open the create playlist modal, setting the trackid in a hidden element.
      if (params.has("addTrack")) $('#autoTrackId').prop('value', params.get("addTrack"));
      createPlaylistModal();
    }
  }

</script>
</html>