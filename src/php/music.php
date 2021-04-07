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
    // TODO: Recommended for you labels.
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

  public static function getAll(int $limit = 200, int $after = 0): array
  {
    // Get all tracks
    // limit - Set a limit on the query
    // after - Get all tracks AFTER this ID sorted by ID. DEPRECATED
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

  public static function getAlbum(int $AlbumId): array
  {
    // Get tracks from the specified album
    global $pdo;

    // Cancel if this album doesn't exist
    try {
      Albums::get($AlbumId);
    } catch (Exception $e) {throw new Exception("InvalidAlbum");}

    // Run the query to find all tracks with this specific album ID.
    $query = $pdo->prepare("SELECT * FROM tracks NATURAL JOIN artists NATURAL JOIN albums WHERE album_id = ?");
    $success = $query->execute([$AlbumId]);
    if (!$success) throw new Exception("QueryFailed");
    if ($query->rowCount() == 0) throw new Exception("NoTracksFound");

    // Convert results into Track objects
    $Tracks = [];
    foreach($query->fetchAll(PDO::FETCH_ASSOC) as $TrackEntry)
    {
      array_push($Tracks, new Track($TrackEntry));
    }

    return $Tracks;
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

  public static function getGenreList(): Array
  {
    // Get a list of available genres from the database
    // Genres are not relational in this database, so they must be manually queried.
    global $pdo;

    $query = "SELECT DISTINCT genre FROM tracks";
    $response = $pdo->query($query);
    if (!$response) throw new Exception("QueryFailed");

    return $response->fetchAll(PDO::FETCH_ASSOC);
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
  // This class paginates (splits into pages) an array of Track objects to reduce the size of the final
  // Tracks.php page, this can also be implemented anywhere a lot of tracks are displayed.
  private $trackCount = 0;
  public $pageCount = 0;
  private $tracks;

  public function __construct(Array $Tracks)
  {
    $this->trackCount = count($Tracks);

    // Calculate number of pages, add 1 if the final page will have less than PAGINATION_PAGE_TRACKS tracks.
    $this->pageCount = round($this->trackCount / PAGINATION_PAGE_TRACKS);
    if ($this->trackCount % PAGINATION_PAGE_TRACKS != 0) $this->pageCount++;
    $this->tracks = $Tracks;
  }

  public function getPage(int $page = 1): Array
  {
    // Get track listing for this page
    if ($page > $this->pageCount) throw new Exception("InvalidPage");

    if ($page == 1) {
      $lastId = 0;
    } else {
      $lastId = PAGINATION_PAGE_TRACKS * ($page - 1);
    }

    return array_slice($this->tracks, $lastId, PAGINATION_PAGE_TRACKS);
  }
}

class Albums
{
  // Static functions for accessing album information
  public static function get(int $Id): Album
  {
    // Get a specific album by it's ID number
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM albums NATURAL JOIN artists WHERE album_id = ?");
    $success = $query->execute([$Id]);
    if (!$success) throw new Exception("QueryFailed");
    if ($query->rowCount() == 0) throw new Exception("InvalidAlbum");
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return new Album(
      $result['album_id'], $result['album_name'],
      new Artist($result['artist_id'], $result['artist_name'])
    );
  }

  public static function getAll(int $artist = null): array
  {
    // Get all albums, from all or specific artists.
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM albums NATUAL JOIN artists");
    $success = $query->execute();
    if (!$success) throw new Exception("QueryFailed");

    $Albums = [];
    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result)
    {
      // Skip any tracks not by $artist (if defined)
      if (!is_null($artist) && $result['artist_id'] != $artist) continue;
      array_push($Albums,
        new Album(
          $result['album_id'], $result['album_name'],
          new Artist($result['artist_id'], $result['artist_name'])
        )
      );
    }

    return $Albums;
  }
}

class Artists
{
  // Static functions for accessing artist information
  public static function getAll(): array
  {
    // Get all artists
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM artists");
    $success = $query->execute();
    if (!$success) throw new Exception("QueryFailed");

    $Artists = [];
    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result)
    {
      // Skip any tracks not by $artist (if defined)
      array_push($Artists,
        new Artist(
          $result['artist_id'], $result['artist_name']
        )
      );
    }

    return $Artists;
  }
}