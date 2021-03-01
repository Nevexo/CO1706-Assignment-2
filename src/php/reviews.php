<?php

// Reviews subsystem
// Handles track reviews: getting, adding, deleting etc.

require_once 'auth.php';
require_once 'music.php';

class Review {
  public $Id;
  public $OwnerId;
  public $OwnerName;
  public $Track_Id;
  public $Rating;
  public $Review;

  public function __construct(int $Id, int $OwnerId, string $Username, int $Track_Id, int $Rating, string $Review) {
    $this->Id = $Id;
    $this->OwnerId = $OwnerId;
    $this->OwnerName = $Username;
    $this->Track_Id = $Track_Id;
    $this->Rating = $Rating;
    $this->Review = $Review;
  }

  public function getTrack() {
    // Returns the track instance.
    return Tracks::get($this->Track_Id);
  }
}

class Reviews {
  // Review subsystem functions
  private static function rowToReview($Row) {
    // Takes a row from the reviews table and converts it into a Review object.
    return new Review(
      $Row['review_id'],
      $Row['author_id'],
      $Row['username'],
      $Row['track_id'],
      $Row['rating'],
      $Row['review']
    );
  }

  public static function getForTrack($TrackId) {
    // Get all reviews for a specific track
    global $pdo;

    // Query for all reviews on this track, joining the username from the users table.
    $q = $pdo->prepare("
    SELECT 
        reviews.*, users.username
    FROM
        musicstream.reviews
            JOIN
        users ON users.id = reviews.author_id
    WHERE
    track_id = ?;
    ");
    $result = $q->execute([$TrackId]);
    if (!$result) throw new Exception("QueryFailed");
    if ($q->rowCount() == 0) throw new Exception("NoReviews");

    $Reviews = [];
    foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $review) {
      array_push($Reviews, Reviews::rowToReview($review));
    }

    return $Reviews;
  }

  public static function create(Track $Track, User $User, int $Rating, string $Review) {
    // Create a new review for a track
    global $pdo;
    if ($Rating < 0 or $Rating > 10) throw new Exception("InvalidRatingValue");
    if (strlen($Review) > 250) throw new Exception("ReviewTextTooLong");

    // Initialise the insert query for this review
    $q = $pdo->prepare("INSERT INTO reviews (track_id, author_id, rating, review) VALUES (?, ?, ?, ?)");

    // TODO: Check the user hasn't created a review before for this track

    // Insert the review
    $result = $q->execute([$Track->Id, $User->Id, $Rating, $Review]);
    if (!$result) throw New Exception("QueryFailed");

    return true;
  }
}