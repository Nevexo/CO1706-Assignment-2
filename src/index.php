<?php
session_start();
require_once 'php/auth.php';
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
  <title>Home | EcksMusic</title>
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
          <li class="navbar-item active">
            <a class="nav-link" href="#">Home</a>
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
        <!--Search Bar-->
<!--        <form class="form-inline my-2 my-lg-0">-->
<!--          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">-->
<!--          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
<!--        </form>-->
        <ul class="navbar-nav ml-auto">
        <?php
          if (isset($_SESSION['User'])) {
            echo '<li class="nav-item dropdown">';
            echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown">';
            echo $user->Username . '</a>';
            echo '<div class="dropdown-menu dropdown-menu-right">';
            echo '<a class="dropdown-item disabled" href="#">Settings</a>';
            echo '<a class="dropdown-item" href="/php/logout.php">Logout</a>';
            echo '</div></li>';
          } else {
            echo '<li class="navbar-item"><a href="pages/login.php">
                  <button type="button" class="btn btn-outline-warning">Login</button></a></li>';
            echo '<li class="navbar-item"><a href="pages/register.php">
                  <button type="button" class="btn btn-warning">Register</button></a></li>';
          }
        ?>
        </ul>
      </div>
    </div>
  </nav>
<?php
  require "./php/database.php";

  $result = $pdo->query("SELECT * FROM tracks;")->fetchAll(PDO::FETCH_ASSOC);

  foreach ($result as $entry) {
    //echo $entry['name'] . "<br>";
  }
?>
</body>
</html>