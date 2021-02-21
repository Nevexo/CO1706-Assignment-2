<?php
session_start();
require_once "../php/auth.php";

if (isset($_SESSION['User'])) {
  // The user is already logged in, send them to the home page.
  header('Location: /');
  die();
}

if (isset($_POST['username'])) {
  // The form has been submitted, attempt to login.
  try {
    $user = Users::login($_POST['username'], $_POST['password']);

    // We've logged in, add the user object to _SESSION and redirect the user.
    $_SESSION['User'] = serialize($user);
    header('Location: /');
    die();
  } catch (Exception $e) {
    // Something went wrong, reload the page with an error displayed.
    header('Location: ' . $_SERVER['REQUEST_URI'] . "?error=" . $e->getMessage());
    die();
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
  <title>Login | EcksMusic</title>
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
        </ul>
      </div>
    </div>
  </nav>

  <div class="col-md-6 row-md-6 ml-auto mr-auto mt-5">
    <div class="jumbotron">
      <h1>Login to EcksMusic</h1>
      <p class="lead">Welcome to EcksMusic! Please login below.</p>
      <p>Don't have an account? <a href="/pages/register.php">Create one now!</a></p>
      <hr class="my-4">
      <span id="alertBox"></span>
      <form method="post" action="#">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" required name="username" class="form-control" id="username" placeholder="Username">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" required name="password" class="form-control" id="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-warning">Login</button>
      </form>
    </div>
  </div>


</body>
<footer>
  <script>
    // Check if an error message query was added to the URL by PHP, if so, display a human-readable message.
    const params = new URLSearchParams(window.location.search);
    if (params.has('error')) {
      const error = params.get('error');
      let message;

      switch (error) {
        case "InvalidUsernamePassword":
          message = "Invalid username or password."
          break;
        default:
          message = "An unknown error occurred while logging in, please try again."
      }

      document.getElementById("alertBox").innerHTML = `<div class="alert alert-danger">${message}</div>`;
    }
  </script>
</footer>
</html>