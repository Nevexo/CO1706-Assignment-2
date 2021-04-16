<?php
// EcksMusic Reviews subsystem
// Handles track reviews: getting, adding, deleting etc.
// Cameron Paul Fleming - 2021

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

  public function delete(): bool {
    // Delete this review
    global $pdo;

    $query = $pdo->prepare("DELETE FROM reviews WHERE review_id = ?");
    $query->execute([$this->Id]);
    if (!$query) throw new Exception("QueryFailed");

    return true;
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

  public static function getForTrack(int $TrackId): array {
    // Get all reviews for a specific track
    global $pdo;

    // Query for all reviews on this track, joining the username from the users table.
    $q = $pdo->prepare("
    SELECT 
        reviews.*, users.username
    FROM
        reviews
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

  public static function averageRating(int $TrackId) {
    // Get average rating for this track.
    try {
      $TrackReviews = Reviews::getForTrack($TrackId);
    } catch (Exception $e) {
      throw new Exception($e);
    }

    $Ratings = [];
    foreach ($TrackReviews as $Review) {
      array_push($Ratings, $Review->Rating);
    }

    // Taken from Stackoverflow response by user 'Mubin' (2015)
    // https://stackoverflow.com/questions/33461430/how-to-find-average-from-array-in-php
    return round(array_sum($Ratings) / count($Ratings));
  }

  public static function getForUserByTrack(int $TrackId, int $UserId) {
    // Get reviews by a user on a specific track
    try {
      $TrackReviews = Reviews::getForTrack($TrackId);
    } catch (Exception $e) {
      return null;
    }

    foreach ($TrackReviews as $Review) {
      if ($Review->OwnerId == $UserId) {
        return $Review;
      }
    }

    return null;
  }

  public static function getForUser(int $UserId) {
    // Get all reviews by a specific user
    global $pdo;

    $query = $pdo->prepare("
    SELECT 
        reviews.*, users.username
    FROM
        reviews
            JOIN
        users ON users.id = reviews.author_id
    WHERE author_id = ?");
    $result = $query->execute([$UserId]);

    if (!$result) throw new Exception("QueryFailed");

    $reviews = [];
    foreach($query->fetchAll(PDO::FETCH_ASSOC) as $review)
    {
      array_push($reviews, Reviews::rowToReview($review));
    }

    return $reviews;
  }

  public static function create(Track $Track, User $User, int $Rating, string $Review) {
    // Create a new review for a track
    global $pdo;
    // Check the rating is valid, this should be handled by the form first.
    if ($Rating < 0 or $Rating > 10) throw new Exception("InvalidRatingValue");
    // Check the review text isn't too long.
    if (strlen($Review) > 250) throw new Exception("ReviewTextTooLong");
    // Check the user hasn't already reviewed this track.
    if (Reviews::getForUserByTrack($Track->Id, $User->Id) != null) throw new Exception("ExistingReview");

    // Initialise the insert query for this review
    $q = $pdo->prepare("INSERT INTO reviews (track_id, author_id, rating, review) VALUES (?, ?, ?, ?)");

    // TODO: Check the user hasn't created a review before for this track

    // Insert the review
    $result = $q->execute([$Track->Id, $User->Id, $Rating, $Review]);
    if (!$result) throw New Exception("QueryFailed");

    return true;
  }
}