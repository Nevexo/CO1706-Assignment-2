<?php
session_start();
if (!isset($_SESSION['User'])) {
  header('Location: /pages/login.php?error=NotLoggedIn');
  die();
}
require_once '../php/auth.php';
require_once '../php/offers.php';
if (isset($_SESSION['User'])) $user = unserialize($_SESSION['User']);

// Handle POST requests from the forms on this page
if (isset($_POST['offerSelection'])) {
  // TODO: handle pricing plan change
}

if (isset($_POST['newPassword'])) [
  // TODO: handle password change
]
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
  <title>Account Settings | EcksMusic</title>
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
          <a class="nav-link" href="/">Home</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="#">Tracks</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="#">Albums</a>
        </li>
        <li class="navbar-item">
          <a class="nav-link" href="#">Search</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <?php
        if (isset($_SESSION['User'])) {
          echo '<li class="nav-item dropdown">';
          echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown">';
          echo $user->Username . ' <span class="badge badge-secondary">' . $user->PricingPlan->Name . '</span></a>';
          echo '<div class="dropdown-menu dropdown-menu-right">';
          echo '<a class="dropdown-item" href="pages/account.php">Account Settings</a>';
          echo '<a class="dropdown-item" href="/php/logout.php">Logout</a>';
          echo '</div></li>';
        } else {
          echo '<li class="navbar-item"><div class="btn-group" role="group">
                  <a href="pages/login.php" class="btn btn-outline-warning">Login</a>';
          echo '<a href="pages/register.php" class="btn btn-warning">Register</a>
                  </div></li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>
<div class="jumbotron">
  <h1 class="display-4">Hello, <?php echo $user->Username; ?></h1>
  <p class="lead"><?php echo $user->PricingPlan->Name; ?></p>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Account Password</h5>
          <h6 class="card-subtitle mb-2 text-muted">Change your password.</h6>
          <form action="." method="post">
            <div class="form-group">
              <label for="currentPasswd">Current Password</label>
              <input type="password" required class="form-control" id="currentPasswd" placeholder="Current Password" name="currentPasswd">
            </div>
            <div class="form-group">
              <label for="newPassword">New Password</label>
              <input type="password" required class="form-control" id="newPassword" placeholder="New Password" name="newPassword">
            </div>
            <button type="submit" class="btn btn-warning">Change Password</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Pricing Plan</h5>
          <h6 class="card-subtitle mb-2 text-muted">Current Plan: <?php echo $user->PricingPlan->Name; ?></h6>
          <h6 class="card-subtitle mb-2 text-muted">Current Subscription Cost: £<?php echo $user->PricingPlan->Price; ?>/mo</h6>
          <span id="pricingPlanAlert"></span>
          <form action="#" method="post">
            <div class="form-group">
              <label for="offerSelection">New Pricing Plan</label>
              <select required class="custom-select my-1 mr-sm-2" id="offerSelection" name="offerSelection">
                <?php
                require_once "../php/offers.php";
                $offers = Offers::getAllOffers();
                foreach ($offers as $offer) {
                  echo '<option value="' . $offer->Id . '">' . $offer->Name . " (£" .$offer->Price . "/mo)</option>";
                }
                ?>
              </select>
            </div>
            <button type="submit" class="btn btn-warning">Change Plan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<script>
  const params = new URLSearchParams(window.location.search);

  if (params.has("newPricingPlan")) {
    document.getElementById("offerSelection").value = params.get("newPricingPlan");
    document.getElementById("pricingPlanAlert").innerHTML =
        '<div class="alert alert-primary">Press "Change Plan" to confirm plan change.</div>'
  }
</script>
</html>