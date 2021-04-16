<?php

// Simple script to create a playlist with all tracks

require_once '../php/playlists.php';
require_once '../php/music.php';
require_once '../php/auth.php';

$tracks = Tracks::getAll(2000);
$U = Users::login("cameron", "password");

$P = Playlists::create($U, "All Tracks Playlist");
$P->setPublic(true);

foreach($tracks as $t)
{
  echo $t->Name . "<br>";
  $P->addTrack($t->Id);
}
