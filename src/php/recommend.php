<?php
// EcksMusic Recommendations Subsystem
// Cameron Paul Fleming - 2021

// TODO COMPLETE INTEGRATION

// --- Explanation ---
// The recommendations system works on reviews the user has added to tracks before. It collects all tracks reviewed
// and uses the rating value (1-10) to add a weighting to each recommendation.
// album - highest weighting
// artist - medium weighting
// genre - lowest weighting
// The system then searches the database for tracks sorted by the weightings and returns X tracks that the user
// may like.
// The system also calculates a "certainty" value - if this value is low a warning will be displayed to the user,
// prompting them to review more tracks to personalise the recommendations further.

require_once 'database.php';
require_once 'music.php';

function getAllReviews(int $UserId): array
{
  global $pdo;

  $query = $pdo->prepare("SELECT reviews.*, users.username FROM reviews JOIN users ON users.id = reviews.author_id
                                WHERE author_id = ?");
  $result = $query->execute([$UserId]);
  if (!$result) throw new Exception("QueryFailed");
  if ($query->rowCount() == 0) throw new Exception("NoReviews");

  $reviews = [];
  foreach($query->fetchAll(PDO::FETCH_ASSOC) as $review) {
    array_push($reviews, new Review(
      $review['review_id'], $review['author_id'], $review['username'], $review['track_id'],
      $review['rating'], $review['review']
    ));
  }

  return $reviews;
}

