<?php
session_start();
require_once '../php/auth.php';

print_r("<p>SERIALISED SESSION: " . $_SESSION['User'] . " </p>");
$user = unserialize($_SESSION['User']);
echo "<hr><h3>UNSERIALISED SESSION</h3>";

echo "<p>USER ID: " . $user->Id . "</p>";

echo "<p>USERNAME: " . $user->Username . "</p>";

echo "<p>PRICING PLAN: " . $user->PricingPlan->Name . "</p>";

if ($user->confirmPassword("password"))
{
  echo "<p>PASSWORD IS SET TO 'password'</p>";
} else {
  echo "<p>PASSWORD IS NOT SET TO 'password'</p>";
}
