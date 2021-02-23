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

class Tracks {
  // Static functions for accessing track information from the database
  public static function getAll(int $limit = 100, int $after = 0): array
  {
    // Get all tracks
    // limit - Set a limit on the query
    // after - Get all tracks AFTER this ID sorted by ID.
    // Return: array or exception.
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM tracks WHERE track_id > :after ORDER BY track_id LIMIT :limit;");
    $query->bindParam(":limit", $limit, PDO::PARAM_INT);
    $query->bindParam(":after", $after, PDO::PARAM_INT);
    $result = $query->execute();
//    echo $query->debugDumpParams() . "<br>";
//    print_r($query->errorInfo());

    if (!$result) throw new Exception("QueryFailed");

    $Tracks = [];
    // Convert response from MySQL into Track objects.
    foreach($query->fetchAll(PDO::FETCH_ASSOC) as $TrackEntry) {
      array_push($Tracks, new Track($TrackEntry));
    }

    return $Tracks;
  }
}