<h1>EcksMusic smoketest suite</h1>

<?php

// Smoketest tool - runs all tests against the platform.
// Cameron Paul Fleming 2021

require_once 'user.php';
require_once 'musictest.php';
require_once 'playlisttest.php';

echo "<h2>Testing User Creation & Management</h2>";
try {
  $User = create();
} catch (Exception $e) {
  echo "<p>ERROR: " . $e->getMessage() . "</p>";
}

print_r($User);
echo "<hr>";

echo "<h2>Testing Track Information</h2>";
try {
  testMusic();
} catch(Exception $e) {
  echo "<p>ERROR: " . $e->getMessage() . "</p>";
}
echo "<hr>";

echo "<h2>Testing Playlist subsystem</h2>";
try {
  testPlaylists($User);
} catch (Exception $e)
{
  echo "<p>ERROR: " . $e->getMessage() . "</p>";
}
echo "<hr>";

echo "<h2>Testing User Deletion</h2>";
try {
  delete($User);
  echo "OK";
} catch (Exception $e) {
  echo "<p>ERROR: " . $e->getMessage() . "</p>";
}
echo "<hr>";