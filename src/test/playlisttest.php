<?php

// Playlist smoketest
// Cameron Paul Fleming 2021

require_once '../php/playlists.php';

function testPlaylists(User $User)
{
  echo "create playlist<br>";
  try {
    $P = Playlists::create($User, "Smoketest Tunes");
  } catch (Exception $e) {
    throw $e;
  }

  echo "add track 1<br>";
  try {
    $P->addTrack(1);
  } catch (Exception $e) {
    throw $e;
  }

  $P = Playlists::get($P->Id);

  echo "check track present - manual<br>";
  $t = $P->Tracks;
  $f = false;
  foreach ($t as $T)
  {
    if ($T->Id == 1)
    {
      $f = true;
    }
  }

  if (!$f) throw new Exception("Track check manual fail");

  echo "check track present - built-in<br>";
  if (!$P->containsTrack(1)) throw new Exception("Track check built-in fail");

  echo "remove track<br>";
  try {
    $P->removeTrack(1);
  } catch (Exception $e) {
    throw $e;
  }

  $P = Playlists::get($P->Id);

  echo "confirm remove<br>";
  if ($P->containsTrack(1)) throw new Exception("Remove track fail");

  echo "random populate<br>";
  $P->randomPopulate(10);
  $P = Playlists::get($P->Id);

  echo "confirm populate 10 tracks<br>";
  $t = $P->Tracks;
  if (count($t) != 10) throw new Exception("Random populate fail");

  echo "Delete playlist<br>";
  try {
    $P->delete();
  } catch (Exception $e) {
    throw $e;
  }

  echo "confirm delete<br>";
  try {
    Playlists::get($P->Id);
    throw new Exception("Delete fail");
  } catch (Exception $e) {
    echo "delete ok<br>";
  }
}