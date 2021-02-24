<?php
require_once "database.php";

class Track {
  public $Id;
  public $Artist;
  public $Album;
  public $Description;
  public $Name;
  public $Genre;
  public $ImagePath;
  public $ThumbPath;
  public $SamplePath;

  public function __construct($QueryResponse)
  {
    // Construct a Track from a MySQL query response
    $this->Id = $QueryResponse['track_id'];
    $this->Artist = $QueryResponse['artist'];
    $this->Album = $QueryResponse['album'];
    $this->Description = $QueryResponse['description'];
    $this->Name = $QueryResponse['name'];
    $this->Genre = $QueryResponse['genre'];
    $this->ImagePath = $QueryResponse['image'];
    $this->ThumbPath = $QueryResponse['thumb'];
    $this->SamplePath = $QueryResponse['sample'];
  }
}

class Tracks
{
  // Static functions for accessing track information from the database
  public static function getAll(int $limit = 100, int $after = 0): array
  {
    // Get all tracks
    // limit - Set a limit on the query
    // after - Get all tracks AFTER this ID sorted by ID.
    // Return: array or exception.
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM tracks WHERE track_id > :after ORDER BY track_id LIMIT :limit;");
    // pdo::execute converts all parameters into strings, this is rejected by MySQL so the parameters must be
    // set manually to ensure the require type is sent.
    $query->bindParam(":limit", $limit, PDO::PARAM_INT);
    $query->bindParam(":after", $after, PDO::PARAM_INT);
    $result = $query->execute();

    if (!$result) throw new Exception("QueryFailed");

    $Tracks = [];
    // Convert response from MySQL into Track objects.
    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $TrackEntry) {
      array_push($Tracks, new Track($TrackEntry));
    }

    return $Tracks;
  }

  public static function get(int $Id): Track
  {
    // Get a specific track from the Tracks table.
    // id = a track_id
    // Expect Track
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM tracks WHERE track_id = ?");
    $success = $query->execute([$Id]);
    if (!$success) throw new Exception("QueryFailed");
    if ($query->rowCount() == 0) throw new Exception("InvalidTrack");
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return new Track($result);
  }

  public static function search(string $Type, string $Query)
  {
    // Search for a track by artist/album/name/genre
    // type - the type of query to search for ^^
    // query - the term to search for in the database
    global $pdo;

    // Switch each type of query for safety.
    switch ($Type) {
      case "track":
        $sqlQuery = $pdo->prepare("SELECT * FROM tracks WHERE name LIKE :searchQuery");
        break;
      case "artist":
        $sqlQuery = $pdo->prepare("SELECT * FROM tracks WHERE artist LIKE :searchQuery");
        break;
      case "album":
        $sqlQuery = $pdo->prepare("SELECT * FROM tracks WHERE album LIKE :searchQuery");
        break;
      case "genre":
        $sqlQuery = $pdo->prepare("SELECT * FROM tracks WHERE genre LIKE :searchQuery");
        break;
      default:
        throw new Exception("InvalidSearchQueryType");
    }
    $sqlQuery->bindValue(":searchQuery", "%" . $Query . "%");

    // Parameters were set above, so just execute the query.
    $success = $sqlQuery->execute();
    if (!$success) throw new Exception("QueryFailed");
    if ($sqlQuery->rowCount() == 0) throw new Exception("NoResults");

    // Convert results into Track objects
    $Tracks = [];
    foreach ($sqlQuery->fetchAll(PDO::FETCH_ASSOC) as $result) {
      array_push($Tracks, new Track($result));
    }

    return $Tracks;
  }
}