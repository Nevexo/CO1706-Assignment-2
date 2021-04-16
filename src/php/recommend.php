<?php
// EcksMusic Recommendations Subsystem
// Cameron Paul Fleming - 2021

require_once 'database.php';
require_once 'music.php';
require_once 'reviews.php';
require_once 'vars.php';
require_once 'recommend.php';

// -- Explanation --
// EcksMusic recommendations are based on reviews the user has left on other tracks. To reduce server & database load
// recommendations are cached to the recommendations table and recalculated whenever the user creates/deletes
// a review. The system will pick a random subset of tracks from the database and sort them using the reviews the user
// has given to tracks with the same genre, album or artist.


class Recommendation
{
  // A single track recommendation, stores the target track and recommendation ID.

  public $Id;
  public $Track;
  public $User;

  public function __construct(int $id, Track $track, User $user)
  {
    $this->Id = $id;
    $this->Track = $track;
    $this->User = $user;
  }
}

class RecommendationEngine
{
  // Functions for generating recommendations for a user
  private $User;
  private $Reviews;
  public function __construct(User $user)
  {
    $this->User = $user;
    $this->Reviews = $this->getReviews();
  }

  private function getReviews(): Array
  {
    // Get this user's reviews
    try {
      return Reviews::getForUser($this->User->Id);
    } catch (Exception $e) {
      throw $e;
    }
  }

  private function getRandomTracks(): Array
  {
    // Get N random tracks which will be used as the baseline for recommendations.
    try {
      return Tracks::random(RECOMMENDATION_TRACK_COUNT);
    } catch (Exception $e) {
      throw $e;
    }
  }

  private function getAverageRatings(): Array
  {
    // Get average ratings for genres, albums & artists.

    // Stage 1 - Get all ratings for each genre, album and artist.
    $genres = new stdClass(); // Using an empty class here to track the different data without enumerating arrays.
    $albums = new stdClass();
    $artists = new stdClass();

    foreach($this->Reviews as $review)
    {
      // Resolve the reviewed track
      $track = $review->getTrack();

      // Add all elements of this review to their respective arrays.
      // If this type hasn't been reviewed yet, initialise it with a new array.
      if (!property_exists($genres, $track->Genre)) $genres->{$track->Genre} = [];
      if (!property_exists($albums, $track->Album->Name)) $albums->{$track->Album->Name} = [];
      if (!property_exists($artists, $track->Artist->Name)) $artists->{$track->Artist->Name} = [];

      // Push this review to every array
      array_push($genres->{$track->Genre}, $review->Rating);
      array_push($albums->{$track->Album->Name}, $review->Rating);
      array_push($artists->{$track->Artist->Name}, $review->Rating);
    }

    // Stage 2 - Calculate averages
    $genreAverages = $albumAverages = $artistAverages = [];

    // Average genres
    foreach($genres as $genre => $ratings)
    {
      array_push($genreAverages, [
        $genre,
        round(array_sum($ratings) / count($ratings))
      ]);
    }

    // Average albums
    foreach($albums as $album => $ratings)
    {
      array_push($albumAverages, [
        $album,
        round(array_sum($ratings) / count($ratings))
      ]);
    }

    // Average artists
    foreach($artists as $artist => $ratings)
    {
      array_push($artistAverages, [
        $artist,
        round(array_sum($ratings) / count($ratings))
      ]);
    }

    return [$genreAverages, $albumAverages, $artistAverages];
  }

  private function orderTracksByAverages(): Array
  {
    // Get N random tracks.
    $tracks = $this->getRandomTracks();

    // Get average ratings of all meta-types.
    $averages = $this->getAverageRatings();
    $genreAvg = $averages[0];
    $albumAvg = $averages[1];
    $artistAvg = $averages[2];

    // Go through each track and add a "weight" property using the ratings averages.
    foreach($tracks as $track)
    {
      $track->RatingWeight = 0;
      // Add average for genre
      foreach($genreAvg as $genre) if ($genre[0] == $track->Genre) $track->RatingWeight += $genre[1];

      // Add average for album
      foreach($albumAvg as $album) if ($album[0] == $track->Album->Name) $track->RatingWeight += $album[1];

      // Add average for artist
      foreach($artistAvg as $artist) if ($artist[0] == $track->Artist->Name) $track->RatingWeight += $artist[1];
    }

    // Sort tracks by RatingWeight.
    // Adapted from Stackoverflow answer by Scott Quinlan (April 15, 2012)
    // https://stackoverflow.com/questions/4282413/sort-array-of-objects-by-object-fields
    usort($tracks, function($a, $b)
    {
      return $a->RatingWeight < $b->RatingWeight;
    });

    return $tracks;
  }

  public function getRecommendations(): Array
  {
    // Getter function for returning active recommendations.
    return $this->orderTracksByAverages();
  }

}

class Recommendations
{
  // Static functions for generating & accessing recommendations

  private static function clear(int $UserId): bool
  {
    // Clear current recommendations for this user.
    global $pdo;

    $query = $pdo->prepare("DELETE FROM recommendations WHERE user_id = ?");
    $success = $query->execute([$UserId]);
    if (!$success) throw new Exception("QueryFailed");

    return true;
  }

  public static function getForUser(int $UserId, int $limit = RECOMMENDATION_TRACK_COUNT): array
  {
    // Get recommendations for a user.
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM recommendations
                                  NATURAL JOIN tracks NATURAL JOIN artists NATURAL JOIN albums 
                                  WHERE user_id = :user
                                  LIMIT :limit");
    $query->bindParam("user", $UserId, PDO::PARAM_INT);
    $query->bindParam("limit", $limit, PDO::PARAM_INT);
    $success = $query->execute();
    if (!$success) throw new Exception("QueryFailed");

    $tracks = [];
    foreach($query->fetchAll(PDO::FETCH_ASSOC) as $track)
    {
      array_push($tracks, new Track($track));
    }

    return $tracks;
  }

  public static function update(User $User)
  {
    // Update the recommendations table
    global $pdo;

    // Clear existing recommendations.
    try {
      Recommendations::clear($User->Id);
    } catch (Exception $e) {
      throw $e;
    }

    // Get updated recommendations for this user
    $re = new RecommendationEngine($User);
    $recommendations = $re->getRecommendations();

    // Write the updated recommendations to the database
    $query = $pdo->prepare("INSERT INTO recommendations (user_id, track_id) VALUES (?, ?)");

    foreach($recommendations as $recommendation)
    {
      $query->execute([$User->Id, $recommendation->Id]);
    }

  }
}