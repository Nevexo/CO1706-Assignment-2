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


}

class Recommendations
{
  // Static functions for generating & accessing recommendations

  public static function generate(User $User)
  {
    $e = new RecommendationEngine($User);
  }
}