<?php
// EcksMusic Recommendations Subsystem
// Cameron Paul Fleming - 2021

require_once 'database.php';
require_once 'music.php';
require_once 'reviews.php';

// -- Explanation --
// EcksMusic recommendations are based on reviews the user has left on other tracks. To reduce server & database load
// recommendations are cached to the recommendations table and recalculated whenever the user creates/deletes
// a review. The system will pick a random subset of tracks from the database and remove/prioritise certain
// tracks by the following weightings.

// | Object Type | Weighting |
// |-------------|-----------|
// | Genre       | 50%       |
// | Album       | 30%       |
// | Artist      | 20%       |

class Recommendation
{
  // A single track recommendation, stores the target track and "influence" track, which allows the user
  // to see which review cause this recommendation.

  public $Id;
  public $Track;
  public $InfluenceTrack;
  public $User;

  public function __construct(int $id, Track $track, Track $influenceTrack, User $user)
  {
    $this->Id = $id;
    $this->Track = $track;
    $this->InfluenceTrack = $influenceTrack;
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
    print_r($this->Reviews);
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
    // Get 30 random tracks which will be used as the baseline for recommendations.
    try {
      return Tracks::random(30);
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

}

class Recommendations
{
  // Static functions for generating & accessing recommendations

  public static function generate(User $User)
  {
    $e = new RecommendationEngine($User);
  }
}