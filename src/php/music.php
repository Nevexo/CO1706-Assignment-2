<?php
// EcksMusic Track, Artist & Album definitions
// Cameron Paul Fleming - 2021

require_once "database.php";
require_once "reviews.php";

class Artist {
  public $Id;
  public $Name;

  public function __construct(int $Id, string $Name) {
    $this->Id = $Id;
    $this->Name = $Name;
  }
}

class Album {
  public $Id;
  public $Name;
  public $Artist;

  public function __construct(int $Id, string $Name, Artist $Artist) {
    $this->Id = $Id;
    $this->Name = $Name;
    $this->Artist = $Artist;
  }
}

class Track {
  public $Id;
  public $Artist;
  public $Album;
  public $Description;
  public $Name;
  public $FullName;
  public $Genre;
  public $ImagePath;
  public $ThumbPath;
  public $SamplePath;
  public $AverageRating;

  public function __construct($result)
  {
    // Construct a Track from a MySQL query result
    $this->Id = $result['track_id'];
    $this->Artist = new Artist($result['artist_id'], $result['artist_name']);
    $this->Album = new Album($result['album_id'], $result['album_name'], $this->Artist);
    $this->Description = $result['description'];
    $this->Name = $result['track_name'];
    $this->Genre = $result['genre'];
    $this->ImagePath = $result['image'];
    $this->ThumbPath = $result['thumbnail'];
    $this->SamplePath = $result['sample'];
    $this->FullName = $this->Artist->Name . ' - ' . $this->Name;
    try {
      $this->AverageRating = Reviews::averageRating($this->Id) . "/10";
    } catch (Exception $e) {
      $this->AverageRating = "N/A";
    }
  }

  public function prettyPrint() {
    // Pretty-print this track as HTML.
    // TODO: Average rating & recommended for you labels.
    // TODO: Track hyperlinks.
    return '
      <div class="card">
        <img class="card-img-top" src="../' . $this->ImagePath . '" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title">
            ' . $this->Name . '
          </h5>
          <p>
            <!--<span class="badge badge-warning"><span class="fas fa-star"></span> Recommended for You</span>-->
            <span class="badge badge-info"><span class="fas fa-music"></span> Genre: ' . $this->Genre . '</span>
            <span class="badge badge-success"><span class="fas fa-user-edit"></span> Average Rating: ' . $this->AverageRating . '</span>
          </p>
          <p class="text-muted"><span title="Artist" class="fas fa-users"></span> ' . $this->Artist->Name . '</p>
          <p class="text-muted"><span title="Album" class="fas fa-compact-disc"></span> ' . $this->Album->Name . '</p>
          <a href="track.php?id=' . $this->Id . '" class="card-link">More Info</a>
          <a href="#" class="card-link">Add to Playlist</a>
        </div>
      </div>
    ';
  }
}

class Tracks
{
  // Static functions for accessing track information from the database
  public static function trackCount(): int {
    // Get the number of tracks in the database, used for pagination.
    global $pdo;

    $result = $pdo->query("SELECT COUNT(*) FROM tracks;");
    if (!$result) throw new Exception("QueryFailed");

    return $result->fetch(pdo::FETCH_NUM)[0];
  }

  public static function getAll(int $limit = 100, int $after = 0): array
  {
    // Get all tracks
    // limit - Set a limit on the query
    // after - Get all tracks AFTER this ID sorted by ID.
    // Return: array or exception.
    global $pdo;

    $query = $pdo->prepare(
      "SELECT * FROM tracks NATURAL JOIN artists NATURAL JOIN albums 
             WHERE track_id > :after ORDER BY track_id LIMIT :limit;"
    );
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

    $query = $pdo->prepare("SELECT * FROM tracks NATURAL JOIN artists NATURAL JOIN albums WHERE track_id = ?");
    $success = $query->execute([$Id]);
    if (!$success) throw new Exception("QueryFailed");
    if ($query->rowCount() == 0) throw new Exception("InvalidTrack");
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return new Track($result);
  }

  public static function random(int $Count = 1): array
  {
    // Get random selection of songs (limited to $Count)
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM tracks NATURAL JOIN artists NATURAL JOIN albums
                                  ORDER BY RAND() LIMIT :limit");
    $query->bindValue("limit", $Count, PDO::PARAM_INT);
    $result = $query->execute();
    if (!$result) throw new Exception("QueryFailed");

    $tracks = [];
    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $track) {
      array_push($tracks, new Track($track));
    }

    return $tracks;
  }

  public static function search(string $Type, string $Query)
  {
    // Search for a track by artist/album/name/genre
    // type - the type of query to search for ^^
    // query - the term to search for in the database
    global $pdo;

    // Switch each type of query for safety.
    $query = "SELECT * FROM tracks NATURAL JOIN artists NATURAL JOIN albums";
    // TODO: Refactor
    switch ($Type) {
      case "track":
        $sqlQuery = $pdo->prepare($query . " WHERE name LIKE :searchQuery");
        break;
      case "artist":
        $sqlQuery = $pdo->prepare($query ." WHERE artist LIKE :searchQuery");
        break;
      case "album":
        $sqlQuery = $pdo->prepare($query . " WHERE album LIKE :searchQuery");
        break;
      case "genre":
        $sqlQuery = $pdo->prepare($query . " WHERE genre LIKE :searchQuery");
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

class TrackPaginator
{
  // This class handles track pagination, as there's over 100 tracks in the database we don't want to
  // get this many results from the database constantly, and certainly don't want to push that many tracks
  // to the user.
  private $trackCount = 0;
  public $pages = 0;

  public function __construct() {
    global $PAGINATION_PAGE_TRACKS;

    try {
      // Get number of tracks in the database.
      $this->trackCount = Tracks::trackCount();
    } catch (Exception $e) {
      throw $e;
    }

    // Split the track count into pages, adding 1 to give the last page which won't have the full amount of items.
    // TODO: Kind of a random number?
    $this->pages = round($this->trackCount / $PAGINATION_PAGE_TRACKS) + 1;
  }

  public function getPage(int $page) {
    // Get the tracks for a specific page
    if ($page > $this->pages) throw new Exception("InvalidPage");

    global $PAGINATION_PAGE_TRACKS;

    // Get all tracks, limited to $PAGINATION_PAGE_TRACKS (vars.php) and from after the page's track ID.
    if ($page == 1) {
      $lastId = 0;
    } else {
      $lastId = $PAGINATION_PAGE_TRACKS * ($page - 1);
    }

    return Tracks::getAll($PAGINATION_PAGE_TRACKS, $lastId);
  }
}