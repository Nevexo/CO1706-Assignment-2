<?php

require_once '../php/recommend.php';

session_start();

$user = unserialize($_SESSION['User']);

//Recommendations::update($user);
//print_r(Recommendations::getForUser($user->Id));
print_r(Recommendations::isRecommended($user->Id, 54));