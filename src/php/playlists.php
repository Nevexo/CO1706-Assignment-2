<?php
// EcksMusic Playlists Subsystem
// Cameron Paul Fleming - 2021

require_once 'database.php';
require_once 'music.php';
require_once 'auth.php';

class PlaylistEntry
{
  // Entry in a playlist, i.e. a single track within a playlist.
  public $Id;
  public $Track;

  public function __construct(int $Id, Track $Track)
  {
    $this->Id = $Id;
    $this->Track = $Track;
  }
}

class Playlist
{
  // A playlist, holds an array of tracks.
  public $Id;
  public $OwnerId;
  public $Name;
  public $Tracks;

  public function __construct(int $Id, int $OwnerId, string $Name)
  {
    global $pdo;

    $this->Id = $Id;
    $this->OwnerId = $OwnerId;
    $this->Name = $Name;
    $this->Tracks = [];

    // Get tracks from database
    $query = $pdo->prepare("SELECT * FROM playlist_entries NATURAL JOIN tracks NATURAL JOIN albums NATURAL JOIN artists WHERE playlist_id = ?");
    $result = $query->execute([$this->Id]);
    if (!$result) throw new Exception("QueryFailed");

    // Create a new Track object for all tracks and add to Tracks array.
    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $Track) {
      array_push($this->Tracks, new Track(
        $Track
      ));
    }
  }

  public function addTrack(int $TrackId): bool
  {
    // Add a track to this playlist
    global $pdo;

    if ($this->containsTrack($TrackId)) throw new Exception("DuplicateTrack");
    $query = $pdo->prepare("INSERT INTO playlist_entries (playlist_id, track_id) VALUES (?, ?)");
    $result = $query->execute([$this->Id, $TrackId]);
    if (!$result) throw new Exception("QueryFailed");

    return true;
  }

  public function containsTrack(int $TrackId): bool
  {
    // Check if this playlist has a specific track
    foreach ($this->Tracks as $Track) {
      if ($Track->Id == $TrackId) {
        return true;
      }
    }

    return false;
  }

  public function removeTrack(int $TrackId): bool
  {
    // Remove a track from this playlist
    global $pdo;
    if (!$this->containsTrack($TrackId)) throw new Exception("InvalidTrack");

    $query = $pdo->prepare("DELETE FROM playlist_entries WHERE track_id = ?");
    $result = $query->execute([$TrackId]);
    if (!$result) throw new Exception("QueryFailed");

    return true;
  }

  public function delete(): bool
  {
    // Delete this playlist
    global $pdo;

    // Drop all entries from this playlist and then remove the playlist.
    $query = $pdo->prepare("DELETE FROM playlist_entries WHERE playlist_id = :playlistId;
                                  DELETE FROM playlists WHERE playlist_id = :playlistId");
    $query->bindValue("playlistId", $this->Id);
    $result = $query->execute();
    if (!$result) throw new Exception("QueryFailed");

    return true;
  }

  public function rename(string $NewName): bool
  {
    // Rename this playlist
    global $pdo;

    $query = $pdo->prepare("UPDATE playlists SET playlist_name = ? WHERE playlist_id = ?");
    $result = $query->execute([$NewName, $this->Id]);
    if (!$result) throw new Exception("QueryFailed");

    return true;
  }

  public function randomPopulate(int $Count = 10)
  {
    // Populate this playlist with $Count random tracks.
    $tracks = Tracks::random($Count);

    foreach ($tracks as $track) {
      try {
        $this->addTrack($track->Id);
      } catch (Exception $e) {
      }
    }
  }
}

class Playlists
{
  static function get(int $Id): Playlist
  {
    // Get a playlist by it's ID number.
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM playlists WHERE playlist_id = ?");
    $result = $query->execute([$Id]);
    if (!$result) throw new Exception("QueryFailed");
    if ($query->rowCount() == 0) throw new Exception("InvalidPlaylist");
    $playlist = $query->fetch(PDO::FETCH_ASSOC);

    // Return a playlist
    return new Playlist(
      $playlist['playlist_id'],
      $playlist['owner_id'],
      $playlist['playlist_name']
    );
  }

  static function getForUser(int $UserId): array
  {
    // Get playlists for a user
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM playlists WHERE owner_id = ?");
    $result = $query->execute([$UserId]);
    if (!$result) throw new Exception("QueryFailed");

    $Playlists = [];
    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $playlist) {
      array_push($Playlists, new Playlist(
        $playlist['playlist_id'],
        $playlist['owner_id'],
        $playlist['playlist_name']
      ));
    }

    return $Playlists;
  }

  static function create(User $User, string $Name): Playlist
  {
    // Create a new playlist
    global $pdo;
    $query = $pdo->prepare("INSERT INTO playlists (owner_id, playlist_name) VALUES (?, ?)");
    $result = $query->execute([$User->Id, $Name]);
    if (!$result) throw new Exception("QueryFailed");

    // Return a playlist object
    return new Playlist(
      $pdo->lastInsertId(),
      $User->Id,
      $Name
    );
  }
}