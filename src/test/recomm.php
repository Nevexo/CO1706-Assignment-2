<?php

require_once '../php/recommend.php';

session_start();

$user = unserialize($_SESSION['User']);

Recommendations::generate($user);