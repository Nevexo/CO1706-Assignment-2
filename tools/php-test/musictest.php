<?php

// Smoketest music collection functions
// Cameron Paul Fleming 2021

require_once '../php/music.php';

function testMusic()
{
  echo "get specific track<br>";
  try {
    $Track = Tracks::get(1);
    if ($Track->Name != "Taking A Seat") throw new Exception("Tracks::get() validation fail");
    print_r($Track);

  } catch (Exception $e) {
    throw $e;
  }

}