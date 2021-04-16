<?php

// Smoketest PHP file - User Creation, editing & deletion
// Cameron Paul Fleming 2021

require_once '../php/auth.php';

function create(): User
{
  echo "create user<br>";
  try {
    Users::create("Smoketest", "Password!", 2);
  } catch (Exception $e) {
    throw $e;
  }

  echo "login<br>";
  try {
    $U = Users::login("Smoketest", "Password!");
  } catch (Exception $e) {
    throw $e;
  }

  echo "check username<br>";
  if (Users::getUsername($U->Id) == "Smoketest")
  {
    echo "check username ok<br>";
  }

  echo "change passwd";
  try {
    $U->changePassword("Password!", "NewPassword");

    // Check old password
    try {
        $U = Users::login("Smoketest", "Password!");
        throw new Exception("Check fail, logged in successfully with old password.");
      } catch (Exception $e) {
      echo "ok<br>";
    }

    // Login as the new user.
    $U = Users::login("Smoketest", "NewPassword");

  } catch (Exception $e) {
    throw $e;
  }

  echo "change pricing plan<br>";
  try {
    $U->changePricingPlan(2);
  } catch (Exception $e) {
    throw $e;
  }

  echo "confirm password<br>";
  try {
    $U->confirmPassword("NewPassword");
  } catch (Exception $e) {
    throw $e;
  }

  return $U;
}

function delete(User $User)
{
  try {
    $User->delete("NewPassword");
  } catch (Exception $e) {
    throw $e;
  }
}