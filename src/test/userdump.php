<?php
// Testing scripts to dump the users table
require_once 'database.php';

$q = $pdo->query("SELECT * FROM users");

foreach($q->fetchAll(PDO::FETCH_ASSOC) as $user) {
  echo "<hr>";
  echo $user['username'] . "<br>";
  echo "User ID: " . $user['id'] . "<br>";
  echo "Pricing Plan: " . $user['offer_id'] . "<br>";
  echo "Reviews:<br>";
  $r = $pdo->query("SELECT * FROM reviews WHERE author_id = " . $user['id']);
  foreach($r->fetchAll(PDO::FETCH_ASSOC) as $review) {
    echo " -- Track: " . $review['track_id'] . "<br>";
    echo ".     > Rating: " . $review['rating'] . "<br>";
    echo ".     > Review: " . $review['review'] . "<br>";
  }
}