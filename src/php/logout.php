<?php
// Terminate a session and redirect the user to the homepage
// Cameron Paul Fleming - 2021

session_start();
$_SESSION['User'] = "";
session_destroy();
header('Location: /');
