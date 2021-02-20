<?php
session_start();
require_once 'auth.php';

print_r($_SESSION['User']);
$user = unserialize($_SESSION['User']);
echo $user->Username;
?>